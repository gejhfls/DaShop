<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/5
 * Time: 上午9:24
 */

namespace Admin\Model;
use Think\Model;

class GoodsAttrModel extends Model{
    protected $fields = array(
        'id',
        'goods_id',
        'attr_id',
        'attr_value',
        'attr_price'
    );

    protected $_valied = array(
       array('goods_id',"require",'商品号获取失败，属性无法录入'),
        array('attr_id','require','属性号获取失败，属性无法录入'),
        array('attr_value','require','属性值不能为空'),
        array('attr_price','number','请输入正确的属性价格')
    );
}