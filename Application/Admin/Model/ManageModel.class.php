<?php
namespace Admin\Model;
use Think\Model;

class ManageModel extends Model{
	protected $_validate=array(
		array('username','require','管理员用户名不得为空！',0,'regex',4),
		array('username','require','管理员用户名不得为空！',0,'regex',1),
		array('userpass','require','管理员密码不得为空！',0,'regex',4),
		array('oldpass','require','原密码必须填写！'),
		array('userpass','require','管理员密码不得为空！',0,'regex',1),
		array('userpass','ckuserpass','密码与确认密码必须一致！',0,'confirm',3),
		array('email','email','邮箱格式不正确！',2,'regex',3),
		array('phone','/^1\d{10}$/','手机号码格式不正确',2,'regex',3),
	);
	protected $_auto=array(
		array('userpass','password',1,'function'),//新增时对password字段使用password函数处理
		array('createtime','time',1,'function'),//新增时写入当前时间戳
		array('userpass','',2,'ignore'),//修改时密码为空则忽略密码字段
	);
	public function login($data){
		$oneUser=$this->where(array('username'=>$data['username']))->find();
		if($oneUser){
			if($oneUser['userpass'] == password($data['userpass'])){
				unset($oneUser['userpass']);
				session('user',$oneUser);
				return true;
			}else{
				$this->error = '密码错误！';
				return false;
			}
		}else{
			$this->error = '用户名输入错误！';
			return false;
		}
	}
}