<?php
namespace Admin\Model;
use Think\Model;

class PriceModel extends Model{
	protected $_validate=array(
		array('minprice','number','最低价必须为数字',1,'regex',3),
		array('maxprice','number','最高价必须为数字',1,'regex',3),
		array('gid','require','栏目组必须选择'),
		array('cateid','require','栏目类别必须选择'),
	);
	protected $_auto=array(

	);
}