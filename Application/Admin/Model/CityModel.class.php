<?php
namespace Admin\Model;
use Think\Model;

class CityModel extends Model{
	protected $_validate=array(
		array('name','require','城市名称必须填写！'),
		array('name','','省分名称不能重复',0,'unique',1),
		array('pid','require','所在省分必须选择！'),
	);
	protected $_auto=array(
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_city'";
		$res=$this->query($sql);
		return $res[0]['Auto_increment'];
	}
}