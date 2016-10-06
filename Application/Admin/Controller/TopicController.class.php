<?php
namespace Admin\Controller;
use Think\Controller;

class TopicController extends BaseController{
	private $topic=null;
	private $cate=null;
	public function __construct(){
		parent::__construct();
		$this->topic=D('Topic');
		$this->cate=D('Category');
	}
	public function index(){
		$topCates=$this->cate->field('id,name')->where(array('pid'=>0,'groupid'=>5,'type'=>1))->select();
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
				$idstr=implode(',',$data['id']);
				if($this->topic->delete($idstr)){
					$this->success('删除成功！',U('Admin/Topic/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='check'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_topic SET status=1 WHERE id='$id' LIMIT 1";
					$this->topic->execute($sql);
				}
				$this->success('批量审核成功！',U('Admin/Topic/index/'.$paramStr),2);
			}elseif($data['action'] == 'rec'){
				foreach($data['id'] as $id){
					$sql="UPDATE app_topic SET isrec=1 WHERE id='$id' LIMIT 1";
					$this->topic->execute($sql);
				}
				$this->success('推荐成功！',U('Admin/Topic/index/'.$paramStr),2);
			}elseif($data['action'] == 'search'){
				$map['cateid']=array('in',$ids);
				$map['title']=array('like','%'.$data['q'].'%');
				$total=$this->topic->where($map)->count();
				$page=new \Think\Page($total,PAGE_SIZE);
				$show=$page->show();
				$list=$this->topic->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.title,a.tags,a.thumbpic,a.commentnum,a.isrec,a.status,b.name as typename')->where($map)->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
				foreach($list as $value){
					$value['tags']=explode(',',$value['tags']);
					$topiclist[]=$value;
				}
				$this->assign('topCates',$topCates);
				$this->assign('page',$show);
				$this->assign('topiclist',$topiclist);
				$this->assign('cid',$cid);
				$this->display();
			}
		}else{
			$map['cateid']=array('in',$ids);
			$total=$this->topic->where($map)->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$list=$this->topic->alias('a')->join('app_category as b ON a.cateid=b.id')->field('a.id,a.title,a.tags,a.thumbpic,a.commentnum,a.isrec,a.status,b.name as typename')->where($map)->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as $value){
				$value['tags']=explode(',',$value['tags']);
				$topiclist[]=$value;
			}
			$this->assign('topCates',$topCates);
			$this->assign('page',$show);
			$this->assign('topiclist',$topiclist);
			$this->assign('cid',$cid);
			$this->display();
		}
	}
	public function del(){
		$data=I('param.');
		if($this->topic->delete($data['id'])){
			$this->success('删除成功！',U('Admin/Topic/index/cid/'.$data['cid']),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneTopic=$this->topic->where($data)->find();
		if($oneTopic['status'] == 1){
			$data['status'] = 0;
			if($this->topic->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->topic->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
	public function isRec(){
		$data['id']=I('param.id');
		$oneTopic=$this->topic->where($data)->find();
		if($oneTopic['isrec'] == 1){
			$data['isrec'] = 0;
			if($this->topic->save($data)){
				$response=array('errno'=>0,'isrec'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['isrec'] = 1;
			if($this->topic->save($data)){
				$response=array('errno'=>0,'isrec'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}
}