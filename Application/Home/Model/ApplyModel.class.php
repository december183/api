<?php
namespace Home\Model;
use Think\Model;

class ApplyModel extends Model{
	protected $_validate=array(
		array('eventid','require','必须传入活动ID'),
		array('uid','require','必须传入报名用户ID'),
		array('name','require','报名用户名称必须填写'),
		array('phone','/^1[34578]\d{9}$/','手机号码格式不正确',0,'regex',1),
		array('num','number','参与人数必须为数字',0,'regex',1),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}