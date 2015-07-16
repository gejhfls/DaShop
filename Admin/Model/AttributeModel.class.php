<?php
namespace Admin\Model;
use Think\Model;

class AttributeModel extends Model{
    protected $_validate=array(
        array('attr_name','require','属性名不能为空'),
        array('type_id','number','请选择商品类型'),
        array('attr_type','require','要选则属性的类型'),
        array('attr_input_type','require','要选择属性输入方式')
    );
//    protected $pk='id';
    //显示属性的一个函数；
    //参数type_id是所属类型的id
    public function _showattr($type_id){
        $where="type_id=$type_id";
        if($type_id==0)$where='"1"';
            $data = $this->field("luka_attribute.*,b.type_name")->join("luka_goods_type b on b.id=luka_attribute.type_id")->where($where)->select();
            return $data;
    }
}
?>