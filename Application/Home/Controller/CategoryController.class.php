<?php
namespace Home\Controller;
use Think\Controller;

class CategoryController extends Controller{
	private $category=null;
	public function __construct(){
		parent::__construct();
		$this->category=D('Category');
	}
	public function goodsCateList(){
		$catelist=$this->category->getSortNav(2);
		if($catelist){
			$this->apiReturn(200,'返回商品栏目成功',$catelist);
		}else{
			$this->apiReturn(404,'暂无商品栏目信息');
		}
	}
	public function serviceCateList(){
		$catelist=$this->category->getSortNav(3);
		if($catelist){
			$this->apiReturn(200,'返回商品栏目成功',$catelist);
		}else{
			$this->apiReturn(404,'暂无商品栏目信息');
		}
	}
	public function eventCateList(){
		$catelist=$this->category->getSortNav(4);
		if($catelist){
			$this->apiReturn(200,'返回活动栏目成功',$catelist);
		}else{
			$this->apiReturn(404,'暂无活动栏目信息');
		}
	}
	public function topicCateList(){
		$catelist=$this->category->getSortNav(5);
		if($catelist){
			$this->apiReturn(200,'返回论坛栏目成功',$catelist);
		}else{
			$this->apiReturn(404,'暂无论坛栏目信息');
		}
	}
}