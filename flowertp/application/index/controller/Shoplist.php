<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Shoplist as ShoplistModel;
use app\index\model\Myorder;

class Shoplist extends Controller
{

    public function shoplistupdate()
    {
        $orderID = input('get.orderID/d');
        $datas = Db::table('showshoplist')->where('orderID', $orderID)->select();
        $this->assign('results', $datas);
        return $this->fetch('shoplist/pingjia');
    }

    public function update()
    {
        $param=input('post.');
        $orderID = input('post.orderID');
        $datas = Db::table('shoplist')->where('orderID', $orderID)->select();
        foreach ($datas as $s) {
            $shoplist = ShoplistModel::get($s['SLID']);
            $pjstar = 'pjstar' . $s['SLID'];
            $pjcontent = 'pjcontent' . $s['SLID'];
            $shoplist->pjstar = $param[$pjstar];
            $shoplist->pjstar = $param[$pjstar];
            $shoplist->pjcontent = $param[$pjcontent];
            $shoplist->pjtime = date("Y-m-d H:i:s");
            $shoplist->email=session('email');
            $shoplist->save();
        }
        $order=Myorder::get($orderID);
        $order->ddzt='已评价';
        $order->save();
        return redirect('order/showorder');
    }
}