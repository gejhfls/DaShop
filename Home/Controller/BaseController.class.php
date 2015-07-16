<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/21
 * Time: 下午9:48
 */
namespace Home\Controller;
use Think\Controller;
class BaseController extends Controller{
    //开始加锁的一个函数
    public  static $lockFp=array();
    public function startlock($filename){
        self::$lockFp[$filename]=$fp=fopen('./Public/Lock/'.$filename.'.php','r');
        $lock = false;
        $try=10;
        if(!$lock){
            do{
                $lock = flock($fp,LOCK_EX );//如果锁定成功返回真，如果锁定失败返回假
                // var_dump($lock);exit;
                if(!$lock){
                    usleep(50000);//延迟0.05秒
                }
            }while(!$lock && $try-->0);
        }
        return $lock;
    }
    //开始解锁
    public function endlock($filename){
        $fp = self::$lockFp[$filename];
        @flock($fp,LOCK_UN);
        @fclose($fp);
    }
}