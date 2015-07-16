<?php
/**
 * admin表模型
 */
namespace Admin\Model;
use Think\Model;
class AdminModel extends Model {
	protected $fields=array(
        'id',
        'admin_name',
        'last_ip',
        'password',
        'level',
        '_autoinc' => true,
        '_pk' => 'id'
    );


	/**
	 * 利用登陆时的名称和密码进行查询
	 *
	 * @param $admin_name 用户名
	 * @param $admin_pass 密码
	 *
	 * @return mixed 合法,array当前管理员信息，非法：false
	 */
	public function checkByLogin($admin_name, $admin_pass) {
		//形成SQL
        $pass= md5($admin_pass);
        $row = $this->where("admin_name='$admin_name' and password = '$pass'")->find();

        return $row;//将数据转成布尔值即可
	}

	/**
	 * 利用cookie查询
	 */
	public function checkByCookie() {
		//判断是否有cookie
		if(!isset($_COOKIE['admin_id'])  || !isset($_COOKIE['admin_pass'])) {
			return false;
		}

		//是否合法
//		$sql = "select * from {$this->table()} where admin_id='{$_COOKIE['admin_id']}' and md5(concat('itcast',admin_pass,'php')) = '{$_COOKIE['admin_pass']}'";
//		return $this->db->fetchRow($sql);
	}
}