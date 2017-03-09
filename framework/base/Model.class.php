<?php
/**
 * 数据库操作类
 * @author Seldoon <Sedloon@sina.cn>
 */

namespace framework\base;

use framework\library\Object;
use framework\database\DB;
use framework\base\ErrorException;
use framework\log\Log;

/**
 * 数据抽象层
 */
class Model extends Object {

    // 数据库句柄
    protected $db;
    // 数据库表名称
    protected $tableName;
    // 表子段
    protected $tableFileds;
    // 在保存的时候判断是插入还是更新,默认为FALSE 表示插入新的数据
    protected $isNew = FALSE;
    // 查询的字段,默认为所有
    protected $select = ' * ';
    // 执行的SQL语句
    protected $sql;
    
    protected $where;
    protected $orderBy;
    protected $groupBy;
    protected $limit;
    
    // 参数绑定
    protected $param = [];
    // 数据字段属性,用作映射数据库字段
    private $_attributes;
    // 数据库里的字段数据（原数据包）
    private $_attributesOld;

    public function __construct($tableName = NULL) {
        $tableName = $tableName ? : strtolower($this->getClass($this));
        $this->tableName = $tableName;
        parent::__construct();
    }

    public function __get($name) {
        $this->setTableFileds();

        if (isset($this->_attributes[$name])) {
            return $this->_attributes[$name];
        }
        return FALSE;
    }

    public function __set($name, $value) {
        $this->setTableFileds();
        if (in_array($name, $this->tableFileds)) {
            $this->_attributes[$name] = $value;
        } else {
            return FALSE;
        }
    }

    /**
     * 获得PDO资源标识符(句柄)
     * @return PDO resource
     */
    public function getDB() {
        // 链接数据库
        $this->db || $this->db = DB::getDB();
    }

    /**
     * 取所有条件内的数据
     * @return \framework\base\className    结果的数组,每个元素为一个对象模型
     */
    public function fetchAll() {
        $data = [];
        $stmt = $this->query();
        $results = $stmt->fetchAll();

        foreach ($results as $item) {
            $className = $this->getClass($this, TRUE);
            // 实例当前类
            $class = $className::instantiate();
            foreach ($item as $key => $val) {
                $class->_attributes[$key] = $val;
                $class->_attributesOld[$key] = $val;
            }
            $data[] = $class;
        }

        return $data;
    }

    /**
     * 取一条数据
     * @param type $id 当传入一个id时,则表示取id为当前id的一条数据
     * @return \framework\base\Model 返回当前对象模型
     */
    public function fetch($id = NULL) {
        if ($id !== NULL) {
            $this->where(['id', '=', (int) $id]);
        }
        $stmt = $this->query();
        $result = $stmt->fetch();
        if ($result) {
            foreach ($result as $key => $val) {
                $this->_attributes[$key] = $val;
                $this->_attributesOld[$key] = $val;
            }
        }
        return $this;
    }

    /**
     * 查询的字段,默认为 所有字段 *
     * @param type $select ['id', 'name']
     * @return type String 字段
     */
    public function select($select = []) {
        if (!empty($select)) {
            $this->select = '';
            foreach ($select as $filed) {
                $this->select .= " {$filed} ,";
            }

            $this->select = rtrim($this->select, ' ,');
        }
        
        return $this;
    }

    /**
     * 查询条件
     * @param type $where mixed
     * @param 参数类型
     *         参数类型1 String 'field = value';
     *         参数类型2 Array ['field' => $value]
     *         参数类型3 Array ['field',$condition,$value];//$condition 为[<,>,=,!=,like]
     *         参数类型4 Array ['field',$condition,[$value]];//$condition 为[in,not in]
     * @return \framework\base\Model
     */
    public function where($where) {
        
        if ($this->where === NULL) {
            $this->where = 'WHERE';
        }
        if ($this->where !== NULL AND $this->where != 'WHERE') {
            $this->where .= ' AND ';
        }

        if (is_string($where)) {
            $this->where = $where;
        } else if (is_array($where)) {
            $count = count($where);
            
            if ($count == 1) {
                foreach ($where as $key => $val) {
                    $this->where .= " `{$key}` = :{$key} ";
                    $this->param[":$key"] = "{$val}";
                }
            } else if (!is_numeric(array_keys($where)[0])) {
                foreach ($where as $key => $val) {
                    $this->where .= " `{$key}` = :{$key} AND ";
                    $this->param[":$key"] = "{$val}";
                }
                $this->where = trim($this->where, ' AND ');
            } else if ($count == 3) {

                $name = trim($where[0]);
                $condition = strtoupper(trim($where[1]));
                $values = $where[2];
                
                $placeHolder = ":" . $name;
                $this->param[$placeHolder] = $values;

                if ($condition == 'LIKE') {
                    $this->handleConditionLike($values, $name);
                } elseif (($condition == 'IN' || $condition == 'NOT IN') && is_array($values)) {
                    $placeHolder = $this->handleConditionIn($values, $name);
                }

                $this->where .= " `{$name}` {$condition} {$placeHolder}  ";
            }
        }

        return $this;
    }

