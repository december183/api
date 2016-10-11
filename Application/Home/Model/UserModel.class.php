<?php
namespace Home\Model;
use Think\Model;

class UserModel extends Model{
	protected $_validate=array(
		array('phone','','该手机号码已注册',0,'unique',4),
		array('phone','/^1[34578]\d{9}$/','手机号码格式不正确',0,'regex',4),
		array('pass','require','用户密码不能为空',0,'regex',4),
		array('phone','/^1[34578]\d{9}$/','手机号码格式不正确',0,'regex',3),
		array('pass','require','用户密码不能为空'),
	);
	protected $_auto=array(
		array('pass','password',4,'function'),
		array('date','time',4,'function'),
		array('pass','password',1,'function'),
	);
}