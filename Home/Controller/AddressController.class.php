<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/25
 * Time: 下午8:05
 */
namespace Home\Controller;
use Think\Controller;
class AddressController extends Controller{
    function index(){
        $this->display('add');
    }
    function addAction(){
        if(isset($_POST['consignee'])){
            $_POST['user_id']= $_SESSION['user_id']? $_SESSION['user_id']:-1;
            $address= D("Address");
            if($address->create()){
                if($address->add()) {
                        $this->success('录入成功', 'lst');
                    } else {
                        $this->error('录入失败', 'lst');

                    }
            }else{
                $this->error($address->getError());
            }

        }
    }

    function lst(){
        if(empty($_SESSION['user_id'])){
            $this->error('未登陆，请先登陆');
            exit;
        }
        $add=D('Address');
        $addData=$add->where("user_id={$_SESSION['user_id']}")->select();
        $this->assign("addData",$addData);
        $this->display('lst');

    }

    function admin(){
        if(empty($_SESSION['user_id'])){
            $this->error('未登陆，请先登陆');
            exit;
        }
        $id= I('id');
        $add=D('Address');
        $addData=$add->where("user_id={$_SESSION['user_id']} and id='$id'")->find();
        if(empty($addData)){
            $this->error('请选择正确地址进行编辑');exit;
        }
        $this->assign("add",$addData);
        $this->display('admin');
    }

    function addSave()
    {
        $id=I('aid');
        $address = D("Address");
        if ($address->create()) {
            if ($address->where("id='$id'")->save()) {
                $this->success('录入成功', 'lst');
            } else {
                $this->error('录入失败', 'lst');

            }
        }else{
            $this->error($address->getError(), 'lst');
        }
    }
}