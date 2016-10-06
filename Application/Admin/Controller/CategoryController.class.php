<?php
namespace Admin\Controller;
use Think\Controller;

class CategoryController extends BaseController{
	private $category=null;
	private $attr=null;
	private $categroup=null;
	private $brand=null;
	public function __construct(){
		parent::__construct();
		$this->category=D('Category');
		$this->attr=D('Attr');
		$this->categroup=D('Categroup');
		$this->brand=D('Brand');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			$param=I('get.');
			if($param){
				foreach($param as $key=>$value){
					$paramStr.=$key.'/'.$value;
				}
			}else{
				$paramStr='gid/1';
			}
			if($data['action']=='delete'){
				$ids=$this->category->getDelIds($data['id']);
				if($this->category->delete($ids)){
					$this->success('删除成功！',U('Admin/Category/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_category SET sort='$value' WHERE id='$key'";
					$this->category->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Category/index/'.$paramStr),2);
			}
		}else{
			$categrouplist=$this->categroup->select();
			$gid=I('param.gid') ? I('param.gid') : $categrouplist[0]['id'];
			$list=$this->category->getAllSortNav($gid);
			$catelist=array();
			foreach($list as $value){
				if($value['attr']){
					$attrArr=explode(',',$value['attr']);
					$attrStr='';
					foreach($attrArr as $val){
						$oneAttr=$this->attr->field('name')->where(array('id'=>$val))->find();
						$attrStr.=$oneAttr['name'].',';
					}
					$attrStr=substr($attrStr,0,-1);
					$value['attr']=$attrStr;
				}
				$catelist[]=$value;
			}
			$this->assign('categrouplist',$categrouplist);
			$this->assign('catelist',$catelist);
			$this->assign('gid',$gid);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($data['attr']){
				$data['attr']=implode(',',$data['attr']);
			}else{
				$data['attr']='';
			}
			if($data['brandids']){
				$data['brandids']=implode(',',$data['brandids']);
			}else{
				$data['brandids']='';
			}
			//$data['sort']=$this->category->getAutoIncid();
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['thumb']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['thumb']=$this->thumb($path);
				}
			}else{
				$data['thumb']=__ROOT__.'/Public/Admin/img/no-thumb.jpg';
			}
			if($this->category->create($data)){
				if($this->category->add()){
					$this->success('添加栏目成功！',U('Admin/Category/index/gid/'.$data['groupid']),2);
				}else{
					$this->error('添加栏目失败！');
				}
			}else{
				$this->error($this->category->getError());
			}
		}else{
			$categrouplist=$this->categroup->select();
			$gid=I('param.gid') ? I('param.gid') : $categrouplist[0]['id'];
			$data['id']=I('param.id');
			if($data['id']){
				$oneParentCate=$this->category->where($data)->find();
				$groupname=$this->categroup->field('name')->where(array('id'=>$oneParentCate['groupid']))->find();
				$oneParentCate['groupname']=$groupname['name'];
				if($gid == 2){
					$allPrevNav=$this->category->getAllPreNav($data['id']);
					$brandlist=$this->brand->field('id,name')->where(array('cateid'=>$allPrevNav[0]['id']))->select();
					$this->assign('brandlist',$brandlist);
					$this->assign('gid',$gid);
				}
				$this->assign('oneParentCate',$oneParentCate);
			}else{
				$catelist=$this->category->getSortNav($gid);
				$this->assign('catelist',$catelist);
			}
			$attrlist=$this->attr->select();
			$categrouplist=$this->categroup->select();
			$this->assign('attrlist',$attrlist);
			$this->assign('categrouplist',$categrouplist);
			$this->display();
		}
	}
	public function getSortNavByGid(){
		$gid=I('param.gid');
		$catelist=$this->category->getSortNav($gid);
		if($catelist){
			$response=array('catelist'=>$catelist,'errno'=>0);
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关栏目');
		}
		$this->ajaxReturn($response,'json');
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($data['attr']){
				$data['attr']=implode(',',$data['attr']);
			}else{
				$data['attr']='';
			}
			if($data['brandids']){
				$data['brandids']=implode(',',$data['brandids']);
			}else{
				$data['brandids']='';
			}
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['thumb']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['thumb']=$this->thumb($path);
				}
			}
			if($this->category->create($data)){
				$ids=$this->category->getDelIds($data['id']);
				if(!strpos($ids,$data['pid'])){
					if($this->category->save()){
						$this->success('修改栏目成功！',U('Admin/Category/index/gid/'.$data['groupid']),2);
					}else{
						$this->error('修改栏目失败！');
					}
				}else{
					$this->error('不能选择自己或自己的下级作为上级栏目！');
				}
			}else{
				$this->error($this->category->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneCategory=$this->category->where($data)->find();
			$oneCategory['ids']=$this->category->getDelIds($data['id']);
			$groupname=$this->categroup->field('name')->where(array('id'=>$oneCategory['groupid']))->find();
			$oneCategory['groupname']=$groupname['name'];
			if($oneCategory['groupid'] == 2){
				$allPrevNav=$this->category->getAllPreNav($data['id']);
				$brandlist=$this->brand->field('id,name')->where(array('cateid'=>$allPrevNav[0]['id']))->select();
				$this->assign('brandlist',$brandlist);
				$this->assign('gid',$oneCategory['groupid']);
			}
			$catelist=$this->category->getSortNav($oneCategory['groupid']);
			$attrlist=$this->attr->select();
			$categrouplist=$this->categroup->select();
			$this->assign('categrouplist',$categrouplist);
			$this->assign('attrlist',$attrlist);
			$this->assign('catelist',$catelist);
			$this->assign('oneCategory',$oneCategory);
			$this->display();
		}
	}
	public function del(){
		$data=I('param.');
		$ids=$this->category->getDelIds($data['id']);
		if($this->category->delete($ids)){
			$this->success('删除成功！',U('Admin/Category/index/gid/'.$data['gid']),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function getAttr(){
		$data['id']=I('param.id');
		$oneCategory=$this->category->field('id,attr')->where($data)->find();
		if($oneCategory){
			if($oneCategory['attr'] != ''){
				$attrArr=explode(',',$oneCategory['attr']);
				$list=array();
				foreach($attrArr as $value){
					$oneAttr=$this->attr->where(array('id'=>$value))->find();
					$list[]=$oneAttr;
				}
				$response=array('errno'=>0,'list'=>$list);
			}else{
				$response=array('errno'=>2,'errmsg'=>'未找到相关属性');
			}
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关栏目');
		}
		$this->ajaxReturn($response,'JSON');
	}
}