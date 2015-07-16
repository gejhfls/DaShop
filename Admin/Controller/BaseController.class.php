<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/21
 * Time: 下午9:48
 */
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller{
       public function   _initialize(){
           if(isset($_SESSION['ad_login']) && $_SESSION['ad_login'] =='yes') {
               //继续执行
           } else {
               //没有session登陆标记
               //判断是否存在合法的cookie值，利用模型验证是否合法
               $model_admin  = D('Admin');
               if( $model_admin->checkByCookie() ) {
                   //有合法的cookie
                   $_SESSION['ad_login'] = 'yes';
               } else {
                   //没有合法的cookie
                   $this->error("请先登陆",__MODULE__."/Login/login");
               }
           }
       }
}