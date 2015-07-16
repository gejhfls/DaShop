<?php
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/3/24
 * Time: 下午4:06
 */
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {
      /*
       * loginView
       */
    public function index() {
        test1();
        $this->login();
    }
    public function login(){
        D('Admin')->select();
        $this->display('login');
    }

    /*
     * check login
     */
    public function checkLogin(){
        $code = $_POST ['code'];
        // 判断验证码与用户输入的是否一致
        if (!$this->check_verify($code)) {
                $this->error ( '验证码不正确', 'login' );
                exit ();
        }
        if(isset($_POST['btnOk'])){
            $username=$_POST['username'];
            $password=md5($_POST['password']);
            $count=M('Admin')->where("username='$username' and password='$password'")->count();
        }
        if($count>0){
            session('username',$username);
            $this->redirect('Index/index',array(),3,'logining....');
        }else{
            $this->error('failde','login');
        }
    }

    function check_verify($code, $id = ''){
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }

    /**
     * 用于创建验证码图片
     */
    public function createCode() {
        $Verify = new \Think\Verify();
        $Verify->useZh = true;
        $Verify->entry();
    }


}