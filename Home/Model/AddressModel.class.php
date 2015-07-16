<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/25
 * Time: 下午8:05
 */
namespace Home\Model;
use Think\Model;
class AddressModel extends Model{
    protected $pk='id';
    protected $_validate=array(
        array('consignee','require','收货人不能为空'),
        array('address','require','请填写正确地址'),
        array('mobile','require','手机号码不能为空'),

    );
}