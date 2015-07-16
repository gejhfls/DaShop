<?php
namespace Admin\Model;
use Think\Model;
class GoodsTypeModel extends Model{
    // 定义字段列表
    protected $fields = array (
        'id',
        'type_name',
        '_autoinc' => true,
        '_pk' => 'id'
    );
    protected $_validate=array(
        array('type_name','require','类型名称不能为空'),    
    );
}
?>