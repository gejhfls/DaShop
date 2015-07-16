<?php
namespace Home\Model;
use Think\Model\RelationModel;

class GoodsModel extends RelationModel  {
	protected $fields = array (
			'id',
			'goods_name',
            'goods_sn',
            'goods_type',
			'cat_id',
			'click_count',
			'brand_id',
            'shop_price',
            'promote_price',
        'promote_start_date',
        'promote_end_date',
        'is_promote',
        'is_shipping',
        'add_time',
        'sort_order',
        'new_last_time',
        'goods_number',
        'goods_img1',
        'goods_img2',
        'goods_thumb1',
        'goods_thumb2',
        'keywords',
        'goods_brief',
        'goods_desc',
        'is_new',
        'is_hot',
        'is_bewt',
        'is_sale',
        'is_delete',

			'_autoinc' => true,
			'_pk' => 'id'
	);
    protected $pk='id';
    protected $_validate=array(
        array('goods_name','require','商品名不能为空'),
        array('goods_type','require','商品类型不能为空'),
        array('goods_desc','require','请添加商品描述'),
        array('goods_number','number','商品库存必须为数字'),
        array('shop_price','number','商品价格必须为数字'),
        array('promote_price','number','商品促销价必须为数字')
    );
    // 定义关联
    protected $_link = array (
        'Category' => array (
            'mapping_type' => self::BELONGS_TO,
            'mapping_name' => 'cate',
            'mapping_fields' => 'cat_name,cat_desc',
            'class_name' => 'Category',
            'foreign_key' => 'cat_id'
        ),
        'Brand' => array (
            'mapping_type' => self::BELONGS_TO,
            'mapping_name' => 'brand',
            'mapping_fields' => 'brand_name,brand_desc',
            'class_name' => 'Brand',
            'foreign_key' => 'brand_id'
        ),
        'Attr'=> array(
            'mapping_type' => self::HAS_MANY,
            'mapping_name' => 'attr',
            'mapping_fields' => 'attr_id,attr_value,attr_price',
            'class_name' => 'GoodsAttr',
            'foreign_key' => 'goods_id'
        )
    );

}