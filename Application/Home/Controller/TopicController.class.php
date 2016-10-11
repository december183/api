<?php
namespace Home\Controller;
use Think\Controller;

class TopicController extends ComController{
	private $topic=null;
	private $category=null;
	private $comment=null;
	private $admire=null;
	public function __construct(){
		parent::__construct();
		$this->topic=D('Topic');
		$this->category=D('Category');
		$this->comment=D('Comment');
		$this->admire=D('Admire');
	}
	public function topicList(){
		$data=I('param.');
		$map['cateid']=array('in',$this->category->getDelIds($data['cateid']));
		$total=$this->topic->where($map)->select();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$topiclist=$this->topic->field('id,title,tags,thumbpic,commentnum,hits,date')->where($map)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($topiclist){
			$list=array('cate'=>$catelist,'topic'=>$topiclist);
			$this->apiReturn(200,'返回帖子列表成功',$list);
		}else{
			$this->apiReturn(401,'暂无帖子数据');
		}
	}
	public function releaseTopic(){

	}
	public function topicDetail(){
		$data=I('param.');
		$this->topic->where(array('id'=>$data['id']))->setInc('hits');
		$map['a.id']=$data['id'];
		$oneTopic=$this->topic->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.title,a.tags,a.thumbpic,a.content,a.date,b.username,b.avatar')->where($map)->find();
		if($oneTopic){
			$admirelist=$this->admire->field('commentid')->where(array('uid'=>$data['uid'],'serviceid'=>0))->select();
			foreach($admirelist as $value){
				$admireids .= $value['commentid'].',';
			}
			$admireids=substr($admireids,0,-1);
			$list=$this->comment->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.content,a.agreenum,a.date,b.username,b.avatar')->where(array('themeid'=>$oneTopic['id'],'a.type'=>2))->select();
			foreach($list as $value){
				if(strpos($admireids,$value['id']) !== false){
					$value['isadmire']=1;
				}else{
					$value['isadmire']=0;
				}
				$commentlist[]=$value;
			}
			$oneTopic['comment']=$commentlist;
			$this->apiReturn(200,'返回帖子信息成功',$oneTopic);
		}else{
			$this->apiReturn(401,'暂无数据');
		}
	}
	public function userTopicList(){
		$data['uid']=I('param.uid');
		$total=$this->topic->where($data)->select();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$topiclist=$this->topic->field('id,title,tags,thumbpic,commentnum,hits,date')->where($data)->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
		if($topiclist){
			$this->apiReturn(200,'返回帖子列表成功',$topiclist);
		}else{
			$this->apiReturn(401,'暂无帖子数据');
		}
	}
}