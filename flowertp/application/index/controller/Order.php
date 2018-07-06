<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Myorder;
use app\index\model\Tbddzt;
use app\index\model\Shoplist;
use app\index\model\Flower;
use app\index\model\Cart;
use app\index\model\Vcart;
use app\index\model\Showorder;
use app\index\model\Customer;
use app\index\model\Peisong;

class Order extends Controller
{

    public function order()
    {
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        } else {
            $data1 = db('customer')->where('email', session('email'))->select();
            $this->assign('customer', $data1);
            
            $cartIDs = input('post.cartID/a');
            if (!empty($cartIDs)){
               session('cartIDs',$cartIDs); 
            }
            $data2 =Vcart::where('cartID', 'in', session('cartIDs'))->select();
            $this->assign('result', $data2);
            
            return $this->fetch();
        }
    }

    public function addorder()
    {
        $param = input('post.');
        
        $order = new Myorder();
        $order->email = session('email');
        $order->custID = $param['custID'];
        $order->shifu = input('post.shifu/f');
        $order->inputtime = date("Y-m-d H:i:s");
        $order->peisongday = $param['peisongday'];
        $order->peisongtime = $param['peisongtime'];
        $order->peisong = $param['peisong'];
        $order->psyq = $param['psyq'];
        $order->liuyan = $param['liuyan'];
        $order->shuming = $param['shuming'];
        $order->fkfs = $param['fkfs'];
        $order->fp = $param['fp'];
        $order->fpaddress = $param['fpaddress'];
        $order->zip = $param['zip'];
        $order->fpsname = $param['fpsname'];
        $order->ddzt = '未付款';
        $order->cltime=$order->inputtime;
        $order->save();
        
        $ddzt = new Tbddzt();
        $ddzt->orderID = $order['orderID'];
        $ddzt->cltime = $order['inputtime'];
        $ddzt->ddzt = $order['ddzt'];
        $ddzt->save();
        
        $sum=0;
        $cartIDs = input('post.cartID/a');
        $carts = Cart::where('cartID', 'in', $cartIDs)->select();
        foreach ($carts as $cart) {
            $shoplist = new Shoplist();
            $shoplist->email = session('email');
            $shoplist->orderID = $order['orderID'];
            $shoplist->flowerID = $cart['flowerID'];
            $shoplist->num = $cart['num'];
            $shoplist->save();
            
            $flower = Flower::get($cart['flowerID']);
            $flower->SelledNum = $flower->SelledNum + $cart['num'];
            $flower->save();
          
            $vcarts=Db::table('vcart')->where('cartID', $cart['cartID'])->select();
            foreach ($vcarts as $vcart){
                $sum=$vcart['num']*$vcart['yourprice']+$sum;
            }
            
            $car = Cart::get($cart['cartID']);
            $car->delete();
        }
        
        $order->shifu=$sum;
        $order->save();
    
        return redirect('order/showorder');
    }

    public function showorder()
    {
        if (empty(session('email'))) {
            $this->error('请先登录!', 'login/login');
        } else {
            $orders = Showorder::where('email', session('email'))->paginate(3);
            $page = $orders->render();
            $this->assign('showorder', $orders);
            $this->assign('page', $page);
            
            $orderlists = array();
            foreach ($orders as $order) {
                $data = Db::table('showshoplist')->where('orderID', $order->orderID)
                    ->order('orderID')
                    ->select();
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
    
    public function orderdelete(){
        $orderID=$_GET['orderID'];
        $order=Myorder::get($orderID);
        $order->delete();
        return redirect('order/showorder');
    }
    
    public function pay(){
        $orderID=$_GET['id'];
        $order=Myorder::get($orderID);
        $order->ddzt='已付款';
        $order->save();
        return redirect('order/showorder');
    }
    
    public function orderupdate(){
        $orderID=$_GET['id'];
        if (!empty(input('post.adminupdate'))){
            $order=Myorder::get($orderID);
            $order->ddzt='已发货';
            $order->cltime=date("Y-m-d H:i:s");
            $order->kddh=input('post.kddh');
            $order->save();
            return redirect('administrator/adminorderlist');
        }else {
            $order=Myorder::get($orderID);
            $order->ddzt='未评价';
            $order->cltime=date("Y-m-d H:i:s");
            $order->save();
            return redirect('order/showorder');
        }  
    }
}