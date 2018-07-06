<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Showflower extends Controller
{

    public function showflower()
    {
        $data = Db::table('flower')->order('yourprice desc')->paginate(4, false, [
            'query' => request()->param()
        ]);
        $page = $data->render();
        $this->assign('flower', $data);
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function clsflower()
    {
        $pname = $_GET['pname'];
        if (! empty($_GET['pvalue'])) {
            $pvalue = $_GET['pvalue'];
        }
        if (! empty($_GET['pvalue2'])) {
            $pvalue1 = $_GET['pvalue1'];
            $pvalue2 = $_GET['pvalue2'];
        }
        
        if ($pname == 'fclass') {
            $data = Db::table('flower')->where('fclass', $pvalue)
                ->order('yourprice desc')
                ->paginate(4, false, [
                'query' => request()->param()
            ]);
        }
        if ($pname == 'fclass1') {
            $data = Db::table('flower')->where('fclass1', $pvalue)
                ->order('yourprice desc')
                ->paginate(4, false, [
                'query' => request()->param()
            ]);
        }
        if ($pname == 'tejia') {
            $data = Db::table('flower')->where('tejia', $pvalue)
                ->order('yourprice desc')
                ->paginate(4, false, [
                'query' => request()->param()
            ]);
        }
        if ($pname == 'yourprice') {
            $data = Db::table('flower')->where('yourprice', '>', $pvalue1)
                ->where('yourprice', '<', $pvalue2)
                ->order('yourprice desc')
                ->paginate(4, false, [
                'query' => request()->param()
            ]);
        }
        $this->assign('flower', $data);
        $page = $data->render();
        $this->assign('page', $page);
        return $this->fetch();
    }

    public function flowerdetail()
    {
        $flowerID = $_GET['flowerID'];
        $data1 = Db::table('flower')->where('flowerID', $flowerID)->find();
        $this->assign('flower', $data1);
        $data2 = Db::table('shoplist')->where('flowerID', $flowerID)->where('pjstar','neq','')->select();
        $this->assign('shoplists', $data2);
        return $this->fetch();
    }
}