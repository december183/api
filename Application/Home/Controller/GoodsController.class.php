<?php
namespace Home\Controller;
use Think\Controller;

class GoodsController extends ComController{
	private $goods=null;
	private $admire=null;
	private $collect=null;
	public function __construct(){
		parent::__construct();
		$this->goods=D('goods');
		$this->admire=D('Admire');
		$this->collect=D('Collect');
	}
	public function goodsList(){
		$data=I('param.');
		$map['cateid']=$data['cateid'];
		$map['p']=$data['page'] ? $data['page'] : 1;
		$total=$this->goods->where($map)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$show=$page->show();
		$list=$this->goods->field('id,thumbpic,title,saleprice,marketprice')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
		$admirelist=$this->admire->field('goodsid')->where(array('uid'=>$data['uid'],'commentid'=>0))->select();
		foreach($admirelist as $value){
			$admireids .= $value['goodsid'].',';
		}
		$admireids=substr($admireids,0,-1);
		$collectlist=$this->collect->field('goodsid')->where(array('uid'=>$data['uid'],'eventid'=>0))->select();
		foreach($collectlist as $value){
			$collectids .= $value['goodsid'].',';
		}
		$collectids=substr($collectids,0,-1);
		foreach($list as $value){
			if(strpos($admireids,$value['id'])){
				$value['isadmire']=1;
			}
			if(strpos($collectids,$value['id'])){
				$value['iscollect']=1;
			}
			$goodslist[]=$value;
		}
		if($goodslist){
			$this->apiReturn(200,'返回商品列表成功',$goodslist);
		}else{
			$this->apiNotice(400,'暂无数据');
		}
	}
	public function releaseGoods(){
		$data=I('param.');
		if($_FILES['file']){
			$arr=$this->upload();
			foreach($arr as $key=>$path){
				$imgArr=getimagesize($path);
				if($imgArr[0] < 800 && $imgArr[1] < 800){
					$path=str_replace('\\', '/',$path);
					$data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
				}else{
					$data['mainpic'].=$this->thumb($path).';';
				}
				if($key == 0){
					$data['thumbpic']=$this->thumb($path,100,100);
				}
			}
			$data['mainpic']=substr($data['mainpic'],0,-1);
		}else{
			$this->apiNotice(401,'请上传商品主图');
		}
		if($this->goods->create($data)){
			if($this->goods->add()){
				$this->apiNotice(200,'商品发布成功');
			}else{
				$this->apiNotice(402,'商品发布失败');
			}
		}else{
			$this->apiNotice(403,$this->goods->getError());
		}
	}
	public function editGoods(){
		$data=I('param.');
		if($_FILES['file']){
			$arr=$this->upload();
			foreach($arr as $key=>$path){
				$imgArr=getimagesize($path);
				if($imgArr[0] < 800 && $imgArr[1] < 800){
					$path=str_replace('\\', '/',$path);
					$data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
				}else{
					$data['mainpic'].=$this->thumb($path).';';
				}
				if($key == 0){
					$data['thumbpic']=$this->thumb($path,100,100);
				}
			}
			$data['mainpic']=substr($data['mainpic'],0,-1);
		}
		if($this->goods->create($data)){
			if($this->goods->save()){
				$this->apiNotice(200,'商品修改成功');
			}else{
				$this->apiNotice(401,'商品修改失败');
			}
		}else{
			$this->apiNotice(402,$this->goods->getError());
		}
	}
	public function goodsDetail(){
		$data=I('param.');
		$oneGoods=$this->goods->where($data)->find();
		if($oneGoods){
			$this->apiReturn(200,'返回商品详情成功',$oneGoods);
		}else{
			$this->apiNotice(400,'暂无该商品信息');
		}
	}
}