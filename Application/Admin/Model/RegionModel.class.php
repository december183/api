<?php
namespace Admin\Model;
use Think\Model;

class RegionModel extends Model{
	protected $_validate=array(
		array('name','require','地区名称必须填写！'),
		array('name','','地区名称不能重复',0,'unique',1),
		array('pid','require','所在省分必须选择！'),
		array('cityid','require','所在城市必须选择！'),
	);
	protected $_auto=array(
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_region'";
		$res=$this->query($sql);
		return $res[0]['auto_increment'];
	}
}