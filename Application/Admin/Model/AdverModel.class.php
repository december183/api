<?php
namespace Admin\Model;
use Think\Model;

class AdverModel extends Model{
	protected $_validate=array(
		array('url','require','广告链接必须填写！'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}