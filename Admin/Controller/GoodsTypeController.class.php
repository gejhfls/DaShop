<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/1
 * Time: 上午10:38
 */
namespace Admin\Controller;
use Think\Controller;
class GoodsTypeController extends Controller {
    public function add(){
        if($_POST['type_name']){
            //print_r($_POST);exit;
            $model = D('GoodsType');
            //  var_dump($model);
            if($model->create()){
                if($model->add()){
                    $this->success("添加类型成功",'lst');exit;
                }else{
                    $this->error("添加类型失败");
                }
            }else{
                $this->error($model->getError());
            }
        }
        $this->display();
    }
    public function lst(){

        $model = D("GoodsType");
        $typedata = $model->field("luka_goods_type.id,type_name,count(b.id) as count")
            ->join("luka_attribute b on luka_goods_type.id=b.type_id",'LEFT')
            ->group("luka_goods_type.id")->select();
//         print_r($typedata);exit;
        $this->assign('typedata',$typedata);
        $this->display();
    }
}