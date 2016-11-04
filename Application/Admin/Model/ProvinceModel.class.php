<?php
namespace Admin\Model;
use Think\Model;

class ProvinceModel extends Model{
	protected $_validate=array(
		array('name','require','省分名称必须填写！'),
		array('name','','省分名称不能重复',0,'unique',1),
	);
	protected $_auto=array(
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_province'";
		$res=$this->query($sql);
		return $res[0]['auto_increment'];
	}
}