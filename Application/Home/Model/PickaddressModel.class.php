<?php
namespace Home\Model;
use Think\Model;

class PickaddressModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入当前用户ID'),
		array('name','require','收货人姓名必须填写'),
		array('phone','/^1[34578]\d{9}$/','手机号码格式不正确',0,'regex',1),
		array('province','require','省分必须选择'),
		array('cityid','require','城市必须选择'),
		array('region','require','地区必须选择'),
		array('place','require','详细地址必须填写'),
	);
	protected $_auto=array(

	);
}