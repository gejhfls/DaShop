<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/24
 * Time: 下午5:28
 */
namespace Home\Model;
use Think\Model\RelationModel;
class FavoriteModel extends RelationModel{
    protected $fields= array(
        'id',
        'user_id',
        'goods_id',
        '_autoinc' => true,
        '_pk' => 'id'
);
    protected $pk='id';

    protected $_link = array (
        'Good' => array (
            'mapping_type' => self::HAS_ONE,
            'mapping_name' => 'good',
            'mapping_fields' => 'id,goods_name,shop_price,goods_thumb1,goods_brief',
            'class_name' => 'Goods',
            'foreign_key'=>'id',
            'mapping_key'=>'goods_id'

        ),

    );
}