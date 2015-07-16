<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/27
 * Time: 下午6:38
 */
namespace Home\Model;
use Think\Model;
class OrderGoodsModel extends Model {
    protected $fields = array (
        'id',
        'order_id',
        'goods_id',
        'goods_name',
        'shop_price',
        'goods_number',
        'goods_attr',
        'goods_attr_id',
        'goods_attr_price',
        '_autoinc' => true,
    );
    protected $pk='id';
}