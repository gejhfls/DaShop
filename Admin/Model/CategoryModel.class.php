<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/3/24
 * Time: 上午9:50
 */
namespace Admin\Model;
use Think\Model\RelationModel;
//use Think\Model;
class CategoryModel extends RelationModel {

    // 定义字段列表
    protected $fields = array (
        'cat_id',
        'cat_name',
        'keywords',
        'cat_desc',
        'style',
        'show_in_nav',
        'is_show',
        'sort_order',
        'parent_id',
        'click_count',
        '_autoinc' => true,
        '_pk' => 'cat_id'
    );

    protected $pk='cat_id';
    protected $_validate=array(
        array('cat_name','require','栏目名不能为空'),
        array('parent_id','number','父级栏目设置非法'),
    );

    // 定义字段映射
//    protected $_map = array (
//        'cate_id' => 'id',
//        'cate_name' => 'name',
//        'cate_content' => 'content',
//        'cate_cid' => 'cid'
//    );

//     定义关联
    protected $_link = array (
        'Category' => array (
            'mapping_type' => self::BELONGS_TO,
            'mapping_name' => 'cate',
            'mapping_fields' => 'cat_name',
            'class_name' => 'Category',
            'parent_key' => 'parent_id'
        ),
        'Goods' => array (
            'mapping_type' => self::HAS_MANY,
            'mapping_name' => 'goods',
            'mapping_fields'=>'id,name,is_delete',
            'class_name' => 'Goods',
            'foreign_key' => 'cat_id'
        )
    );
}