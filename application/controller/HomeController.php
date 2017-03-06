<?php
namespace app\controller;

use framework\base\Controller;
use app\model\User;
use framework\library\Flash;

class HomeController extends Controller {
    
    /**
     * 首页
     */
    public function actionIndex() {
        $model = $this->findOne(7);
        $model->mobile = 13591791280;
        $model->save();
        $this->render('index', ['ac' => $model]);
    }

    public function actionView($id) {
    }

    /**
     * 登录
     */
    public function actionLogin() {
        $this->layout = 'login';
        $this->render('login');
    }

    /**
     * 注册
     */
    public function actionRegister() {
        $this->layout = 'login';
        $model = new User;
        if (IS_POST && $model->register()) {
            Flash::setFlash('success', '注册成功');
            $this->redirect('home');
        }
        $this->render('register');
    }

    public function findOne($id) {
        if (empty($id)) {
            throw new Exception("$id 不能为空");
        }
        return (new User)->fetch($id);
    }

}
