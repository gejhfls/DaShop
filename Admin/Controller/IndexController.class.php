<?php
namespace Admin\Controller;
use Admin\Controller\BaseController;
class IndexController extends BaseController {
//    public function index(){
//        $this->display();
//    }
    public function top(){
        $this->display();
    }
    public function index(){
        //取出配置文件里面的内容
        $menus = C('menus');
        //取出按钮对应的权限
        $menus_priv=C('menus_priv');
        $lang = C('lang');
        //取出当前登录用户的id
        $user_id=$_SESSION['user_id'];
        $user_id=1;
        //获取当前登录用户的权限
        //$action_list = $_SESSION['acton_list'];
        $action_list = array('addgoods','addcategory');
        //思路：把每个按钮的权限找出来，和 $action_list进行比较，判断按钮的权限是否在$action_list数组中。
        //对按钮进行遍历
        if($user_id!=1){
            foreach($menus as $k=>$v){
                //找出按钮对应的权限：
                //判断顶级按钮是否显示
                if(is_array($menus_priv[$k])){
                    $tmp = array_intersect($menus_priv[$k],$action_list);
                    //var_dump($tmp);
                    if(empty($tmp)){
                        //如果没有交集，说明该顶级按钮没有权限显示。应该从menus数组中删除。
                        unset($menus[$k]);
                        continue;//本次循环结束，继续下次循环。
                    }
                }
                //判断次级按钮是否显示。
                foreach($v  as $k1=>$v1){
                    //找出次级按钮对应的权限。
                    if(!in_array($menus_priv[$k1],$action_list)){
                        unset($menus[$k][$k1]);
                    }
                }
            }
        }

        foreach($menus as $key => $val){
            foreach($val as $val1) {
                $menuslink[$key] = $val1;
                break;
            }
        }
        $this->assign('menuslink',$menuslink);
        $this->assign('menus',$menus);
        $this->assign('lang',$lang);
        $this->display();
    }
    public function main(){
        $this->display();
    }
    public function drag(){
        $this->display();
    }
    public function welcome(){
        $this->display();
    }

}