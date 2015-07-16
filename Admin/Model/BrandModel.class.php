<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/4
 * Time: 上午9:02
 */
namespace Admin\Model;
use Think\Model;

class BrandModel extends Model{
    //定义字段
    protected $fields=array(
        'brand_id',
        'brand_name',
        'brand_logo',
        'brand_desc',
        'site_url',
        'sort_order',
        'is_show',
        'click_count',
        '_autoinc' => true,
        '_pk' => 'brand_id'
    );

    protected $pk='brand_id';
    protected $_validate=array(
        array('brand_name','require','名称不能为空'),
        array('brand_logo','require','logo不能为空')
    );


}