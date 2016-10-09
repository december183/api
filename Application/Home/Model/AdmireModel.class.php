<?php
namespace Home\Model;
use Think\Model;

class AdmireModel extends Model{
	protected $_validate=array(
		
	);
	protected $_auto=array(
		array('date','time',1,'function'),
	);
}