<?php
namespace framework\library;


class Object {
    public function __construct() {
    }

    /**
     * 获取当前类的名称
     * @param type $this
     * @param Boolean $nameapace 为真则获取带命名空间的类名称
     * @return type
     */
    public static function getClass($this, $nameapace = false) {
        $class = get_class($this);
        if ($nameapace) {
            return $class;
        }
        if (strpos($class, '\\') !== FALSE) {
            $class = explode('\\', $class);
            $class = end($class);
        }
        return $class;
    }

    /**
     * 实例当前类
     * @return \static 当前对象的实例
     */
    public static function instantiate() {
        return new static;
    }

    /**
     * 获取函数调用的堆栈信息,从当前方法往前的调用次序
     * @return type
     */
    public static function getTrance() {
        $e = new \Exception();
        return $e->getTraceAsString();
    }
    /**
     * 字符串加密方法,返回加密后的字符.若加密失败则返回FALSE
     * @param type $password 待加密的字符串
     * @return String Or FALSE
     */
    public static function generateHashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * 密码校验, 返回TRUE 或FALSE
     * @param type $stringPassword 明文字符串
     * @param type $hashPassword 加密后的字符串
     * @return type Boolean
     */
    public static function verifyPassword($stringPassword, $hashPassword) {
        return password_verify($stringPassword, $hashPassword);
    }

}
