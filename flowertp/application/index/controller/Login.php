<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Login extends Controller
{

    public function login()
    {
        if (session('email')) {
            $this->error(session('email') . '已登录', 'index/index');
        }
        return $this->fetch();
    }

    public function dologin()
    {
        $param = input('post.');
        $e = $param['email'] . $param['suffix'];
        $has = db('member')->where('email', $e)->find();
        if (empty($has)) {
            $this->error($e . "用户不存在");
        }
        if ($has['password'] != md5($param['pw'])) {
            $this->error('密码错误');
        } else {
            session('email', $has['email']);
            $this->redirect(url('index/index'));
        }
    }

    public function logout()
    {
        if (session('email')) {
            session('email', null);
            $this->success('您已登出！', 'index/index');
        }
    }
}