<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Flower as FlowerModel;

class Flower extends Controller
{

    public function flowerupdate()
    {
        $id=$_GET['id'];
        $data=input('post.');
        $f=FlowerModel::get($id);
        $f->flowerID=$data['flowerID'];
        $f->fname=$data['fname'];
        $f->myclass=$data['myclass'];
        $f->fclass=$data['fclass'];
        $f->fclass1=$data['fclass1'];
        $f->cailiao=$data['cailiao'];
        $f->baozhuang=$data['baozhuang'];
        $f->huayu=$data['huayu'];
        $f->shuoming=$data['shuoming'];
        $f->price=$data['price'];
        $f->yourprice=$data['yourprice'];
        $f->tejia=$data['tejia'];
        
        $pictures=request()->file('pictures');
        if(!empty($pictures)){
            $info=$pictures->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $f->pictures=$info->getSaveName();
        }
        $picturem=request()->file('picturem');
        if(!empty($picturem)){
            $info=$picturem->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $f->picturem=$info->getSaveName();
        }
        $pictureb=request()->file('pictureb');
        if(!empty($pictureb)){
            $info=$pictureb->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $f->pictureb=$info->getSaveName();
        }
        $pictured=request()->file('pictured');
        if(!empty($pictured)){
            $info=$pictured->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $f->pictured=$info->getSaveName();
        }
        $cailiaopicture=request()->file('cailiaopicture');
        if(!empty($cailiaopicture)){
            $info=$cailiaopicture->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $f->cailiaopicture=$info->getSaveName();
        }
        $bzpicture=request()->file('bzpicture');
        if(!empty($bzpicture)){
            $info=$bzpicture->validate(['ext'=>'jpg,png'])->move(ROOT_PATH.'public/static'.DS.'picture','');
            $f->bzpicture=$info->getSaveName();
        }
        $f->save();
        $this->redirect(url('administrator/adminshowflower'));
    }
    
    public function flowerdelete(){
        $id=$_GET['id'];
        $f=FlowerModel::get($id);
        $f->delete();
        $this->redirect(url('administrator/adminshowflower'));
    }
}