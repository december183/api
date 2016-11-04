<?php
namespace Admin\Model;
use Think\Model;

class AdverModel extends Model{
	protected $_validate=array(
		array('url','require','广告链接必须填写！'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_adver'";
		$res=$this->query($sql);
		return $res[0]['auto_increment'];
	}
}