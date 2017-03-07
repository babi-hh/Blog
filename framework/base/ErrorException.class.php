<?php

/**
 * 处理未捕获的异常
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 7, 2017 9:33:54 AM
 * @todo 把错误类和异常类分开
 */   
namespace framework\base;
use framework\base\ErrorHandler;

class ErrorException extends \Exception {
    // 回调跟踪
    private static $_trace;
    // 错误类别
    private static $_level;
    // 错误信息
    private static $_message = '<h1>￣へ￣</h1>';
    // 文件
    private static $_file;
    // 行数
    private static $_line;

    public function __toString() {
        $errors = debug_backtrace();
        $errorHtml = '错误!<br/>';
        foreach ($errors as $key => $item) {
            $file = str_replace(getcwd(), ' ', $item['file']);
            $errorHtml .= "#{$key} In file {$item['file']} : {$item['line']} line, function : {$item['function']} in the class {$item['class']} <br/>";
        }
        return $errorHtml;
    }

    /**
     * 自定义捕获异常的方法
     */
    public static function ExceptionHandler($exception) {
        self::$_message .= $exception->getMessage();
        self::$_file = $exception->getFile();
        self::$_trace = $exception->getTrace();
        self::$_level = $exception->getCode();
        self::$_line = $exception->getLine();
        self::error();
    }

    
    

    /**
     * 自定义错误处理函数
     * @param type $level   错误级别
     * @param type $message 错误信息
     * @param type $file    错误所在的文件
     * @param type $line    错误所在文件的行数
     * @return boolean      TURE表示不将错误信息交给PHP内部处理
     */
    public static function errorHandler($level, $message, $file, $line) {
        self::$_level = $level;
        self::$_message = $message;
        self::$_file = $file;
        self::$_line = $line;
        // 不执行PHP内部处理程序错误,return FALSE 的话会执行PHP内部处理程序
        self::error();
        return TRUE;
    }

    /**
     * 错误提示显示方法
     */
    public static function error() {
        // 获得调用的堆栈信息
        $backtrace = debug_backtrace();
        $trace = '';
//        array_shift($backtrace);
//        array_shift($backtrace);
        foreach ($backtrace as $key => $val) {
            $trace .= "#{$key} <i>{$val['file']}</i>( {$val['line']} ) : {$val['class']} {$val['type']} {$val['function']} <br />";
        }
        switch (self::$_level) {
            case E_USER_ERROR:
            case E_ERROR:
                self::$_message .= "<b>[致命错误 ] " . self::$_message . " </b> <br />"
                    . "  错误位置 <i>" . self::$_file . "</i> 的 " . self::$_line . " 行. "
                    . "&nbsp;&nbsp;&nbsp;{PHP " . PHP_VERSION . " (" . PHP_OS . ")} <br />"
                    . " <b>Trance... </b> <br />"
                    . $trace;
                exit(self::$_message);
                break;
            case E_USER_WARNING;
            case E_WARNING:
                self::$_message .= "<b> [警告] </b> [" . self::$_level . "] " . self::$_message . " 在文件 <i>" . self::$_file . "</i> 的 " . self::$_line . " <br />{$trace}";
                break;
            case E_USER_NOTICE;
            case E_NOTICE:
                self::$_message .= "<b> [提示]</b> [" . self::$_level . "] " . self::$_message . " 在文件 <i>" . self::$_file . "</i> 的 " . self::$_line . "  <br />{$trace}";
                break;
            default:
                self::$_message .= "未知错误类型: [" . self::$_level . "] " . self::$_message . " 在文件 <i>" . self::$_file . "</i> 的 " . self::$_line . " 行  <br />{$trace}";
                break;
        }
        echo (self::$_message);
    }

}
