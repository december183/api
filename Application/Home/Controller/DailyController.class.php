<?php
namespace Home\Controller;
use Think\Controller;

class DailyController extends Controller{
	private $daily=null;
	private $user=null;
	private $task=null;
	public function __construct(){
		parent::__construct();
		$this->daily=D('Daily');
		$this->user=D('User');
		$this->task=D('Task');
	}
	public function indexApi(){
		$data['uid']=I('param.uid');
		if(isset($data['uid'])){
			$tasklist=$this->task->field('id,name,point')->where(array('status'=>1))->order('sort ASC')->select();
			$today=date('Y-m-d',time());
			$oneDaily=$this->daily->field('date,point,issigned,ispost')->where($data)->order('date DESC')->limit(1)->select();
			$date=date('Y-m-d',$oneDaily[0]['date']);
			if($date == $today){
				$data=array('daily'=>$oneDaily[0],'task'=>$tasklist);
			}else{
				$todayDaily=array('point'=>0,'issigned'=>0,'ispost'=>0);
				$data=array('daily'=>$todayDaily,'task'=>$tasklist);
			}
				$this->apiReturn(200,'返回会员任务信息成功',$data);
		}else{
			$this->apiReturn(401,'参数错误，需传入用户ID');
		}
	}
	public function signApi(){
		$data['uid']=I('param.uid');
		if(isset($data['uid'])){
			$oneDaily=$this->daily->where($data)->order('date DESC')->limit(1)->select();
			$date=date('Y-m-d',$oneDaily[0]['date']);
			$today=date('Y-m-d',time());
			if($date == $today){
				if($oneDaily[0]['issigned'] == 0){
					$data2['id']=$oneDaily[0]['id'];
					$data2['issigned']=1;
					$data2['point']=$oneDaily[0]['point']+2;
					if($this->daily->save($data2)){
						$this->user->where(array('id'=>$data['uid']))->setInc('credit',2);
						$this->apiReturn(200,'签到成功',array('point'=>2));
					}else{
						$this->apiReturn(404,'签到失败');
					}
				}else{
					$this->apiReturn(402,'你已签到');
				}
			}else{
				$data['issigned']=1;
				$data['ispost']=0;
				$data['date']=time();
				if(strtotime($today)-strtotime($date) > 86400){
					$data['signcount']=1;
					$data['point']=2;
				}else{
					if($oneDaily[0]['signcount'] == 29){
						$data['point']=3+7;
						$data['signcount']=0;
					}elseif($oneDaily[0]['signcount'] < 29 && $oneDaily[0]['signcount'] > 6){
						$data['point']=3;
						$data['signcount']=$oneDaily[0]['signcount']+1;
					}elseif($oneDaily[0]['signcount'] == 6){
						$data['point']=2+3;
						$data['signcount']=$oneDaily[0]['signcount']+1;
					}elseif($oneDaily[0]['signcount'] < 6){
						$data['point']=2;
						$data['signcount']=$oneDaily[0]['signcount']+1;
					}
				}
				if($this->daily->add($data)){
					$this->user->where(array('id'=>$data['uid']))->setInc('credit',$data['point']);
					$this->apiReturn(200,'签到成功',array('point'=>$data['point']));
				}else{
					$this->apiReturn(404,'签到失败');
				}
			}
		}else{
			$this->apiReturn(401,'参数错误，需传入用户ID');
		}
	}
}