    /**
     * 处理SQL语句的条件 LIKE 
     * @param type $like    数据的值
     * @param type $name 占位符名称
     */
    private function handleConditionLike($like, $name) {
        // 多个LIKE 处理
//        if (is_array($like)) {
//            $val = "%{$val}%";
//        }
        $like = trim($like);
        if (empty($like)) {
             throw new ErrorException('LIKE 不能为空!', 500);
        }
        // 字符前或者后都不含有%
        if (($like{0} != '%') && ($like{strlen($like) - 1} != '%')) {
            $like = "%{$like}%";
        }
        $this->param[":{$name}"] = $like;
    }

    /**
     * 处理SQL语句中的IN条件
     * @param type $in
     * @param type $name
     * @return string
     */
    private function handleConditionIn($in, $name) {
        $this->param[":{$name}"] = [];
        $placeHolder = [];
        foreach ($in as $v) {
            $placeHolder [] = $v;
            $this->param[":{$name}"][":{$v}"] = "{$v}";
        }
        $placeHolder = ' (:' . join(', :', $placeHolder) . ')';
        return $placeHolder;
    }

    /**
     * 字段排序
     * @param type $orderBy mixed
     *         参数类型1 String 'filed ASC'
     *         参数类型2 Array ['filed1'=> 'ASC','filed2'=> 'DESC']
     * @return \framework\base\Model
     */
    public function orderBy($orderBy) {
        if (is_string($orderBy)) {
            $this->orderBy = " ORDER BY {$orderBy} ASC ";
        } else if (is_array($orderBy) && !empty($orderBy)) {
            $this->orderBy = ' ORDER BY ';
            foreach ($orderBy as $key => $val) {
                $val == '' && $val = ' ASC ';
                $this->orderBy .= "{$key}  $val ,";
            }
        }
        $this->orderBy = rtrim($this->orderBy, ' ,');
        return $this;
    }
    
    /**
     * 查询条数
     * @param type $offset
     * @param type $rows
     * @return \framework\base\Model
     */
    public function limit($offset, $rows = 0) {
        if ($rows == 0) {
            $this->limit = " LIMIT 0,{$offset}";
        } else {
            $this->limit = " LIMIT {$offset},{$rows}";
        }
        return $this;
    }

    /**
     * 执行SQL语句
     * @param type $sql 可直接执行sql语句
     * @param type $param 执行sql时的条件参数
     * @return type
     */
    public function query($sql = NULL, $param = []) {
        $this->setTableFileds();

        // 执行query() sql语句
        if ($sql) {
            $this->sql = $sql;
            $stmt = $this->db->prepare($sql);
            $stmt->setFetchMode(DB::FETCH_INTO);
            if (!empty($param)) {
                foreach ($param as $key => $val) {
                    $stmt->bindParam(":{$key}", $val);
                    unset($key, $val);
                }
            }
            $stmt->execute();
            return $stmt->fecthAll(DB::FETCH_ASSOC);
        } else {
            $this->sql = "SELECT {$this->select} FROM `{$this->tableName}` {$this->where} {$this->groupBy} {$this->orderBy} {$this->limit}";
            // PDO预处理
            try {
                $stmt = $this->db->prepare($this->sql);
                $stmt->setFetchMode(DB::FETCH_ASSOC);
            } catch (\PDOException $exc) {
                echo $exc->getTraceAsString();
                exit;
            }
            
            // 参数绑定
            if (!empty($this->param)) {
                $this->bindParam($stmt);
            }
        }
        // Write log
        Log::save($this->sql);
        $stmt->execute();
        return $stmt;
    }

    /**
     * 保存数据
     * @return type 成功返回受影响的行数,失败返回FALSE
     */
    public function save() {
        $this->setTableFileds();
        // 插入/更新操作判断
        $this->_attributesOld === NULL && $this->isNew = TRUE;

        return $this->isNew ? $this->insert() : $this->update();
    }

