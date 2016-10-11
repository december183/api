<?php
namespace Home\Model;
use Think\Model;

class FeedbackModel extends Model{
	protected $_validate=array(
		array('content','require','反馈内容不能为空'),
	);
	protected $_auto=array(
		array('date',1,'time','function'),
	);
}