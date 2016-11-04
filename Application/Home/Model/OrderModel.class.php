<?php
namespace Home\Model;
use Think\Model;

class OrderModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入用户uid参数',1,'regex',1),
		array('info','require','必须传入订单商品信息',1,'regex',1),
		array('paymethod','require','请选择订单支付方式',1,'regex',1),
		array('pickid','require','必须填写用户收获地址',1,'regex',1),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}