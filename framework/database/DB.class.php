<?php
namespace framework\database;

use framework\log\Log;
use \PDO;

class DB {
    
    // PDO的资源标识符
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
            $error_msg = "数据库链接错误,错误信息：{$exc->getMessage()}";
            Log::save("PDO Exception:{$error_msg}");
            APP_DEBUG && exit($error_msg);
        }
        self::$db = $pdo;
        return self::$db;
    }

    public static function lastInsertId() {
        return PDO::lastInsertId();
    }

}
