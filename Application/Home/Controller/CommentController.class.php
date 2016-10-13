<?php
namespace Home\Controller;
use Think\Controller;

class CommentController extends Controller{
	private $comment=null;
	private $event=null;
	private $topic=null;
	public function __construct(){
		parent::__construct();
		$this->comment=D('Comment');
		$this->topic=D('Topic');
	}
	public function addApi(){
		$data=I('param.');
		if($this->comment->create($data)){
			if($this->comment->add()){
				if($data['type'] == 1){
					$this->event->where(array('id'=>$data['themeid']))->setInc('commentnum');
				}elseif($data['type'] == 2){
					$this->topic->where(array('id'=>$data['themeid']))->setInc('commentnum');
				}
				$this->apiReturn(200,'发布评论成功');
			}else{
				$this->apiReturn(404,'发布评论失败');
			}
		}else{
			$this->apiReturn(401,$this->comment->getError());
		}
	}

}