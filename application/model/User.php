<?php
namespace app\model;

use framework\base\Model;

class User extends Model {

    const STATUS_INVALID = 0;
    const STATUS_ACTIVE = 1;

    // 用来确认两次密码的一致
    public $passwordConfirm;

    
    /**
     * 用户注册
     * @todo 设置一个通用的错误提示机制
     * @return Boolean 
     */
    public function register() {
        $this->setAttribute($_POST['user']);
        if ($this->password !== $_POST['user']['passwordConfirm']) {
            # todo..setError('密码不一致');
        }
        $this->password = self::generateHashPassword($this->password);
        $this->created = REQUEST_TIME;
        $this->status = self::STATUS_ACTIVE;
        if ($this->save()) {
            $_SESSION['uid'] = $this->id;
            return TRUE;
        }
        return FALSE;
    }

    
    
}
