<?php
namespace Home\Model;
use Think\Model;

class CreditOrderModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入当前登录用户ID'),
		array('gid','require','必须传入兑换商品ID'),
		array('num','require','兑换商品数量必须填写'),
		array('pickaddress','require','收获地址必须填写'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}