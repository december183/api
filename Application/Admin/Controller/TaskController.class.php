<?php
namespace Admin\Controller;
use Think\Controller;

class TaskController extends BaseController{
	private $task=null;
	public function __construct(){
		parent::__construct();
		$this->task=D('Task');
	}
	public function index(){
		if(IS_POST){
			$data=I('param.');
			$param=I('get.');
			if($param){
				foreach($param as $key=>$value){
					$paramStr.=$key.'/'.$value;
				}
			}
			if($data['action']=='delete'){
				$ids=implode(',',$data['id']);
				if($this->task->delete($ids)){
					$this->success('删除成功！',U('Admin/Task/index/'.$paramStr),2);
				}else{
					$this->error('删除失败！');
				}
			}elseif($data['action']=='sort'){
				foreach($data['sort'] as $key=>$value){
					$sql="UPDATE app_task SET sort='$value' WHERE id='$key'";
					$this->task->execute($sql);
				}
				$this->success('排序成功！',U('Admin/Task/index/'.$paramStr),2);
			}
		}else{
			$total=$this->task->count();
			$page=new \Think\Page($total,PAGE_SIZE);
			$show=$page->show();
			$tasklist=$this->task->order('sort ASC')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('tasklist',$tasklist);
			$this->assign('page',$show);
			$this->display();
		}
	}
	public function add(){
		if(IS_POST){
			$data=I('param.');
			if($data=$this->task->create($data)){
				if($this->task->add($data)){
					$this->success('新增任务成功！',U('Task/index'),2);
				}else{
					$this->error('新增任务失败！');
				}
			}else{
				$this->error($this->task->getError());
			}
		}else{
			$this->display();
		}
	}
	public function edit(){
		if(IS_POST){
			$data=I('param.');
			if($this->task->create($data)){
				if($this->task->save()){
					$this->success('修改任务成功！',U('Task/index'),2);
				}else{
					$this->error('修改任务失败！');
				}
			}else{
				$this->error($this->task->getError());
			}
		}else{
			$id=I('get.id');
			$oneTask=$this->task->where(array('id'=>$id))->find();
			$this->assign('oneTask',$oneTask);
			$this->display();
		}
	}
	public function del(){
		$id=I('get.id');
		if($this->task->delete($id)){
			$this->success('删除成功！',U('Task/index'),2);
		}else{
			$this->error('删除失败！');
		}
	}
	public function setStatus(){
		$data['id']=I('param.id');
		$oneTask=$this->task->field('id,status')->where($data)->find();
		if($oneTask['status'] == 1){
			$data['status'] = 0;
			if($this->task->save($data)){
				$response=array('errno'=>0,'status'=>0);
			}else{
				$response=array('errno'=>1);
			}
		}else{
			$data['status'] = 1;
			if($this->task->save($data)){
				$response=array('errno'=>0,'status'=>1);
			}else{
				$response=array('errno'=>1);
			}
		}
		$this->ajaxReturn($response,'json');
	}}