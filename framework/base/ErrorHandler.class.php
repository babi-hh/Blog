<?php

/**
 * 此类用作错误显示的处理程序
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 7, 2017 4:05:40 PM
 */   
namespace framework\base;

class ErrorHandler {

    // 回调跟踪
    private static $_trace;
    // 错误类别
    private static $_level;
    // 错误信息
    private static $_message;
    // 文件
    private static $_file;
    // 行数
    private static $_line;

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
        
        // 获得调用的堆栈信息
        $backtrace = debug_backtrace();
        $trace = '';
        // 去除当前方法的调用堆栈信息
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
                self::$_message = ERROR_INCO . "<b> [警告] </b> "
                    . self::$_message . " 在文件 <i>"
                    . self::$_file . "</i> 的 "
                    . self::$_line . " <br />{$trace}";
                break;
            case E_USER_NOTICE;
            case E_NOTICE:
                self::$_message .= "<b> [提示]</b> "
                    . self::$_message . " 在文件 <i>"
                    . self::$_file . "</i> 的 "
                    . self::$_line . "  <br />{$trace}";
                break;
            default:
                self::$_message .= "未知错误类型: [" . self::$_level . "] "
                    . self::$_message . " 在文件 <i>"
                    . self::$_file . "</i> 的 "
                    . self::$_line . " 行  <br />{$trace}";
                break;
        }
        echo (self::$_message);

        // 不执行PHP内部处理程序错误,return FALSE 的话会执行PHP内部处理程序
        return TRUE;
    }

    /**
     * 错误提示显示方法
     */
    public static function error() {
        // 获得调用的堆栈信息
        $backtrace = debug_backtrace();
        $trace = '';
        array_shift($backtrace);
        array_shift($backtrace);
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
                self::$_message .= "<b> [警告] </b> "
                    . self::$_message . " 在文件 <i>"
                    . self::$_file . "</i> 的 "
                    . self::$_line . " <br />{$trace}";
                break;
            case E_USER_NOTICE;
            case E_NOTICE:
                self::$_message .= "<b> [提示]</b> "
                    . self::$_message . " 在文件 <i>"
                    . self::$_file . "</i> 的 "
                    . self::$_line . "  <br />{$trace}";
                break;
            default:
                self::$_message .= "未知错误类型: [" . self::$_level . "] "
                    . self::$_message . " 在文件 <i>"
                    . self::$_file . "</i> 的 "
                    . self::$_line . " 行  <br />{$trace}";
                break;
        }
        echo (self::$_message);
    }

}
