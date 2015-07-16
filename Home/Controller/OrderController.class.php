<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/27
 * Time: 下午6:36
 */
namespace Home\Controller;
use Think\Controller;
class OrderController extends BaseController{
    public function checkout(){


        //判断用户是否登录
        if(!empty($_SESSION['user_id'])){
            //没有登录则，跳转到登录页面。
            $paytype=0;
           $this->redirect("orderform");
        }else{
            $paytype=1;
            $address= D('Address');
            $addData=$address->where("user_id = '{$_SESSION['user_id']}'")->select();

        }

//        $this->assign('address',$address[0]);
        //正式下单。
        $this->display();
    }

    public function orderform(){
        $address= D('address');

        $cart = D("Cart");
        if(isset($_SESSION['user_id'])){
            $cartdata = $cart->where("user_id='{$_SESSION['user_id']}'")->select();
            $addData = $address->where("user_id='{$_SESSION['user_id']}'")->select();
            $this->assign('add',$addData);
        }else {
            $session_id = session_id();
            $cartdata = $cart->where("session_id='$session_id'")->select();
            $this->assign('Message',"please write the info of consignee");
        }

        if(!$cartdata){
            $this->error("购物车里面没有商品，无法结算",__MODULE__."/Index/home");
        }

        $this->assign('cart',$cartdata);
        $this->display();
    }
    //填写收货人信息的一个方法
    public function writeaddress(){
        if($this->isPost()){
            //完成收货人信息入库
            $model = M();
            $user_id = $_SESSION['user_id'];
            $consignee = $_POST['consignee'];
            $address = $_POST['address'];
            $email=$_POST['email'];
            $tel=$_POST['tel'];
            $mobile = $_POST['mobile'];
            $sql="insert into address values(null,$user_id,'$consignee','$address','$email','$tel','$mobile')";
            if($model->Execute($sql)){
                $this->redirect("checkout");
            }
        }
        $this->display();
    }
    public function done(){
        // 收集提交的信息
        $type= I('type');
        $session_id = session_id();
        $_POST['session_id']=$session_id;
        $user_id = $_SESSION['user_id'];
        $_POST['user_id']=$user_id;
        $_POST['order_status']=1;
        $_POST['order_sn']=date("Ymd")*10000+rand(1,10000);

        //完成入库
        $lock = $this->startlock('orderlock');
        if($lock){
            //执行下订单
//            $sql="insert into order_info values(null,'$order_sn','$user_id','$consignee','$address','$email','$tel','$mobile','$shipping_name','$pay_name','$goods_amount')";
            $model = D('OrderInfo');
//            $model->Execute($sql);
            if($model->create()) {
                $model->add();
                $id = $model->getLastInsID();////返回自动增长的id
                if ($id > 0) {
                    //执行成功；
                    //到order_goods表里面添加数据
                    $cart = D("Cart");
                    if($type=='nologin') {

                        $data['session_id']=session_id();
                        $cartdata = $cart->where("session_id='$session_id'")->select();
                    }else{
                        $cartdata = $cart->where("user_id='$user_id'")->select();
                    }
                    $data['goods_attr_price']=0;
                    foreach ($cartdata as $v) {
                        $data['goods_attr_price']+=$v['goods_attr_price'];
                        $orderGoods = D('OrderGoods');
                        $data['order_id']=$id;
                        $data['goods_name']= $v['goods_name'];
                        $data['goods_id'] = $v['goods_id'];
                        $data['shop_price'] = $v['shop_price'];
                        $data['goods_number'] = $v['goods_number'];
                        $data['goods_attr'] = $v['goods_attr'];
                        $data['goods_attr_id'] = $v['goods_attr_id'];
                        $orderGoods->add($data);
                    }

                }

                //清空购物车
                // $cart = D("Cart");

                $cart->where("session_id='$session_id'")->delete();
                $cart->where("user_id='{$user_id}'")->delete();
                if(isset($_SESSION['user_id'])) {
                    $this->success('success', __MODULE__ . "/Member");
                }else{
                    $this->success('success', __MODULE__ . "/Order/order");
                }
            }else{
                $this->error($model->getError());
            }
            $this->endlock('lock');//执行完成订单后解锁。
        }else{
            //下订单失败
            $this->error('服务器忙，呆会再下订单。');
        }
//        $this->redirect("complete",array('order_sn'=>$order_sn));
    }
    public function complete(){
        $this->display();
    }

    public function order(){
        $user = $_SESSION['user_id'];
        if(empty($_SESSION['user_id'])) {
            $session_id = session_id();
            $orderdata = D('OrderInfo')->where("session_id='$session_id'")->select();
        }else{
            $orderdata = D('OrderInfo')->where("user_id=$user")->select();
        }

        $this->assign('orders',$orderdata);
        $this->display();
    }
}