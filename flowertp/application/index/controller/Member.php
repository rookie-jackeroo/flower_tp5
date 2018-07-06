<?php
namespace app\index\controller;

use think\Controller;

class Member extends Controller
{
    public function member(){
        return $this->fetch();
    }
}