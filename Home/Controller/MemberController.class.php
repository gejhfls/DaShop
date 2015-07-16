<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/20
 * Time: 下午8:47
 */
namespace Home\Controller;
use Home\Model\CartModel;
use Think\Controller;
use Home\Model\GoodsModel;
use Home\Model\FavoriteModel;
class MemberController extends Controller{
     function index(){
         //渲染头部
         if(empty($_SESSION['user_id'])){
             $this->error('未登陆，请先登陆');
             exit;
         }
         $cate= M('Category')->select();
         load('@.tree');
         $cateArray=array();
         foreach($cate as $key=>$val){
             if($val['parent_id']==0) {
                 $val['child'] = getTree($cate, $val['cat_id']);
                 $cateArray[] = $val;
             }
         }
//        var_dump($cateArray);exit;
         $this->assign("category",$cateArray);
         $this->assign("welcome","hello world");
         $this->assign('logout',1);
         $heading = $this->fetch("Public/heading");
         $this->assign("heading",$heading);
         //渲染购物车
         $this->assign("carts",$this->showitem());


        $this->display("member");
     }

    function getCart(){
        $cart = D('Cart');
        $user =$_SESSION['user_id'];
        return $cart->where("user_id=$user")->select();
    }

    private  function showitem(){
        //条件：session_id
        $cart = D('Cart');
        $_SESSION['user_id'];
        if(isset($_SESSION['user_id'])){
            $cartdata = $cart->where("user_id='{$_SESSION['user_id']}'")->select();
        }else {
            $session_id = session_id();
            $cartdata = $cart->where("session_id='$session_id'")->select();
        }

//        $goodsmodel = D("Goods");
//        $list=array();
//        //取出商品的缩略图
//        foreach($cartdata as  $v){
//            $v['goods_thumb']=$goodsmodel->where("id={$v['goods_id']}")->getField("goods_thumb1");
//            $list[]=$v;
//        }
        return $cartdata;
    }

    public function setInfo(){
        if(empty($_SESSION['user_id']))exit;
        $user = D('Member');
        $data = $user->where("id={$_SESSION['user_id']}")->find();
//        $data['birthday']=date('Y-m-d',$data['birthday']);
        $this->assign('userData',$data);
        $this->display();
    }

    public function saveInfo(){
        if(empty($_SESSION['user_id']))exit;
        $user = D('Member');
         $_validate=array(
            array('email','email','邮箱格式不正确!'),
        );
//        $_POST['birthday']=strtotime($_POST['birthday']);
        if($user->validate($_validate)->field('email,phone,birthday,sex')->create()){
            if($user->where("id={$_SESSION['user_id']}")->save()){
                $this->success('Info Saved');
            }else{
                $this->error($user->getError());
            }
        }else{
            $this->error($user->getError());
        }
    }
}