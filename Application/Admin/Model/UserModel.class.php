<?php
namespace Admin\Model;
use Think\Model;

class UserModel extends Model{
	protected $_validate=array(
		array('username','require','用户名不得为空！'),
		array('phone','/^1[34578]\d{9}$/','手机号码格式不正确！',0,'regex',3),
		array('userpass','require','密码不得为空！',0,'regex',4),
		array('oldpass','require','原密码必须填写！'),
		array('userpass','require','密码不得为空！',0,'regex',1),
		array('userpass','ckuserpass','密码与确认密码必须一致！',0,'confirm',3),
	);
	protected $_auto=array(
		array(),
	);
}