<?php
namespace Home\Model;
use Think\Model;

class AdmireModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入登陆用户ID'),
		array('serviceid','require','必须传入商品ID',0,'regex',1),
		array('commentid','require','必须传入评论ID',0,'regex',1),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}