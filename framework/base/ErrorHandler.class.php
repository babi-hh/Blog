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
        ob_start();
        debug_print_backtrace();
        self::$_trace = ob_get_clean();

        // 错误信息由Error方法来处理
        self::error();
        // 不执行PHP内部处理程序错误,return FALSE 的话会执行PHP内部处理程序
        return TRUE;
    }

    /**
     * 错误提示显示方法
     */
    public static function error($error = NULL) {
        $e = [];
        if ($error) {
            /* if (!is_array($error)) {
              $trace = debug_backtrace();

              $e['file'] = $trace[0]['file'];
              $e['line'] = $trace[0]['line'];
              $e['message'] = $error;
              ob_start();
              debug_print_backtrace();
              $e['trace'] = ob_get_clean();
              } else {
              $e = $error;
              } */
            $e = $error;
        } else {
            // 获得调用的堆栈信息
            $e['message'] = self::$_message;
            $e['file'] = self::$_file;
            $e['line'] = self::$_line;
            $e['trace'] = self::$_trace;
        }
        // 去除调用堆栈里的路径信息
        $root_path = stristr(getcwd(), '\web', TRUE);
        $e['trace'] = str_replace($root_path, '..', $e['trace']);
        // 包含异常页面的模版
        $error_template = FRAMEWORK_PATH . 'exception/error.tpl.php';
        require $error_template;
        exit;
    }

}
