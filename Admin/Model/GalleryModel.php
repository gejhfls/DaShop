<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/5
 * Time: 下午3:58
 */
namespace Admin\Model;
use Think\Model;
class GalleryModel extends Model{
    protected $fields = array(
        'img_id',
        'goods_id',
        'img_url',
        'img_desc',
        'img_order',
        'thumb_url',
        '_autoinc' => true,
        '_pk' => 'img_id'

    );
    protected $pk     = 'img_id';

    protected $_validate = array(
        array('goods_id','require','商品序号不能为空'),
    );

    protected $_link =array(
        'Goods' => array (
            'mapping_type' => self::BELONGS_TO,
            'mapping_name' => 'goods',
            'mapping_fields'=>'id,name',
            'class_name' => 'Goods',
            'foreign_key' => 'goods_id'
        )
    );
}