<?php
namespace Home\Controller;
use Think\Controller;

class RegionController extends Controller{
	private $region=null;
	public function __construct(){
		parent::__construct();
		$this->region=D('Region');
	}
	public function regionList(){
		$regionlist=$this->region->field('id,name')->where(array('gid'=>1,'cityid'=>1))->order('sort ASC')->select();
		if($regionlist){
			$this->apiReturn(200,'地区数据返回成功',$regionlist);
		}else{
			$this->apiReturn(401,'暂无地区数据');
		}
	}
}