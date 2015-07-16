<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Created by PhpStorm.
 * User: mebius
 * Date: 15/4/21
 * Time: 下午9:11
 */
class LoginController extends Controller{
    function index(){
        $this->login();
    }
    function login(){
        $this->display('login');
    }

    public function logout(){
        $_SESSION['admin_id']=null;
        $_SESSION['admin_pass']=null;
        $this->success("成功登出",__MODULE__.'/Login',2);
        exit;
    }

    public function signinAction() {
//		die;
        //验证验证码
        $code=$_POST['code'];
//        if(!$this->check_verify($code,'')){
//            $this->error("验证码错误!");
//        }

        //调用模型完成数据库操作
        //利用 表单内的 管理员名 和 密码 完成 查询！
        $model_admin = D("Admin");
        if ($admin_info = $model_admin->checkByLogin($_POST['username'], $_POST['password'])) {
            //验证通过

            //是否记录cookie登陆信息
            if (isset($_POST['remember']) && $_POST['remember'] == '1') {
                //需要保存
                setcookie('admin_id', $admin_info['admin_id'], PHP_INT_MAX);
                setcookie('admin_pass', md5('itcast'.$admin_info['admin_pass'].'php'), PHP_INT_MAX);

            }

            //设置等登陆凭证
            $_SESSION['ad_login'] = 'yes';
            //转到后台首页,立即跳转
            $this->success("登录成功",__MODULE__.'/index');exit;
        } else {
            //非法用户
            $this->error("非法用户！");
        }
    }

    public function createCode() {
        $Verify = new \Think\Verify();
        $Verify->entry();
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
}