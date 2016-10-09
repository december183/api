<?php
namespace Home\Controller;
use Think\Controller;

class ServiceController extends ComController{
	private $service=null;
	private $admire=null;
	private $collect=null;
	private $category=null;
	public function __construct(){
		parent::__construct();
		$this->service=D('Service');
		$this->admire=D('Admire');
		$this->collect=D('Collect');
		$this->category=D('Category');
	}
	public function serviceList(){
		$data=I('param.');
		$map['cateid']=$data['cateid'];
		$map['cateid']=array('IN',$this->category->getDelIds($data['cateid']));
		$page=$data['page'] ? $data['page'] : 1;
		$map['p']=array('eq',$page);
		$total=$this->service->where($map)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$show=$page->show();
		$list=$this->service->field('id,thumbpic,title,price,location')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
		$admirelist=$this->admire->field('serviceid')->where(array('uid'=>$data['uid'],'commentid'=>0))->select();
		foreach($admirelist as $value){
			$admireids .= $value['serviceid'].',';
		}
		$admireids=substr($admireids,0,-1);
		$collectlist=$this->collect->field('serviceid')->where(array('uid'=>$data['uid'],'eventid'=>0))->select();
		foreach($collectlist as $value){
			$collectids .= $value['serviceid'].',';
		}
		$collectids=substr($collectids,0,-1);
		foreach($list as $value){
			if(strpos($admireids,$value['id']) !== false){
				$value['isadmire']=1;
			}else{
				$value['isadmire']=0;
			}
			if(strpos($collectids,$value['id']) !== false){
				$value['iscollect']=1;
			}else{
				$value['iscollect']=0;
			}
			$servicelist[]=$value;
		}
		if($servicelist){
			$this->apiReturn(200,'返回商品列表成功',$servicelist);
		}else{
			$this->apiNotice(401,'暂无数据');
		}
	}
	public function releaseService(){
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
			$this->apiNotice(402,'请上传商品主图');
		}
		if($this->service->create($data)){
			if($this->service->add()){
				$this->apiNotice(200,'商品发布成功');
			}else{
				$this->apiNotice(404,'商品发布失败');
			}
		}else{
			$this->apiNotice(403,$this->service->getError());
		}
	}
	public function editService(){
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
		if($this->service->create($data)){
			if($this->service->save()){
				$this->apiNotice(200,'商品修改成功');
			}else{
				$this->apiNotice(404,'商品修改失败');
			}
		}else{
			$this->apiNotice(402,$this->service->getError());
		}
	}
	public function serviceDetail(){
		$data=I('param.');
		$oneService=$this->service->where($data)->find();
		if($oneService){
			$this->apiReturn(200,'返回商品详情成功',$oneService);
		}else{
			$this->apiNotice(404,'暂无该商品信息');
		}
	}

}