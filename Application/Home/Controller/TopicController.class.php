<?php
namespace Home\Controller;
use Think\Controller;

class TopicController extends ComController{
	private $topic=null;
	private $category=null;
	private $comment=null;
	private $admire=null;
	private $user=null;
	private $daily=null;
	public function __construct(){
		parent::__construct();
		$this->topic=D('Topic');
		$this->category=D('Category');
		$this->comment=D('Comment');
		$this->admire=D('Admire');
		$this->user=D('User');
		$this->daily=D('Daily');
	}
	public function topicList(){
		$data=I('param.');
		if(isset($data['cateid'])){
			$map['cateid']=array('in',$this->category->getDelIds($data['cateid']));
		}
		
		if(isset($data['keywords'])){
			$keywords=urlencode($data['keywords']);
			if(!empty($keywords)){
				$map['title']=array('like','%'.$keywords.'%');
			}
		}
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
	/**
	 * [releaseTopic IOS发布帖子]
	 * @return [type] [description]
	 */
	public function releaseTopic(){
		$data=I('param.');
		if($_FILES['file']){
            $res=$this->uploadPic();
            if(is_array($res)){
            	array_merge($data,$res);
            }
        }else{
            $this->apiReturn(402,'请上传帖子主图');
        }
		if($this->topic->create($data)){
			if($this->topic->add()){
				$oneDaily=$this->daily->where(array('uid'=>$data['uid']))->order('date DESC')->limit(1)->select();
				$date=date('Y-m-d',$onedaily[0]['date']);
				$today=date('Y-m-d',time());
				if($date == $today){
					if($onedaily[0]['ispost'] == 0){
						$data2['id']=$onedaily[0]['id'];
						$data2['ispost']=1;
						$data2['point']=$oneDaily[0]['point']+3;
						if($this->daily->save($data2)){
							$this->user->where(array('id'=>$data['uid']))->setInc('credit',3);
						}
					}
				}else{
					$data2['uid']=$data['uid'];
					$data2['ispost']=1;
					$data2['point']=3;
					$data2['date']=time();
					if($this->daily->add($data2)){
						$this->user->where(array('id'=>$data['uid']))->setInc('credit',3);
					}
				}
				if(($date == $today) && ($oneDaily[0]['ispost'] == 1)){
					$this->apiReturn(200,'帖子发布成功');
				}else{
					$this->apiReturn(200,'帖子发布成功',array('point'=>3));
				}
			}else{
				$this->apiReturn(404,'帖子发布失败');
			}
		}else{
			$this->apiReturn(403,$this->topic->getError());
		}
	}
	/**
	 * [addTopic Andriod上传帖子]
	 */
	public function addTopic(){
		$data=I('param.');
		if($this->topic->create($data)){
			if($this->topic->add()){
				$oneDaily=$this->daily->where(array('uid'=>$data['uid']))->order('date DESC')->limit(1)->select();
				$date=date('Y-m-d',$onedaily[0]['date']);
				$today=date('Y-m-d',time());
				if($date == $today){
					if($onedaily[0]['ispost'] == 0){
						$data2['id']=$onedaily[0][0]['id'];
						$data2['ispost']=1;
						$data2['point']=$oneDaily[0][0]['point']+3;
						if($this->daily->save($data2)){
							$this->user->where(array('id'=>$data['uid']))->setInc('credit',3);
						}
					}
				}else{
					$data2['uid']=$data['uid'];
					$data2['ispost']=1;
					$data2['point']=3;
					$data2['date']=time();
					if($this->daily->add($data2)){
						$this->user->where(array('id'=>$data['uid']))->setInc('credit',3);
					}
				}
				if(($date == $today) && ($oneDaily[0]['ispost'] == 1)){
					$this->apiReturn(200,'帖子发布成功');
				}else{
					$this->apiReturn(200,'帖子发布成功',array('point'=>3));
				}
			}else{
				$this->apiReturn(404,'帖子发布失败');
			}
		}else{
			$this->apiReturn(403,$this->topic->getError());
		}
	}
	public function editTopic(){
		$data=I('param.');
		if($_FILES['file']){
            $res=$this->uploadPic();
            if(is_array($res)){
            	array_merge($data,$res);
            }
        }
		if($this->topic->create($data)){
			if($this->topic->save()){
				$this->apiReturn(200,'帖子修改成功');
			}else{
				$this->apiReturn(404,'帖子修改失败');
			}
		}else{
			$this->apiReturn(403,$this->topic->getError());
		}
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
			$total=$this->comment->where(array('themeid'=>$oneTopic['id'],'type'=>2))->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$list=$this->comment->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.content,a.agreenum,a.date,b.username,b.avatar')->where(array('themeid'=>$oneTopic['id'],'a.type'=>2))->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$commentlist=array();
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
	public function deleteTopic(){
		$data=I('param.');
		$oneTopic=$this->topic->field('id,uid')->where(array('id'=>$data['id']))->find();
		if($oneTopic){
			if($data['uid'] == $oneTopic['uid']){
				if($this->topic->delete($oneTopic['id'])){
					$this->apiReturn(200,'删除成功');
				}else{
					$this->apiReturn(404,'删除失败');
				}
			}else{
				$this->apiReturn(402,'无权限进行此操作');
			}
		}else{
			$this->apiReturn(401,'暂无该帖子信息');
		}
	}
}