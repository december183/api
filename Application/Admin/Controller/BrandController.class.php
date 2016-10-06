<?php
namespace Admin\Controller;
use Think\Controller;

class BrandController extends BaseController{
	private $brand=null;
	private $cate=null;
	public function __construct(){
		parent::__construct();
		$this->brand=D('Brand');
		$this->cate=D('Category');
	}
	public function index(){
		$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'groupid'=>2,'type'=>1))->select();
		$cid=I('get.cid') ? I('get.cid') : $topCates[0]['id'];
		$map['cateid']=array('eq',$cid);
		if(IS_POST){
			$data=I('param.');
			$param=I('get.');
			if($param){
				foreach($param as $key=>$value){
					$paramStr.=$key.'/'.$value;
				}
			}else{
				$paramStr='cid/'.$cid;
			}
			if($data['action']=='delete'){
				$idstr=implode(',',$data['id']);
				if($this->brand->delete($idstr)){
					$this->success('删除成功！',U('Admin/Brand/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_brand SET sort='$value' WHERE id='$key'";
					$this->brand->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Brand/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['name']=array('like','%'.$data['q'].'%');
				$brandlist=$this->brand->where($map)->order('sort ASC')->select();
				$this->assign('topCates',$topCates);
				$this->assign('brandlist',$brandlist);
				$this->assign('cid',$cid);
				$this->display();
			}
		}else{
			$total=$this->brand->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$brandlist=$this->brand->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$show);
			$this->assign('brandlist',$brandlist);
			$this->assign('topCates',$topCates);
			$this->assign('cid',$cid);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['logo']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['logo']=$this->thumb($path);
				}
			}else{
				$data['logo']=__ROOT__.'/Public/Admin/img/no-thumb.jpg';
			}
			if($this->brand->create($data)){
				if($this->brand->add()){
					$this->success('新增成功！',U('Admin/Brand/index/cid/'.$data['cateid']),2);
				}else{
					$this->error('新增失败！');
				}
			}else{
				$this->error($this->brand->getError());
			}
		}else{
			$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'type'=>1,'groupid'=>2))->select();
			$this->assign('topCates',$topCates);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['logo']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['logo']=$this->thumb($path);
				}
			}
			if($this->brand->create($data)){
				if($this->brand->save()){
					$this->success('修改成功！',U('Admin/Brand/index/cid/'.$data['cateid']),2);
				}else{
					$this->error('修改失败！');
				}
			}else{
				$this->error($this->brand->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneBrand=$this->brand->where($data)->find();
			$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'type'=>1,'groupid'=>2))->select();
			$this->assign('topCates',$topCates);
			$this->assign('oneBrand',$oneBrand);
			$this->display();
		}
	}
	public function del(){
		$data=I('get.');
		if($this->brand->delete($data['id'])){
			$this->success('删除成功！',U('Admin/Brand/index/id'.$data['cid']),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function getBrand(){
		$id=I('param.cateid');
		$allPrevNav=$this->cate->getAllPreNav($id);
		$brandlist=$this->brand->field('id,name')->where(array('cateid'=>$allPrevNav[0]['id']))->select();
		if($brandlist){
			$response=array('brandlist'=>$brandlist,'errno'=>0);
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关品牌');
		}
		$this->ajaxReturn($response,'json');
	}
}