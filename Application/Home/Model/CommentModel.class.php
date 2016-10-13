<?php
namespace Home\Model;
use Think\Model;

class CommentModel extends Model{
	protected $_validate=array(
		array('uid','require','必须传入登陆用户ID'),
		array('themeid','require','必须传入评论主题ID'),
		array('type','require','必须传入评论类型'),
		array('content','require','必须填写评论内容'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}