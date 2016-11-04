<?php
namespace Admin\Model;
use Think\Model;

class TaskModel extends Model{
	protected $_validate=array(
		array('name','require','任务名称必须选择'),
		array('point','require','任务分手必须填写'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_task'";
		$res=$this->query($sql);
		return $res[0]['auto_increment'];
	}
}