<?php
namespace Home\Model;
use Think\Model;

class OrderModel extends Model{
	protected $validate=array(
		array('uid','require','必须传入用户uid参数'),
		array('type','require','必须传入商品类型type参数'),
		array('paymethod','require','请选择订单支付方式'),
		array('pickaddress','require','必须填写用户收获地址'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}