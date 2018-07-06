<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Register extends Controller
{

    public function register()
    {
        return $this->fetch();
    }

    public function doregister()
    {
        $param = input('post.');
        $data = db('member')->where('email', $param['email'])->find();
        $result = Db::execute("insert into member(email,password,jifen,ye) values('" . $param['email'] . "','" . md5($param['pw']) . "',0,0)");
        $this->redirect(url('login/login'));
    }

    public function checkemail()
    {
        $param = input('post.');
        $data = Db::table('member')->where('email', $param['email'])->find();
        $result = ! empty($data);
        return $result ? '用户名已存在！' : '';
    }
}