<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/5/5
 * Time: 上午8:52
 */
namespace Admin\Controller;
use Think\Controller;
use Home\Model\OrderInfoModel;
use Think\Think;

class OrderController extends BaseController{
    function lst(){
        $order= D('OrderInfo');
        $count = $order->count ();
        $page = new \Think\Page( $count, 10);
        $show = $page->show ();
        $orderdata= $order->limit ( $page->firstRow . ',' . $page->listRows )->select();
        $this->assign('order',$orderdata);
        $this->assign('show',$show);
        $this->display('lst');
    }
}