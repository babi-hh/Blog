<?php
namespace app\controller;

use framework\base\Controller;
use app\model\User;

class HomeController extends Controller {
    public function actionIndex() {
        $model = new User;
        $model = $model->fetchAll();

        $model = $this->render('index', ['ac' => $model]);
    }

    public function actionView($id) {
        D($id);
    }
    
    /**
     * 登录
     */
    public function actionLogin() {
        $this->renderPartial('login');
    }

    /**
     * 注册
     */
    public function actionRegister() {
        
    }

}
