<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use app\index\model\Customer as CustomerModel;

class Customer extends Controller
{

    public function customeradd()
    {
        $param = input('post.');
        if (empty($param)) {
            return $this->fetch();
        } else {
            $sname = $param['sname'];
            $sex = $param['sex'];
            $mobile = $param['mobile'];
            $zip = $param['zip'];
            $address = $param['address'];
            $customer = new CustomerModel();
            $customer->email = session('email');
            $customer->sname = $param['sname'];
            $customer->sex = $param['sex'];
            $customer->mobile = $param['mobile'];
            $customer->zip = $param['zip'];
            $customer->address = $param['address'];
            $customer->cdefault = 0;
            $customer->save();
            return redirect('order/order');
        }
    }

    public function customerdelete()
    {
        $custID = $_GET['custID'];
        $cus = CustomerModel::get($custID);
        $cus->delete();
        return redirect('order/order');
    }

    public function customerupdate()
    {
        $all = db('customer')->where('email', session('email'))->select();;
        foreach ($all as $a) {
            $cus = CustomerModel::get($a['custID']);
            $cus->cdefault = 0;
            $cus->save();
        }
        $custID = $_GET['custID'];
        $cus = CustomerModel::get($custID);
        $cus->cdefault = 1;
        $cus->save();
        return redirect('order/order');
    }
}