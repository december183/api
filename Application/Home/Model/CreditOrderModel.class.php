<?php
namespace Home\Model;
use Think\Model;

class CreditOrderModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入当前登录用户ID',1,'regex',1),
		array('gid','require','必须传入兑换商品ID',1,'regex',1),
		array('num','require','兑换商品数量必须填写',1,'regex',1),
		array('credit','require','必须传入单间商品所需积分',1,'regex',1),
		array('pickid','require','必须传入收货地址ID',1,'regex',1),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}