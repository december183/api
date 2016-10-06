<?php
namespace Admin\Controller;
use Think\Controller;

class EventCommentController extends BaseController{
	private $comment=null;
	private $cate=null;
	private $event=null;
	public function __construct(){
		parent::__construct();
		$this->comment=D('Comment');
		$this->cate=D('Category');
		$this->event=D('Event');
	}
	public function index(){
		$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'groupid'=>4,'type'=>1))->select();
		$cid=I('get.cid') ? I('get.cid') : $topCates[0]['id'];
		$ids=$this->cate->getDelIds($cid);
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
				$ids=implode(',',$data['id']);
				if($this->comment->delete($ids)){
					$this->success('删除成功！',U('Admin/Comment/index/cid/'.$cid),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action'] == 'search'){
				$condition['title']=array('like','%'.$data['q'].'%');
				$condition['cateid']=array('in',$ids);
				$themelist=$this->event->field('id')->where($condition)->select();
				if($themelist){
					foreach($themelist as $value){
						$idstr.=$value['id'].',';
					}
					$map['themeid']=array('in',substr($idstr,0,-1));
					$map2['themeid']=array('in',substr($idstr,0,-1));
				}else{
					$map['themeid']=array('eq',0);
					$map2['themeid']=array('eq',0);
				}
				$map['type']=array('eq',1);
				$map2['a.type']=array('eq',1);
				$total=$this->comment->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$commentlist=$this->comment->alias('a')->join('app_user as b ON a.uid=b.id')->join('app_event as c ON a.themeid=c.id')->field('a.id,b.username,b.avatar,c.title as theme,a.content,a.agreenum,a.date')->where($map2)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
				$this->assign('topCates',$topCates);
				$this->assign('commentlist',$commentlist);
				$this->assign('cid',$cid);
				$this->assign('page',$show);
				$this->display();
			}
		}else{
			$condition['cateid']=array('in',$ids);
			$themelist=$this->event->field('id')->where($condition)->select();
			if($themelist){
				foreach($themelist as $value){
					$idstr.=$value['id'].',';
				}
				$map['themeid']=array('in',substr($idstr,0,-1));
				$map2['themeid']=array('in',substr($idstr,0,-1));
			}else{
				$map['themeid']=array('eq',0);
				$map2['themeid']=array('eq',0);
			}
			$map['type']=array('eq',1);
			$map2['a.type']=array('eq',1);
			$total=$this->comment->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$commentlist=$this->comment->alias('a')->join('app_user as b ON a.uid=b.id')->join('app_topic as c ON a.themeid=c.id')->field('a.id,b.username,b.avatar,c.title as theme,a.content,a.agreenum,a.date')->where($map2)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('cid',$cid);
			$this->assign('topCates',$topCates);
			$this->assign('commentlist',$commentlist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function del(){
		$data=I('get.');
		if($this->comment->delete($data['id'])){
			$this->success('删除成功！',U('Admin/Comment/index/cid/'.$data['cid']),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function checkComment(){
		$data['id']=I('param.id');
		$oneComment=$this->comment->where($data)->find();
		if($oneComment['is_checked'] == 1){
			$data['is_checked'] = 0;
			if($this->comment->save($data)){
				$response=array('errno'=>0,'is_checked'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['is_checked'] = 1;
			if($this->comment->save($data)){
				$response=array('errno'=>0,'is_checked'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}