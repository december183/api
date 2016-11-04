<?php
namespace Home\Model;
use Think\Model;

class WithdrawModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入用户ID'),
		array('money','require','提现金额必须填写'),
		array('name','require','提现姓名必须填写'),
		array('type','require','提现账号类型必须选择'),
		array('account','require','提现账号必须填写'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}