    /**
     * 增加数据
     * @return type
     */
    public function insert() {
        // 生成插入的字段名称和数据值
        $name_values = ['name' => '', 'values' => ''];
        foreach ($this->_attributes as $key => $val) {
            $this->param[":{$key}"] = "{$val}";
            $name_values['name'] .= "`{$key}` ,";
            $name_values['values'] .= " :{$key} ,";
        }
        $name_values['name'] = rtrim($name_values['name'], ',');
        $name_values['values'] = rtrim($name_values['values'], ',');
        
        $this->sql = "INSERT INTO `{$this->tableName}` ({$name_values['name']}) VALUES ({$name_values['values']})";

        try {
            $stmt = $this->db->prepare($this->sql);
            // 绑定参数,注意 $stmt->bindParam() 方法是引用传值,使用完毕后变凉后unset($key, $val);否则变量的值只保存最后一个,导致绑定的数据全部都是最后的数据
            $this->bindParam($stmt);
            $result = $stmt->execute();
        } catch (\PDOException $exc) {
            exit($exc->getTraceAsString());
        }

        Log::save($this->sql);
        return $result;
    }
    
    /**
     * 更新数据
     * @return 返回受影响的行数
     */
    public function update() {
        $name_values = '';
        foreach ($this->_attributes as $key => $val) {
            if (array_key_exists($key, $this->_attributesOld) && $val !== $this->_attributesOld[$key]) {
                $this->param[":{$key}"] = "{$val}";
                $name_values .= " `{$key}`= :{$key} ,";
            }
        }
        // 若对象数据没有变更,则直接返回1(返回0不太好,在判断的时候,无法判定是执行成功了还是失败了,因为失败的话返回false)
        if (empty($name_values)) {
            return 1;
        }
        $name_values = rtrim($name_values, ' ,');
        $this->sql = "UPDATE `{$this->tableName}` SET  {$name_values} WHERE id = :id";
        try {
            $stmt = $this->db->prepare($this->sql);
            // 绑定参数,注意 $stmt->bindParam() 方法是引用传值,使用完毕后变凉后unset($key, $val);否则变量的值只保存最后一个,导致绑定的数据全部都是最后的数据
            $this->bindParam($stmt);
            $result = $stmt->execute();
        } catch (\PDOException $exc) {
            exit($exc->getTraceAsString());
        }

        return $result;
    }

    /**
     * 给映射数据表字段的属性赋值
     * @param type $attribute
     */
    public function setAttribute($attribute = NULL) {
        $this->setTableFileds();
        if ($attribute === NULL) {
            //给映射数据库字段的属性赋值
            foreach ($this as $key => $val) {
                if (in_array($val, $this->tableFileds)) {
                    $this->_attributes["{$key}"] = $val;
                    $this->param[":{$key}"] = $val;
                }
            }
        } else {
          
            foreach ($attribute as $key => $val) {
                if (is_array($val) && !empty($val)) {
//                    foreach () {
//                        
//                    }
                }
                if (in_array($key, $this->tableFileds)) {
                    $this->$key = $val;
                    $this->param[":{$key}"] = $val;
                }
            }
        }
    }

    /**
     * 获取最后插入的ID值
     * @return type int
     */
    public function getLastInsertId() {
        return DB::lastInsertId();
    }
    
    /**
     * 设置对象的字段属性，对数据表的字段映射到模型上
     */
    public function setTableFileds() {
        // 获得pdo句柄
        $this->getDB();
        // 若该对象已存在表字段的映射,则返回
        if ($this->tableFileds !== NULL) {
            return;
        }
        

        // 获取字段名称
        $results = $this->db->query("DESC {$this->tableName}");
        $this->tableFileds = [];
        foreach ($results as $name) {
            $this->tableFileds[] = $name['Field'];
        }
    }
    
    /**
     * 对SQL语句的参数进行绑定
     * @param type $stmt 引用PDO statement对象
     */
    public function bindParam(&$stmt) {
        foreach ($this->param as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v) {
                    $stmt->bindParam($k, $v);
                    unset($k, $v);
                }
            } else {
                $stmt->bindParam($key, $val);
            }
            // 由于stmt的bindParam 的参数是引用传值,所以在使用后要unset掉,否则会导致绑定的参数的值都为最后一次循环的数据
            unset($key, $val);
        }
    }
}
