<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/2
 * Time: 上午8:38
 */

namespace Admin\Controller;
use Think\Controller;

class AttributeController extends Controller{
    public function add(){
        if(IS_POST){
            $attrmodel  = D("Attribute");
            if($attrmodel->create()){
                if($attrmodel->add()){
                    $this->success("添加属性成功$id",'lst/id/'.$_POST['type_id']);exit;
                }else{
                    $this->error('添加属性失败');
                }
            }else{
                $this->error($attrmodel->getError());
            }
        }
        //取出商品的类型
        $model = D("GoodsType");
        $attrdata  = $model->select();
        $this->assign('attrdata',$attrdata);
        $this->display();
    }
    public function lst(){
        //接收传递的所属类型的id
        $id = (int)$_GET['id'];
        // echo $id; exit;
        $attrmodel  = D("Attribute");
        $attrdata = $attrmodel->_showattr($id);
        $model = D("GoodsType");
        $typedata  = $model->select();

        $this->assign('typedata',$typedata);
        $this->assign('attrdata',$attrdata);
        $this->display();
    }
    public function showattr(){
        $id = (int)$_GET['id'];
        $attrmodel  = D("Attribute");
        $attrdata = $attrmodel->_showattr($id);
        $this->assign('attrdata',$attrdata);
        $this->display();
    }
}