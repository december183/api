<?php
namespace Home\Controller;
use Think\Controller;

class EventController extends ComController{
	private $event=null;
	private $collect=null;
	private $category=null;
	private $comment=null;
	private $apply=null;
	private $admire=null;
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->event=D('Event');
		$this->collect=D('Collect');
		$this->category=D('Category');
		$this->comment=D('Comment');
		$this->apply=D('Apply');
		$this->admire=D('Admire');
		$this->user=D('User');
	}
	public function eventList(){
		$data=I('param.');
		if(isset($data['cateid'])){
			$map['cateid']=array('IN',$this->category->getDelIds($data['cateid']));
		}
		if(isset($data['region'])){
			$map['region']=array('eq',$data['region']);
		}
		if(isset($data['type'])){
			if($data['type'] == 1){
				$condition['level']=array('eq',1);
			}else{
				$condition['level']=array('neq',1);
			}
			$userlist=$this->user->field('id')->where($condition)->select();
			foreach($userlist as $value){
				$userids.=$value['id'].',';
			}
			$userids=substr($userids,0,-1);
			$map['uid']=array('in',$userids);
		}
		if(isset($data['isfree'])){
			if($data['isfree']){
				$map['price']=array('eq',0);
			}else{
				$map['price']=array('neq',0);
			}
		}
		$total=$this->event->where($map)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$show=$page->show();
		$list=$this->event->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.starttime,a.endtime,a.thumbpic,a.title,a.price,a.place,a.descript,a.status,b.username')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
		$collectlist=$this->collect->field('eventid')->where(array('uid'=>$data['uid'],'eventid'=>0))->select();
		foreach($collectlist as $value){
			$collectids .= $value['eventid'].',';
		}
		$collectids=substr($collectids,0,-1);
		foreach($list as $value){
			if(strpos($collectids,$value['id']) !== false){
				$value['iscollect']=1;
			}else{
				$value['iscollect']=0;
			}
			$eventlist[]=$value;
		}
		if($eventlist){
			$this->apiReturn(200,'返回活动列表成功',$eventlist);
		}else{
			$this->apiReturn(401,'暂无数据');
		}
	}
	/**
	 * [releaseEvent IOS发布活动]
	 * @return [type] [description]
	 */
	public function releaseEvent(){
		$data=I('param.');
		if($_FILES['file']){
            $res=$this->uploadPic();
            if(is_array($res)){
            	array_merge($data,$res);
            }
        }else{
            $this->apiReturn(402,'请上传商品主图');
        }
		if($this->event->create($data)){
			if($this->event->add()){
				$this->apiReturn(200,'活动发布成功');
			}else{
				$this->apiReturn(404,'活动发布失败');
			}
		}else{
			$this->apiReturn(403,$this->event->getError());
		}
	}
	/**
	 * [addEvent Andriod发布活动]
	 */
	public function addEvent(){
		$data=I('param.');
		if($this->event->create($data)){
			if($this->event->add()){
				$this->apiReturn(200,'活动发布成功');
			}else{
				$this->apiReturn(404,'活动发布失败');
			}
		}else{
			$this->apiReturn(403,$this->event->getError());
		}
	}
	public function editEvent(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['file']){
	            $res=$this->uploadPic();
	            if(is_array($res)){
	            	array_merge($data,$res);
	            }
	        }
			if($this->event->create($data)){
				if($this->event->save()){
					$this->apiReturn(200,'活动修改成功');
				}else{
					$this->apiReturn(404,'活动修改失败');
				}
			}else{
				$this->apiReturn(403,$this->event->getError());
			}
		}else{
			$data=I('get.');
			$oneEvent=$this->event->where(array('id'=>$data['id']))->find();
			if($oneEvent){
				if($oneEvent['uid'] == $data['uid']){
					$this->apiReturn(200,'返回活动信息成功',$oneEvent);
				}else{
					$this->apiReturn(402,'无权限进行此操作');
				}
			}else{
				$this->apiReturn(404,'未找到该活动信息');
			}
		}
	}
	public function eventDetail(){
		$data=I('param.');
		$map['a.id']=$data['id'];
		$oneEvent=$this->event->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.title,a.thumbpic,a.content,a.starttime,a.endtime,a.status,a.date,a.place,a.price,a.paymethod,a.payaccount,b.username,b.avatar,b.momstatus,b.birthday')->where($map)->find();
		if($oneEvent){
			$oneCollect=$this->collect->where(array('eventid'=>$data['id'],'uid'=>$data['uid']))->find();
			if($oneCollect){
				$oneEvent['iscollect']=1;
			}else{
				$oneEvent['iscollect']=0;
			}
			if($oneEvent['status'] == 1){
				$oneApply=$this->apply->field('id')->where(array('eventid'=>$oneEvent['id'],'uid'=>$data['uid']))->find();
				if($oneApply){
					$oneEvent['status']=1;
				}else{
					$applylist=$this->apply->field('id,num')->where(array('eventid'=>$oneEvent['id']))->select();
					foreach($applylist as $value){
						$applynum+=$applylist['num'];
					}
					if($applynum < $oneEvent['maxnum']){
						$oneEvent['status']=2;
					}else{
						$oneEvent['status']=3;
					}
				}
			}
			$admirelist=$this->admire->field('commentid')->where(array('uid'=>$data['uid'],'serviceid'=>0))->select();
			foreach($admirelist as $value){
				$admireids .= $value['commentid'].',';
			}
			$admireids=substr($admireids,0,-1);
			$total=$this->comment->where(array('themeid'=>$oneEvent['id'],'type'=>1))->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$list=$this->comment->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.content,a.agreenum,a.date,b.username,b.avatar,b.momstatus,b.birthday')->where(array('themeid'=>$oneEvent['id'],'a.type'=>1))->order('a.date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			$commentlist=array();
			foreach($list as $value){
				if(strpos($admireids,$value['id']) !== false){
					$value['isadmire']=1;
				}else{
					$value['isadmire']=0;
				}
				$commentlist[]=$value;
			}
			$oneEvent['comment']=$commentlist;
			$this->apiReturn(200,'返回活动详情成功',$oneEvent);
		}else{
			$this->apiReturn(404,'暂无该活动信息');
		}
	}
	public function userEventList(){
		$data['uid']=I('param.uid');
		$total=$this->event->where($data)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$show=$page->show();
		$eventlist=$this->event->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.starttime,a.endtime,a.thumbpic,a.title,a.price,a.place,a.descript,a.status,b.username')->where($data)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
		if($eventlist){
			$this->apiReturn(200,'返回会员活动列表成功',$eventlist);
		}else{
			$this->apiReturn(401,'暂无会员活动数据');
		}
	}
	public function userEventDetail(){
		$data=I('param.');
		$map['a.id']=$data['id'];
		$oneEvent=$this->event->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.title,a.thumbpic,a.content,a.starttime,a.endtime,a.status,a.date,a.place,a.price,a.paymethod,a.payaccount,b.username,b.avatar,b.momstatus,b.birthday')->where($map)->find();
		if($oneEvent){
			//获取当前活动的报名信息
			$applylist=$this->apply->alias('a')->join('app_user as b ON a.uid=b.id')->field('b.username,b.avatar')->where(array('eventid'=>$oneEvent['id']))->select();
			$oneEvent['apply']=$applylist;
			//获取登陆用户的评论点赞
			$admirelist=$this->admire->field('commentid')->where(array('uid'=>$data['uid'],'serviceid'=>0))->select();
			foreach($admirelist as $value){
				$admireids .= $value['commentid'].',';
			}
			$admireids=substr($admireids,0,-1);
			//获取当前活动的评论列表
			$total=$this->comment->where(array('themeid'=>$oneEvent['id'],'a.type'=>1))->count();
			$page=new \Think\Page($total,FRONT_PAGE_SIZE);
			$list=$this->comment->alias('a')->join('app_user as b ON a.uid=b.id')->field('a.id,a.content,a.agreenum,a.date,b.username,b.avatar,b.momstatus,b.birthday')->where(array('themeid'=>$oneEvent['id'],'a.type'=>1))->order('date DESC')->limit($page->firstRow.','.$page->listRows)->select();
			foreach($list as $value){
				if(strpos($admireids,$value['id']) !== false){
					$value['isadmire']=1;
				}else{
					$value['isadmire']=0;
				}
				$commentlist[]=$value;
			}
			$oneEvent['comment']=$commentlist;
			$this->apiReturn(200,'返回会员活动详情成功',$oneEvent);
		}else{
			$this->apiReturn(404,'暂无该会员活动信息');
		}
	}
	public function deleteEvent(){
		$data=I('param.');
		$oneEvent=$this->event->field('id,uid')->where(array('id'=>$data['id']))->find();
		if($oneEvent){
			if($data['uid'] == $oneEvent['uid']){
				if($this->event->delete($oneEvent['id'])){
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
	/**
	 * [cronApi 每6个小时执行一次，改变活动状态]
	 * @return [type] [description]
	 */
	public function cronApi(){
		$time=time();
		$map['status']=array('neq',5);
		$list=$this->event->field('id,starttime,endtime')->where($map)->select();
		foreach($list as $value){
			if($time > strtotime($value['starttime']) && $time < strtotime($value['endtime'])){
				$data['id']=$value['id'];
				$data['status']=4;
			}elseif($time > strtotime($value['endtime'])){
				$data['id']=$value['id'];
				$data['status']=5;
			}
			$this->event->save($data);
		}
	}
}