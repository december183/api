<?php
namespace Admin\Model;
use Think\Model;

class CreditGoodsModel extends Model{
	protected $_validate=array(
		array('title','require','商品标题必须填写'),
		array('mainpic','require','必须上传商品主图必须上传',0,'regex',1),
		array('credit','require','商品积分必须填写'),
		array('inventory','require','商品库存必须填写'),
		array('descript','require','商品描述必须填写'),
		array('content','require','商品详情必须填写'),
	);
	protected $_auto=array(
		array('date','time',1,'function'),
		array('sort','getAutoIncid',1,'callback'),
	);
	public function getAutoIncid(){
		$sql="SHOW TABLE STATUS LIKE 'app_credit_goods'";
		$res=$this->query($sql);
		return $res[0]['auto_increment'];
	}
}