<?php

/**
 * 用来写入各种日志,默认写入文件
 * @author Seldoon <Sedloon@sina.cn>
 * Created: Mar 9, 2017 4:50:06 PM
 */

namespace framework\log;

class Log {

    public static $messageType = 3;
    public static $destination = '/error.log';
    public static $message;

    public function __construct() {
    }

    /**
     * 写入日志
     */
    public static function save($message, $message_type = NULL, $destination = NULL, $extra_headers = '') {
        $log_dir = FRAMEWORK_PATH . 'log/log/' . date('Ymd');
        !is_dir($log_dir) && mkdir($log_dir, 0777, true);
        is_null($message_type) && $message_type = self::$messageType;
        is_null($destination) && $destination = $log_dir . self::$destination;
        /**
         * error_log() 函数的用法
         * @param String $message 消息的内容
         * @param String $destination 目标 由参数$message_type决定
         * @param Int $message_type 发送消息$message的方式 
         * [0=>发送到PHP的系统日志, 1=>发送到$destination设置的邮件地址, 2=>不再是一个选项,
         *  3=>发送到$destination的文件里(追加的方式), 4=>直接发送到SAPI的日志处理程序中]
         * $extra_headers 额外的头。当 message_type 设置为 1 的时候使用。 该信息类型使用了 mail() 的同一个内置函数。
         * 
         */
        if (is_array($message)) {
            $arr = $message;
            $message = '';
            foreach ($arr as $key => $val) {
                $message .= "$key => {$val}" . PHP_EOL;
            }
        }
        error_log($message . PHP_EOL, $message_type, $destination, $extra_headers);
    }

}
