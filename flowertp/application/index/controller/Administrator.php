<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Peisong;
use app\index\model\Myorder;

class Administrator extends Controller
{

    public function adminlogin()
    {
        if(empty(session('username'))){
            return $this->fetch();
        }else {
            $this->redirect(url('administrator/adminindex'));
        }
    }

    public function adminindex()
    {
        return $this->fetch();
    }

    public function dologin()
    {
        $param = input('post.');
        $username = $param['username'];
        $has = db('admin')->where('username', $username)->find();
        if (empty($has)) {
            $this->error($username . "管理员不存在");
        }
        if ($has['password'] != md5($param['password'])) {
            $this->error('密码错误');
        } else {
            session('username', $username);
            $this->redirect(url('administrator/adminindex'));
        }
    }

    public function logout()
    {
        if (session('username')) {
            session('username', null);
            $this->success('已退出管理员模式！', 'index/index');
        }
    }
    
    public function adminshowflower()
    {
        if (empty(session('username'))){
            $this->error('请先登录管理员账号!', 'administrator/adminlogin');
        }else {
            $data = Db::table('flower')->order('yourprice desc')->paginate(8);
            $page = $data->render();
            $this->assign('flower', $data);
            $this->assign('page', $page);
            return $this->fetch();
        }
    }

    public function adminorderlist()
    {
        if (empty(session('username'))){
            $this->error('请先登录管理员账号!', 'administrator/adminlogin');
        }else {
            $peisongs = db('peisong')->where('ddzt', '已付款')->order('orderID')->paginate(2);
            $page = $peisongs->render();
            $this->assign('peisongs', $peisongs);
            $this->assign('page', $page);
            
            $IDs = array();
            foreach ($peisongs as $p) {
                array_push($IDs, $p['orderID']);
            }
            $orders = db('myorder')->where('orderID', 'in', $IDs)->order('orderID')->select();
            
            $orderlists = array();
            foreach ($orders as $order) {
                $data = Db::table('showshoplist')->where('orderID', $order['orderID'])->order('orderID')->select();
                $shoplistitems = array();
                foreach ($data as $shoplist) {
                    array_push($shoplistitems, $shoplist);
                }
                array_push($orderlists, $shoplistitems);
            }
            $this->assign('orderlists', $orderlists);
            return $this->fetch();
        }
    }
    
    public function adminflowerlist(){
        $id=$_GET['id'];
        $data = Db::table('flower')->where('flowerID',$id)->find();
        $this->assign('flower', $data);
        return $this->fetch();
    }
}