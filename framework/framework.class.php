<?php

namespace framework;

use framework\base\ErrorException;
use framework\base\ErrorHandler;

/**
 * 主进程类
 * @author Seldoon <Sedloon@sina.cn>
 * @todo 定义一个开发者模式,如define('APP_DEBUG',Ture);开发阶段方便调试
 */
class Framework {

    // PHP会在退出之前进来溜达一圈
    final static function shutdown() {

        // 输出脚本运行的时间
        $time = (microtime(1) - SCRIPT_START_TIME) * 1000;
        print ('<br />' . $time . ' ms');
    }

    /**
     * 开始
     */
    public static function run() {
        self::init();
        self::debug();
        self::autoload();
        self::setHandler();
        self::dispatch();
    }

    /**
     * 初始化
     */
    public static function init() {
        header('Content-Type:text/html;Charset=utf-8');
        header('X-Powered-By:Seldoon');
        
        // 脚本开始执行的精确时间
        define('SCRIPT_START_TIME', microtime(true));
        define('REQUEST_TIME', $_SERVER['REQUEST_TIME']);
        defined('APP_DEBUG') || define('APP_DEBUG', FALSE);
        // 资源文件(css js...)的相对路径
        define('ASSETS_PATH', stristr(parse_url($_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'])['path'], '/'));
        // 定义目录分隔符,[windows=>\,linux=>/]
        define('DS', DIRECTORY_SEPARATOR);
        define('ROOT', getcwd() . DS . '..' . DS);
        define('APP_PATH', ROOT . 'application' . DS);
        define('FRAMEWORK_PATH', ROOT . 'framework' . DS);
        define('WEB_PATH', ROOT . 'web' . DS);
        define('APP_NAMESPACE', 'app\controller\\');

        // 应用目录的路径定义
        define('CONFIG_PATH', APP_PATH . 'config' . DS);
        define('CONTROLLER_PATH', APP_PATH . 'controller' . DS);
        define('MODEL_PATH', APP_PATH . 'model' . DS);
        define('VIEW_PATH', APP_PATH . 'view' . DS);
        
        // 框架核心目录的定义
        define('LIBRARY_PATH', FRAMEWORK_PATH . 'library' . DS);
        define('BASE_PATH', FRAMEWORK_PATH . 'base' . DS);
        define('DB_PATH', FRAMEWORK_PATH . 'database' . DS);
        define('UPLOAD_PATH', WEB_PATH . 'uploads' . DS);

        // 定义默认控制器及操作
//        define('PLATFORM', isset($_REQUEST['p']) ? $_REQUEST['p'] : 'home');// 前后台分离
        define('CONTROLLER', isset($_REQUEST['c']) && $_REQUEST['c'] ? ucfirst($_REQUEST['c']) : 'Home');
        define('ACTION', isset($_REQUEST['a']) && $_REQUEST['a'] ? ucfirst($_REQUEST['a']) : 'Index');
        
        // 管理员
//        define('IS_ADMIN', isset($_SESSION['']));
        // 匿名用户
//        define('IS_GUEST', !isset($_SESSION['']));
        define('IS_POST', isset($_POST['submit']) || !empty($_POST) ? TRUE : FALSE);
        define('BR', '<br />');

        // 加载核心类文件
        require LIBRARY_PATH . 'Object.class.php';
        require BASE_PATH . 'Controller.class.php';
        require BASE_PATH . 'Model.class.php';
        require DB_PATH . 'DB.class.php';
        require FRAMEWORK_PATH . 'log/Log.class.php';

        // 加载配置文件
        $GLOBALS['config'] = include CONFIG_PATH . 'config.php';

        // Session 
        session_start();
    }
    
    /**
     * 调试模式的配置
     */
    public static function debug() {
        if (defined('APP_DEBUG') && APP_DEBUG) {
            // 不显示错误,有set_error_handler()来输出错误,一般是致命错误php不会交给自定义方法,而是自己处理
            ini_set('display_errors', 'Off');
        }
    }

    /**
     * 注册自动加载类load,用来实现类的自动加载
     */
    public static function autoload() {
        spl_autoload_register([__CLASS__, 'load']);
    }

    /**
     * 自定义处理程序
     */
    public static function setHandler() {
        register_shutdown_function([__CLASS__, 'shutdown']);
        // 自定义捕获异常处理
        set_exception_handler(['framework\base\ErrorException', 'ExceptionHandler']);
        // 自定义错误处理方法
        set_error_handler(['framework\base\ErrorHandler', 'ErrorHandler']);
    }

    /**
     * 注册的自动加载方法,当实例一个没有加载的类时,会隐式调用此方法.
     * PHP解析引擎会在抛出异常之前,再做最后一次争扎ﾟｰﾟ.
     * @param type $className Description
     */
    public static function load($className) {
        $class = $className;
        $className = self::namespaceConversionToPath($className);
        require_once "{$className}.php";
    }

    /**
     * 路由信息分发,实例相应控制器并调用其方法
     */
    public static function dispatch() {
        $controllerName = self::getControllerNamespace();
        $actionName = 'action' . ACTION;
        $controller = new $controllerName;

        if ($controller->beforeAction() === FALSE) {
            throw new \ErrorException(CONTROLLER . "Controller error in beforAction :" . CONTROLLER . "Controller->{$actionName}()", 500);
        }
        $controller->$actionName();
        $controller->afterAction();
    }

    /**
     * 获取控制器的命名空间
     * @return type String
     */
    private static function getControllerNamespace() {
        $controllerNamespace = APP_NAMESPACE . CONTROLLER . 'Controller';
        return $controllerNamespace;
    }

    /**
     * 把命名空间地址转换为文件的路径,在写框架里类的时候命名空间一定要与framework下的路径一致
     * @param type $name
     * @return type
     */
    public static function namespaceConversionToPath($name) {
        $tmp = strstr($name, '\\', true);
        // 获取类的名字
        $className = explode('\\', $name);
        $className = end($className);
        
        if (in_array($tmp, ['app', 'framework'])) {
            // 判断是否为应用目录
            if ($tmp == 'app') {
                if (substr($className, -10) == 'Controller') {
                    $className = CONTROLLER_PATH . $className;
                } else {
                    $className = MODEL_PATH . $className;
                }
                // 判断是否为框架目录
            } else if ($tmp == 'framework') {
                $className = ROOT . str_replace('\\', '/', $name) . '.class';
            }
        } else {
            
        }

        return $className;
    }
}
