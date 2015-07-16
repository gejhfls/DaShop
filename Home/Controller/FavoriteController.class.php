<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/24
 * Time: 下午5:57
 */
namespace Home\Controller;
use Think\Controller;
use Home\Model\GoodsModel;
use Home\Model\FavoriteModel;
class FavoriteController extends Controller{
    public function index(){
        $res=$this->showFav();
//        var_dump($result);exit;
        $this->assign('result',$res[1]);
        $this->display('favorite');
    }

    public function add(){
        if(isset($_POST['goods_id'])&&isset($_SESSION['user_id'])){
            $_POST['user_id']=$_SESSION['user_id'];
            $fav = D('Favorite');
            if($fav->where("user_id = '{$_SESSION['user_id']}' and goods_id = '{$_POST['goods_id']}'")->select()){
                echo 'already added';
                exit;
            }
            $fav->create();
            if($fav->add()) {
                echo 'success';
                exit;
            }
        }

        echo 'error';
    }

    function showFav()
    {
        $Fav = D('Favorite');
        $_SESSION['user_id'] = $_SESSION['user_id'];
        if (isset($_SESSION['user_id'])) {
//            $favData= $Fav->join("Luka_goods on Luka_goods.id = goods_id")->where("user_id = '{$_SESSION['user_id']}'")->select();
            $favData = $Fav->relation('good')->where("user_id= '{$_SESSION['user_id']}'")->select();
           return $result = array(1, $favData);
        }
           return $result = array(0, '');

    }

    function del(){
        if(isset($_POST['goods_id'])&&isset($_SESSION['user_id'])){
            $fav = D('Favorite');
            if($fav->where("user_id = '{$_SESSION['user_id']}' and goods_id = '{$_POST['goods_id']}'")->delete()) {
                echo 'success';
                exit;
            }
        }

        echo 'error';
    }
}