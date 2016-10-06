<?php
namespace Admin\Controller;
use Think\Controller;

class ArticleController extends BaseController{
	private $category=null;
	private $attr=null;
	public function __construct(){
		parent::__construct();
		$this->category=D('Category');
		$this->attr=D('Attr');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->article->delete($ids)){
					$this->success('删除成功！',U('Article/index'),2);
				}else{
					$this->error('删除失败！');
				}
			}
		}else{
			$total=$this->article->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$list=$this->article->alias('a')->join('app_category as c ON a.cateid=c.id')->field('a.id,a.title,a.tags,a.thumbnail,a.descript,a.comment_count,c.name as typename')->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$articlelist=array();
			foreach($list as $value){
				$value['tags']=explode(',',$value['tags']);
				$articlelist[]=$value;
			}
			$this->assign('articlelist',$articlelist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($data['attr']){
				$data['attr']=json_encode($data['attr']);
			}
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['thumbnail']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['thumbnail']=$this->thumb($path);
				}
			}else{
				$data['thumbnail']=__ROOT__.'/Public/Admin/img/no-thumb.jpg';
			}
			if($this->article->create($data)){
				if($this->article->add()){
					$this->success('新增文章成功！',U('Article/index'),2);
				}else{
					$this->error('新增文章失败！');
				}
			}else{
				$this->error($this->article->getError());
			}
		}else{
			$catelist=$this->category->getSortNav(1);
			$this->assign('catelist',$catelist);
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($data['attr']){
				$data['attr']=json_encode($data['attr']);
			}
			if($_FILES['pic']['tmp_name']){
				$path=$this->upload();
				$imgArr=getimagesize($path);
				if($imgArr[0] < 600 && $imgArr[1] < 600){
					$path=str_replace('\\', '/',$path);
					$data['thumbnail']=strstr($path,__ROOT__.'/Uploads/image/');
				}else{
					$data['thumbnail']=$this->thumb($path);
				}
			}
			if($this->article->create($data)){
				if($this->article->save()){
					$this->success('修改文章成功！',U('Article/index'),2);
				}else{
					$this->error('修改文章失败！');
				}
			}else{
				$this->error($this->article->getError());
			}
		}else{
			$data['id']=I('get.id');
			$oneArticle=$this->article->where($data)->find();
			$catelist=$this->category->getSortNav(1);
			$this->assign('catelist',$catelist);
			$this->assign('oneArticle',$oneArticle);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		if($this->article->delete($id)){
			$this->success('删除成功！',U('Article/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function getCurAttr(){
		$data['id']=I('param.id');
		$oneArticle=$this->article->field('attr,cateid')->where($data)->find();
		$oneCategory=$this->category->field('id,attr')->where(array('id'=>$oneArticle['cateid']))->find();
		$oneArticle['attr']=json_decode($oneArticle['attr'],true);
		$oneArticle['attrkeys']=array_keys($oneArticle['attr']);
		if($oneCategory){
			if($oneCategory['attr'] != ''){
				$attrArr=explode(',',$oneCategory['attr']);
				$list=array();
				foreach($attrArr as $value){
					$oneAttr=$this->attr->where(array('id'=>$value))->find();
					$list[]=$oneAttr;
				}
				$response=array('errno'=>0,'list'=>$list,'attr'=>$oneArticle);
			}else{
				$response=array('errno'=>2,'errmsg'=>'未找到相关属性');
			}
		}else{
			$response=array('errno'=>1,'errmsg'=>'未找到相关栏目');
		}
		$this->ajaxReturn($response,'JSON');
	}
	public function search(){
		$query=I('param.q');
		$condition['title']=array('LIKE','%'.$query.'%');
        $articlelist=$this->article->where($condition)->order('date DESC')->select();
        $this->assign('articlelist',$articlelist);
        $this->display('Article/search');
	}
}