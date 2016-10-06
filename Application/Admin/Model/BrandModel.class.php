<?php
namespace Admin\Model;
use Think\Model;

class BrandModel extends Model{
	protected $_validate=array(
		array('name','require','品牌名称不能为空！'),
		array('cateid','require','品牌所属栏目必须选择'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_brand'";
		$res=$this->query($sql);
		return $res[0]['Auto_increment'];
	}
}