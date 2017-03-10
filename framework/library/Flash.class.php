<?php
namespace framework\library;
use framework\base\ErrorException;

/**
 * Flash 消息类,通常用作表单提交之后的信息提示
 * @author Seldoon <Sedloon@sina.cn>
 */   
final class Flash {

    const FLASHES_KEY = '_flashes';

    private static $flashes = NULL;

    private function __construct() {
        
    }
    /**
     * 增加flashe 消息信息
     * @param type $status 自定义信息,sucess,error
     * @param type $message
     * @throws Exception
     */
    public static function setFlash($status, $message) {
        if (empty(trim($message))) {
            throw new ErrorException('无法设置一个空的消息.');
        }
        self::initFlashes();
        // 因为在初始化的时候self::$flashes 引用的是$_SESSION[self::FLASHES_KEY],相当于$_SESSION[self::FLASHES_KEY][] = $message
        self::$flashes[$status] = $message;
    }
    
    /**
     * 获取flash里信息
     * @param type $status []
     * @return type
     */
    public static function getFlash($status = '') {
        self::initFlashes();
        if (empty($status)) {
            $copy = self::$flashes;
        } else {
            $copy = isset(self::$flashes[$status]) ? self::$flashes[$status] : '';
        }
        // 清空$_SESSION里的flash关联数组
        self::$flashes = [];
        return $copy;
    }

    /**
     * 检查是否有flash消息
     * @return type Boolean
     */
    public static function hasFlash() {
        self::initFlashes();
        return count(self::$flashes) > 0;
    }

    /**
     * 初始化会话(SESSION)里的flashes,主要是用来创建SESSION里的flashes关联数组
     * @return type
     */
    public static function initFlashes() {
        if (self::$flashes !== NULL) {
            return;
        }
        if (!isset($_SESSION[self::FLASHES_KEY])) {
            $_SESSION[self::FLASHES_KEY] = [];
        }
        self::$flashes = &$_SESSION[self::FLASHES_KEY];
    }

}
