<?php
namespace framework\database;
use \PDO;

class DB {

    protected static $db = FALSE;
    protected $sql;
    const FETCH_ASSOC = PDO::FETCH_ASSOC;
    const FETCH_INTO = PDO::FETCH_INTO;

    /**
     * 获取PDO链接
     * @param type $config
     * @return type
     */
    public static function getDB($config = NULL) {
        try {
            if (self::$db !== FALSE) {
                return self::$db;
            }
            
            $config || $config = $GLOBALS['config']['db'];

            $pdo = new PDO($config['dsn'], $config['username'], $config['password']);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->query("SET NAMES {$config['charset']}");
        } catch (\PDOException $exc) {
            exit("数据库链接错误,错误信息：{$exc->getMessage()}");
        }
        self::$db = $pdo;
        return self::$db;
    }

    /**
     * 执行的 sql 语句日志
     * @param type $sql
     */
    public static function log($sql) {
        $sql = "[" . date('Y-m-d H:i:s') . "] {$sql} " . PHP_EOL;
        file_put_contents(DB_PATH . 'PDO.log', $sql, FILE_APPEND);
    }
    public static function lastInsertId() {
        return PDO::lastInsertId();
    }

}
