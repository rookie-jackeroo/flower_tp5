<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Cart extends Controller
{

    public function cart()
    {
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        }
        $data = Db::table('vcart')->where('email', session('email'))->select();
        $this->assign('result', $data);
        return $this->fetch();
    }

    public function addcart()
    {
        $param = input('get.');
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        }
        $data = Db::table('cart')->where('email', session('email'))
            ->where('flowerID', $param['flowerID'])
            ->find();
//         $data = Db::table('cart')->where("email='" . session('email') . "' and flowerID='" . $param['flowerID'] . "'")->find();
        if (empty($data)) {
            $result = Db::execute("insert into cart(cartID,email,flowerID,num) values(null,'" . session('email') . "','" . $param['flowerID'] . "',1)");
        } else {
            $result = Db::execute("update cart set num=num+1 where email='" . session('email') . "' and flowerID='" . $param['flowerID'] . "'");
        }
        $this->redirect(url('cart/cart'));
    }
    
    public function clearcart(){
        $result=Db::execute("delete from cart where email='" .session('email'). "'");
        $this->redirect(url('cart/cart'));
    }
    
    public function deletecart(){
        $param = input('get.');
        $result=Db::execute("delete from cart where cartID=" .$param['cartID']);
        $this->redirect(url('cart/cart'));  
    }
    
    public function updatecart(){
        $param = input('get.');
        $result=Db::execute("update cart set num=".$param['num']." where cartID=" .$param['cartID']);
        $this->redirect(url('cart/cart'));
    }
}