<?php

/**
 * 处理未捕获的异常
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 7, 2017 9:33:54 AM
 * @todo 把错误类和异常类分开
 */   
namespace framework\base;
use framework\base\ErrorHandler;

/**
 * @param type $status  错误状态代码 如 404
 * @param type $message 错误消息
 * @param type $code    错误级别
 * @param \Exception $previous 上一个异常对象
 */
class ErrorException extends \Exception {

    // 404错误
    const NOT_FOUND = 404;
    // 异常错误
    const ERROR = 500;
    
    // 状态码 [404,500...]
    public static $statusCode = 500;
    // 模版文件
    private static $template;
    
    public function __construct($message = "", $code = 0, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    /**
     * 当直接echo 一个对象的时候,会调用此魔术方法,来输出想要的字符
     * @return type String
     */
    public function __toString() {
        self::NotFound;
        $errors = debug_backtrace();
        $errorHtml = '错误!<br/>';
        foreach ($errors as $key => $item) {
            $errorHtml .= "#{$key} In file {$item['file']} : {$item['line']} line, function : {$item['function']} in the class {$item['class']} <br/>";
        }
        return $errorHtml;
    }

    /**
     * 自定义捕获异常的方法,并渲染相应的模版
     */
    public static function ExceptionHandler($exception) {
        $e['message'] = self::$statusCode . ' ' . $exception->getMessage();
        $e['file'] = $exception->getFile();
        $e['line'] = $exception->getLine();
        ob_start();
        debug_print_backtrace();
        $e['trace'] = strip_tags(ob_get_clean());

        // 调用处理错误方法去渲染错误信息
        ErrorHandler::error($e);
        return false;
    }

}
