<?php
namespace Home\Model;
use Think\Model;
class CartModel extends Model{
    protected $fields = array (
        'id',
        'goods_name',
        'goods_id',
        'shop_price',
        'user_id',
        'goods_number',
        'goods_attr',
        'goods_attr_price',
        'goods_attr_id',
        'session_id',
        'goods_thumb',
        '_autoinc' => true,
        '_pk' => 'id'
    );
}
?>