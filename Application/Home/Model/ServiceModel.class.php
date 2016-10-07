<?php
namespace Home\Model;
use Think\Model;

class ServiceModel extends Model{
	protected $_validate=array(
		array('region','require','请选择区域');
		array('location','require','请填写详细地址');
		array('category','require','请选择商品类别'),
		array('title','require','请填写商品标题'),
		array('descript','require','请填写商品描述'),
		array('linkman','require','请填写联系人'),
		array('phone','require','请填写联系人手机号'),
		array('qq','require','请填写联系人qq号'),
		array('isdiscount','require','请选择商品是否打折'),
	);
	protected $_auto=array(
		array('hits','randClick',1,'callback');
		array('sort','getAutoIncid',1,'callback'),
	);
	public function randClick($min=50,$max=100){
		return rand($min,$max);
	}
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_service'";
		$res=$this->query($sql);
		return $res[0]['Auto_increment'];
	}
}