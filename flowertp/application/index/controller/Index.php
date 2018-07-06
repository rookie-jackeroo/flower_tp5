<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{

    public function index()
    {  
            $param = input('get.');
            $fclasses = array('开业乔迁','道歉鲜花','家居鲜花','生子祝贺','友情鲜花','爱情鲜花','婚庆鲜花','回报老师','问候长辈','生日鲜花','祝福鲜花','探病慰问','丧葬哀思');
            $this->assign('fclasses', $fclasses);
            if (empty($param['sub'])) {
                $fname = "";
                $fclass = "";
                $min = 0;
                $max = 20000;
                $data = Db::table('flower')->order('yourprice desc')->paginate(8,false,['query'=>request()->param()]);      
            } else {
                $fname = trim($param['fname']);
                $fclass = $param['fclass'];
                $min = $param['minprice'];
                $max = $param['maxprice'];

                if (empty($fname) && empty($fclass)) {
                    $map['yourprice']  = array('between',array($min,$max));
                    $data = Db::table('flower')->where($map)->order('yourprice desc')->paginate(8,false,['query'=>request()->param()]);
                }
                if(empty($fname) && !empty($fclass)){
                    $map['fclass']  = array('eq',$fclass);
                    $map['yourprice']  = array('between',array($min,$max));
                    $data = Db::table('flower')->where($map)->order('yourprice desc')->paginate(8,false,['query'=>request()->param()]);
                }
                if (!empty($fname) && empty($fclass)){ 
                    $map['fname'] = array('like',"%$fname%");
                    $map['yourprice']  = array('between',array($min,$max));
                    $data = Db::table('flower')->where($map)->order('yourprice desc')->paginate(8,false,['query'=>request()->param()]);
                }
                if (!empty($fname) && !empty($fclass)){
                    $map['fname'] = array('like',"%$fname%");
                    $map['fclass']  = array('eq',$fclass);
                    $map['yourprice']  = array('between',array($min,$max));
                    $data = Db::table('flower')->where($map)->order('yourprice desc')->paginate(8,false,['query'=>request()->param()]);
                }
            } 
            $this->assign('fname_', $fname);
            $this->assign('fclass_', $fclass);
            $this->assign('min_', $min);
            $this->assign('max_', $max);
            
            $this->assign('flower', $data);
            $page = $data->render();
            $this->assign('page', $page);
            return $this->fetch();
        
    }
    

}


