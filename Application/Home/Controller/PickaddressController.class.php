<?php
namespace Home\Controller;
use Think\Controller;

class PickaddressController extends Controller{
	private $pick=null;
	public function __construct(){
		parent::__construct();
		$this->pick=D('Pickaddress');
	}
	public function indexApi(){
		$data=I('param.');
		if(isset($data['uid'])){
			$picklist=$this->pick->field('id,name,phone,place,isdefault')->where($data)->select();
			if($picklist){
				$this->apiReturn(200,'返回用户收获地址列表成功',$picklist);
			}else{
				$this->apiReturn(404,'暂无用户收获地址信息');
			}
		}else{
			$this->apiReturn(401,'参数错误，必须传入当前登陆用户ID');
		}
	}
	public function addApi(){
		$data=I('param.');
		if($this->pick->create($data)){
			if($insertid=$this->pick->add()){
				if($data['isdefault'] == 1){
					$map['uid']=array('eq',$data['uid']);
					$map['id']=array('neq',$insertid);
					$this->pick->where($map)->setField('isdefault',0);
				}
				$this->apiReturn(200,'添加收货地址成功');
			}else{
				$this->apiReturn(404,'添加收货地址失败');
			}
		}else{
			$this->apiReturn(401,$this->pick->getError());
		}
	}
	public function delApi(){
		$data['id']=I('param.id');
		if(isset($data['id'])){
			if($this->pick->delete($data['id'])){
				$this->apiReturn(200,'删除成功');
			}else{
				$this->apiReturn(404,'删除失败');
			}
		}else{
			$this->apiReturn(401,'参数错误，需传入收货地址ID');
		}
	}
}