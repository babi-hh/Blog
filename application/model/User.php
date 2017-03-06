<?php
namespace app\model;

use framework\base\Model;

class User extends Model {

    public function getInfo() {
//        $results = $this->select(['id', 'name', 'email'])->where(['id', '=', 1])->fetchAll();
        $results = $this->fetch();
        
        return $results;
    }

}
