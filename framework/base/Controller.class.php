<?php
namespace framework\base;

use framework\library\Object;
use framework\library\Flash;
/**
 * 控制器的基类
 */
class Controller extends Object {

    // 网站标题
    public $title = NULL;
    // 布局文件名称,不含有php扩展名
    public $layout = NULL;

    public function __construct() {
        parent::__construct();
        $this->init();
    }

    /**
     * 初始化一些数据,暂时没有用到
     */
    public function init() {
        
    }
    public function beforeAction() {
    }

    public function afterAction() {
    }

    /**
     * 重定向 
     * @param type $url 路径 'action' || '/controller/action'
     * @param type $message 提示信息
     * @param type $wait 等待倒计时
     * @todo $wait 设置一个弹出层
     */
    public function redirect($url, $message = '', $wait = 0) {
        $url = $this->parseUrl($url);

        if ($wait) {
            header("Refresh:{$wait};url={$url}");
        } else {
            header("Location:{$url}");
        }
        // header 跳转后要加exit
        exit;
    }

    /**
     * 渲染模版文件
     * @param type $tpl 模版文件名称
     *      参数类型1 controller/view  或者 index  当前控制器下的视图文件
     *      参数类型2 /OtherController/view 表示其他控制器下的视图文件
     * @param type $data 传递过来的数据
     */
    public function render($tpl, $data = []) {
        if (strpos($tpl, '/') === 0) {
            $template = ltrim($tpl, '/');
        } else {
            // 类型1 controller/view
            if (strpos($tpl, '/') !== FALSE) {
                $template = $tpl;
            } else { // 当前控制器下的view
                $class = self::getClass($this);
                // HomeController=>Home
                $class = substr($class, 0, strlen($class) - 10);
                $class = strtolower($class);
            }
            $template = $class . '/' . $tpl;
        }
        
        //转换 对象在调用$this->display(['model' => $var])方法时传递过来的数组的键名，便于在views里获取此名称即 $model
        extract($data);
        // 提示消息
        $flash = Flash::hasFlash() ? Flash::getFlash() : '';
        $view_file = VIEW_PATH . "{$template}.php";
        $this->checkFileExists($view_file);

        ob_start();
        include $view_file;
        $content = ob_get_clean();
        
        // 布局文件默认为 main.php
        $main = $this->layout ? : 'main';
        $layout = VIEW_PATH . "layout/{$main}.php";
        $this->checkFileExists($layout);
        // 加载布局文件
        include $layout;
    }

    /**
     * 渲染一个不带布局文件的视图
     * @param type $tpl 模板文件
     *      参数类型1 controller/view  或者 index  当前控制器下的视图文件
     *      参数类型2 /OtherController/view 表示其他控制器下的视图文件
     * @param type $data 传递给模板的参数
     */
    public function renderPartial($tpl, $data = []) {
        $class = self::getClass($this);
        // 若传递的$tpl首字符含有 '/',则表示渲染一个其他控制器下的视图
        if (strpos($tpl, '/') === 0) {
            $template = $tpl;
        } else {
            if (strpos($tpl, '/') !== FALSE) {
                $template = strtolower($tpl);
            } else {
                // controller=>Home
                $class = substr($class, 0, strlen($class) - 10);
                $class = strtolower($class);
                $template = $class . "/{$tpl}";
            }
        }

        $template = VIEW_PATH . "{$template}.php";
        $this->checkFileExists($template);

        //转换 对象在调用$this->display(['model' => $var])方法时传递过来的数组的键名，便于在views里获取此名称即 $model
        extract($data);
        $flash = Flash::hasFlash() ? Flash::getFlash() : '';
        include $template;
    }

    /**
     * 生成URL地址链接
     * @param string $url 跳转的地址
     * @param type $param 传递的参数关联数组
     * @return string 生成的URL
     */
    protected function url($url, $param = []) {
        return $this->parseUrl($url, $param);
    }
    
    /**
     * 解析URL 把简洁的URL解析成服务可执行的URL
     * @param type $url 如 'action' || '/controller/action'
     * @param type $param 传递的参数为关联数组
     * @return type String
     */
    public function parseUrl($url, $param = []) {
        $url = trim($url);
        if ($url == 'home') {
            return "?c=home&a=index";
        }
        $arg = '';
        if (!empty($param)) {
            $arg = '&' . http_build_query($param);
        }
        
        // 当前控制器下的操作
        if (strpos($url, '/') === FALSE) {
            $class = $this->getClass($this);
            $class = strtolower(substr($class, 0, strlen($class) - 10));
            $url = "c={$class}&a={$url}";
            // 其他控制器的跳转
        } else {
            $uri = explode('/', $url);
            $url = "c={$uri[1]}&a={$uri[2]}";
        }

        return "?{$url}{$arg}";
    }

    /**
     * 检查要包含的文件是否存在,不存在则会抛出一个异常
     * @param type $filename 文件的路径
     * @throws \Exception
     */
    public function checkFileExists($filename) {
        if (!file_exists($filename)) {
            throw new \framework\exception\NotFoundException("文件：{$filename} 不存在!");
        }
    }
    
    
}
