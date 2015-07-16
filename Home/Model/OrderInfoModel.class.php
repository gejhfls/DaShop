<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/27
 * Time: 下午6:38
 */
namespace Home\Model;
use Think\Model;
class OrderInfoModel extends Model {
    protected $fields = array (
        'id',
        'order_sn',
        'user_id',
        'consignee',
        'address',
        'email',
        'tel',
        'mobile',
        'shipping_name',
        'pay_name',
        'order_status',
        'goods_amount',
        'order_desc',
        'session_id',
        '_autoinc' => true,
    );
    protected $pk='id';
    protected $_validate=array(
        array('consignee','require','收货人不能为空'),
        array('address','require','请填写正确地址'),
        array('mobile','require','手机号码不能为空'),

    );
}