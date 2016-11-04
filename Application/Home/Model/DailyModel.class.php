<?php
namespace Home\Model;
use Think\Model;

class DailyModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入登陆用户ID'),
	);
	protected $_auto=array(
		
	);
}