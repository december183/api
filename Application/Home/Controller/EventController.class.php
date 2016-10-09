<?php
namespace Home\Controller;
use Think\Controller;

class EventController extends ComController{
	private $event=null;
	public function __construct(){
		parent::__construct();
		$this->event=D('Event');
	}
	public function eventList(){
		$data=I('param.');
		$map['cateid']=$data['cateid'];
		$map['cateid']=array('IN',$this->category->getDelIds($data['cateid']));
		$page=$data['page'] ? $data['page'] : 1;
		$map['p']=array('eq',$page);
		$total=$this->event->where($map)->count();
		$page=new \Think\Page($total,FRONT_PAGE_SIZE);
		$show=$page->show();
		$list=$this->event->field('id,thumbpic,title,price,location')->where($map)->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
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
			$this->apiNotice(401,'暂无数据');
		}
	}
	public function releaseEvent(){
		$data=I('param.');
		if($_FILES['file']){
            $res=$this->uploadPic();
            if(is_array($res)){
            	array_merge($data,$res);
            }
        }else{
            $this->apiNotice(402,'请上传商品主图');
        }
		if($this->event->create($data)){
			if($this->event->add()){
				$this->apiNotice(200,'活动发布成功');
			}else{
				$this->apiNotice(404,'活动发布失败');
			}
		}else{
			$this->apiNotice(403,$this->event->getError());
		}
	}
	public function editEvent(){
		$data=I('param.');
		if($_FILES['file']){
            $res=$this->uploadPic();
            if(is_array($res)){
            	array_merge($data,$res);
            }
        }
		if($this->event->create($data)){
			if($this->event->save()){
				$this->apiNotice(200,'活动修改成功');
			}else{
				$this->apiNotice(404,'活动修改失败');
			}
		}else{
			$this->apiNotice(403,$this->event->getError());
		}
	}
	public function eventDetail(){
		$data=I('param.');
		$oneEvent=$this->event->where($data)->find();
		if($oneEvent){
			$this->apiReturn(200,'返回活动详情成功',$oneEvent);
		}else{
			$this->apiNotice(404,'暂无该活动信息');
		}
	}
}