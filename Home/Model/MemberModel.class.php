<?php
namespace Home\Model;
use Think\Model;
class MemberModel extends Model{
    protected $_validate=array(
        array('user_name','require','用户名不能为空'),
        array('user_name','','用户名称已经存在',0,'unique',1),
        array('user_name','/^[a-zA-Z][a-zA-Z0-9_]{1,19}$/','用户名不合法'),
        array('password','require','密码不能为空'),
        array('password','6,12','密码要在6到12位之间',0,'length'),
        array('rpassword','password','两次密码输入不一致',0,'confirm'),
        array('email','require','邮箱不能为空!'),
        array('email','email','邮箱格式不正确!'),
        array('email','','该邮箱已经注册过！',0,'unique',1),
        array('verify','require','验证码必须！'),
    );
    //验证用户登录的方法
    public function login($username,$password){
            //$sql="select * from user where username='$username' and password='$password'";
            //根据用户名找出密码，该密码再和用户输入的密码进行比较。
            //思路；判断输入的用户名是否是一个邮箱，如果是邮箱根据邮箱找出用户名。
            if($this->is_email($username)){
                    $userinfo = $this->where("email='$username'")->getField('user_name');  
                    if(!empty($userinfo)){
                        $username= $userinfo;
                    }
            }
            $userinfo = $this->where("user_name='$username'")->find();
            if($userinfo){
                    if($userinfo['password']==md5($password)){
                            //和方法的用户，把一些信息写入到session里面。
                            $_SESSION['user_name']=$username;
                            $_SESSION['user_id']=$userinfo['id'];
                            //把登录用户的会员级别写入到session里面，便于计算当前用户的会员价格。
                            return true;
                    }
            }
            return false;
    }

    function is_email($user_email){
        $chars = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
        if (strpos($user_email, '@') !== false && strpos($user_email, '.') !== false)
        {
                if (preg_match($chars, $user_email))
                {
                    return true;
                }
                else
                {
                    return false;
                }
        }
        else
            {
                return false;
            }
        }

}

?>