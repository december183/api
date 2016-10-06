<?php
namespace Admin\Model;
use Think\Model;

class ArticleModel extends Model{
	protected $_validate=array(
		array('title','require','文章标题必须填写！'),
		array('title','','文章标题不能重复！',0,'unique',1),
		array('catid','require','文章栏目必须选择！'),
		array('date','require','发布日期必须填写！'),
		array('descript','require','文章描述必须填写！'),
		array('content','require','文章详情必须填写！'),
	);
	protected $_auto=array(
		array('date','strtotime',3,'function'),
		array('click_count','randClick',1,'callback'),
	);
	protected function randClick($min=50,$max=100){
		return rand($min,$max);
	}
}