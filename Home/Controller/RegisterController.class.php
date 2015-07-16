<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/20
 * Time: 下午9:09
 */
namespace Home\Controller;
use Think\Controller;
class RegisterController extends Controller{
    public function  index(){
        $this->register();
    }

    public function register(){
        if(isset($_POST['user_name'])){
            $code=$_POST['code'];
            if(!$this->check_verify($code,'')){
                $this->error("验证码错误！");
            }
            $member = D('Member');
            $_POST['reg_time']=strtotime("now");
            $_POST['is_validated']=1;
            if($member->create()){
                //接收密码给md5加密
                $member->password=md5($_POST['password']);
                $url = __MODULE__.'/Index/home';
                if($_POST["forward"]=="order")$url = __MODULE__.'/Order/orderform';
                if($member->add()){
                    $_SESSION['user_name']=$_POST['user_name'];
                    $_SESSION['user_id']=$member->getLastInsID();
                    $this->success("注册会员成功",$url);exit;

                }else{
                    $this->error("注册会员失败");
                }
            }else{
                $this->error($member->getError());
            }
        }
        $forward= $_GET['forward'];
        $Verify = new \Think\Verify();
        $this->assign('forward',$forward);
        $cate= M('Category')->select();
        load('@.tree');
        $cateArray=array();
        foreach($cate as $key=>$val){

            if($val['parent_id']==0) {
                $val['child'] = getTree($cate, $val['cat_id']);
                $cateArray[] = $val;
            }
        }
//        var_dump($cateArray);exit;
        $this->assign("category",$cateArray);
        $this->assign("welcome","hello world");
        $heading = $this->fetch("Public/heading");
        $this->assign("heading",$heading);
        $this->display('register');
    }

    //登录的方法。
    public function login(){
        if(isset($_POST['user_name'])){
            $code=$_POST['code'];
            if(!$this->check_verify($code,'')){
                $this->error("验证码错误！");
            }
            $membermodel = D("Member");
            if($membermodel->login($_POST['user_name'],$_POST['password'])){
                $url = __MODULE__.'/Index/home';
                if($_POST["forward"]=="order")$url = __MODULE__.'/Order/orderform';
                $session_id=session_id();
                $cart=D('Cart');
                $data['user_id']=$_SESSION['user_id'];
                $cart->where("session_id='$session_id'")->save($data);
                $this->success("登录成功",$url);exit;

            }else{
                $this->error("用户名或密码输入错误");
            }
        }else{
            $this->error("用户名不能为空");
        }


//        $this->display();
    }
    public function logout(){
        $_SESSION['user_id']=null;
        $_SESSION['user_name']=null;
        $_SESSION['level_id']=null;
        $this->success("成功退出",__MODULE__.'/index/home');
    }
    public function createCode() {
        $Verify = new \Think\Verify();
        $Verify->entry();
    }

    public function CheckName() {
        if($_POST['user_name']==''){echo "用户名不能为空";exit;}
        $rule=array(
            array('user_name','require','用户名不能为空'),
            array('user_name','','用户名称已经存在',0,'unique',1),
            array('user_name','/^[a-zA-Z][a-zA-Z0-9_]{1,19}$/','用户名不合法'),
        );
        $user = M ( 'Member' );
        if (! $user->validate($rule)->create ()) {
            exit ( $user->getError () );
        } else {
            echo 0;//这是回传给$.post的数据，对应上面的data
        }
    }
    public function CheckEmail() {
        if($_POST['email']==''){echo "邮箱名不能为空";exit;}
        $rule=array(
            array('email','require','邮箱不能为空!'),
            array('email','email','邮箱格式不正确!'),
            array('email','','该邮箱已经注册过！',0,'unique',1),
        );
        $user = M ( 'Member' );
        if (! $user->validate($rule)->create ()) {
            exit ( $user->getError () );
        } else {
            echo 0;//这是回传给$.post的数据，对应上面的data
        }
    }

    public function CheckPass() {
        if($_POST['password']==''){echo "密码不能为空";exit;}
        $rule=array(
            array('password','require','密码不能为空'),
            array('password','6,12','密码要在6到12位之间',0,'length'),
        );
        $user = M ( 'Member' );
        if (! $user->validate($rule)->create ()) {
            exit ( $user->getError () );
        } else {
            echo 0;//这是回传给$.post的数据，对应上面的data
        }
    }

    public function CheckRPass() {
        $rule=array(
            array('rpassword','password','两次密码输入不一致',0,'confirm'),
            array('password','require','密码不能为空'),
        );
        $user = M ( 'Member' );
        if (! $user->validate($rule)->create ()) {
            exit ( $user->getError () );
        } else {
            echo 0;//这是回传给$.post的数据，对应上面的data
        }
    }

    public function CheckCode(){
        $code=$_POST['code'];
        if($this->check_verify($code,'',false)){
            echo 0;
        }else{
            echo "验证码错误";
        }
    }

    function check_verify($code, $id = '',$reset="true"){

        $verify = new \Think\Verify();
        if($reset)
        return $verify->check($code, $id);
        else
            return $verify->checkwithoutreset($code, $id);
    }

    public function changepw(){

        if(empty($_SESSION['user_id'])||$_SESSION['user_id']<1)exit;
        $this->display('changepw');
    }

    public function changeDone(){
        $user = D('Member');
        $rule=array(
            array('password','require','密码不能为空'),
            array('password','6,12','密码要在6到12位之间',0,'length'),
            array('rpassword','password','两次密码输入不一致',0,'confirm'),
        );


//        $_POST['birthday']=strtotime($_POST['birthday']);
        if($user->validate($rule)->field('password,rpassword')->create()){
            $user->password=md5($_POST['password']);
            if($user->where("id={$_SESSION['user_id']}")->save()){
                $this->success('Passward Updated');
            }else{
                $this->error($user->getError());
            }
        }else{
            $this->error($user->getError());
        }
    }
}