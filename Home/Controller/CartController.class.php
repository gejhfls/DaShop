<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/21
 * Time: 下午8:52
 */
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller {
    public function index(){

        //渲染购物车
        $this->assign("carts",$this->showitem());


        $this->display("index");
    }
    public function add(){
//        print_r($_POST);exit;

        $lan=$this->getlan();
        $goods = D('Goods');
        $goods_id = $_POST['goods_id'];
        $goods_name= $_POST['goods_name'];
        $goods_thumb = I('goods_thumb');

        if(!isset($_SESSION['user_id']))$user_id=-1;
        else
        $user_id= (int)$_SESSION['user_id'];

        $session_id=session_id();
        $shop_price=$goods->where("id=$goods_id")->getField('shop_price');
        $goods_number=$_POST['goods_number'];
        $goods_attr='';
        $goods_attr_price=0;//多个属性加起来
        $goods_attr_id='';
        //颜色:黑色[0.00]
        $attr = $_POST['attr'];
        foreach($attr as $k=>$v){
            $v= explode(' ',$v)[0];
            $attrprice =$this->getprice($goods_id,$v);
            $goods_attr_price+=$attrprice;
//            $goods_attr.=$lan[$k].':'.$v.'['.$attrprice.'],';
            $goods_attr.=$lan[$k].':'.$v.',';
            $goods_attr_id.=$k.',';//构建属性的id,多个id用逗号隔开。

        }
        $goods_attr=trim($goods_attr,',');
        $goods_attr_id=trim($goods_attr_id,',');

        if($this->additem($goods_id,$session_id,$goods_attr,$user_id,$goods_name,$shop_price,$goods_number,$goods_attr_price,$goods_attr_id,$goods_thumb)){
//            $this->success("添加购物车成功",__URL__.'/showitem');exit;
            echo "success";
        }else{
//            $this->error("添加购物车失败");
//            echo "error";
        }


    }
    //用于加入到购物车的一个函数：
    public function additem($goods_id,$session_id,$goods_attr,$user_id,$goods_name,$shop_price,$goods_number,$goods_attr_price,$goods_attr_id,$goods_thumb){
        //在商品加入购物车之前要判断一下该商品购物车里面是否存在，如果存在则数量加1，如果不存在则添加。
        $cartmodel = D('Cart');
        $data['goods_id']=$goods_id;
        $data['goods_number']=$goods_number;
        $data['session_id']=$session_id;
        $data['goods_attr']=$goods_attr;
        $data['goods_attr_id']=$goods_attr_id;
        $data['goods_attr_price']=$goods_attr_price;
        $data['user_id']=$user_id;
        $data['shop_price']=$shop_price;
        $data['goods_name']=$goods_name;
        $data['goods_thumb']=$goods_thumb;
        if($this->hasitem($goods_id,$session_id,$goods_attr,$user_id)){
            //存在，
            if($user_id==-1)
                $result=$cartmodel->where("goods_id='$goods_id' and user_id='$user_id' and session_id='$session_id' and goods_attr='$goods_attr'")->setInc('goods_number',$goods_number);
            else
                $result=$cartmodel->where("goods_id='$goods_id' and user_id='$user_id' and goods_attr='$goods_attr'")->setInc('goods_number',$goods_number);

        }else{
            //不存在

            $result=$cartmodel->add($data);
//            $sql="insert into cart (goods_id,user_id,session_id,goods_name,shop_price,goods_number,goods_attr,goods_attr_price,goods_attr_id) values($goods_id,$user_id,'$session_id','$goods_name',$shop_price,$goods_number,'$goods_attr','$goods_attr_price','$goods_attr_id')";
        }
        //执行sql语句
        if($result){
            return true;
        }
        echo $cartmodel->getError();
        return false;

    }
    //判断购物车里面有没有同一件商品
    public function hasitem($goods_id,$session_id,$goods_attr,$user_id){
        $cart = D("Cart");
        if($user_id==-1)
            $cartdata = $cart->where("goods_id=$goods_id and session_id='$session_id' and goods_attr='$goods_attr'")->select();
        else
            $cartdata = $cart->where("goods_id=$goods_id and user_id='$user_id' and goods_attr='$goods_attr'")->select();

        if($cartdata){
            return true;
        }else{
            return false;
        }
    }
    //展示购物车商品的一个函数
//    public function showitem(){
//        //条件：session_id
//        $cart = D('Cart');
//        $session_id = session_id();
//        $cartdata = $cart->where("session_id='$session_id'")->select();
////        $goodsmodel = D("Goods");
////        $list=array();
////        //取出商品的缩略图
////        foreach($cartdata as  $v){
////            $v['goods_thumb']=$goodsmodel->where("id={$v['goods_id']}")->getField("goods_thumb");
////            $list[]=$v;
////        }
//        $this->assign('cartdata',$cartdata);
//        $this->display();
//    }

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

        return $cartdata;
    }
    //删除购物车的函数：
    public function delitem(){
        if(isset($_POST['id'])) {
            $id = $_POST['id'];
            $cartmodel = D('Cart');
            if ($cartmodel->where("user_id='{$_SESSION['user_id']}'")->delete($id)) {
//            $this->redirect("showitem");
                echo "success";
            } else {
//            $this->error("删除购物车失败");
                echo "error";
            }
        }
    }

    public function changeNum(){
        if(isset($_POST['id'])) {
            $id = $_POST['id'];
            $num = $_POST['num'];
            $cartmodel = D('Cart');
            $data['goods_number'] = $num;
            if ($cartmodel->where("id='$id'")->save($data)) {
//            $this->redirect("showitem");
                echo "success";
            } else {
//            $this->error("删除购物车失败");
                echo "error";
            }
        }
    }


    //清空购物车的函数
    public function clearitem(){
        $cartmodel = D("Cart");
        $session_id = session_id();
        $cartdata = $cartmodel->where("session_id='$session_id'")->select();
        if($cartdata){
            if($cartmodel->where("session_id='$session_id'")->delete()){
                $this->success('已经成功清空购物车',__MODULE__.'/Index/index');
            }
        }else{
            $this->redirect('Index/index');
        }

    }
    //获取属性的价格，根据goods_id和属性的名称
    public function getprice($goods_id,$v){
        $goods_attr=D('GoodsAttr');
        $attr_price = $goods_attr->where("goods_id=$goods_id and  attr_value='$v'")->getField('attr_price');
        return $attr_price;
    }
    public function getlan(){
        $attr = D('Attribute');
        $data = $attr->select();
        $list=array();
        foreach($data as  $v){
            $list[$v['id']]=$v['attr_name'];
        }
        return $list;
    }
    //修改购物车的函数
    public function update(){
        $cart_id = $_GET['cart_id'];
        //主要修改商品的数量+1
        $sql="update cart set goods_number=goods_number+1 where id=$cart_id";
        $model = M();
        if($model->Execute($sql)!==false){
            echo 'ok';
        }
    }

    public function getSum(){
        $items=self::showitem();
        if(empty($items)){echo 0;return;}
        $sum= 0;
        foreach($items as $val){
            $sum+=$val['shop_price']*$val['goods_number'];
            $sum+=$val['goods_attr_price']*$val['goods_number'];
        }
        echo $sum;
//        return $sum;
    }

}