<?php
namespace Home\Controller;
use Think\Controller;

class UserController extends ComController{
	private $user=null;
	public function __construct(){
		parent::__construct();
		$this->user=D('User');
	}
	public function indexApi(){
		$data['id']=I('param.uid');
		$oneUser=$this->user->field('avatar,level,birthday')->where($data)->find();
		if($oneUser){
			$this->apiReturn(200,'返回登陆用户信息成功',$oneUser);
		}else{
			$this->apiReturn(404,'获取登陆用户信息失败');
		}
	}
	public function filledUserInfo(){
		$data=I('param.');
		$data['isfilled']=1;
		if($this->user->create($data)){
			if($this->user->save()){
				$this->apiReturn(200,'完善用户信息成功');
			}else{
				$this->apiReturn(400,'完善用户信息失败');
			}
		}else{
			$this->apiReturn(401,$this->user->getError());
		}
	}
	public function isFilledUserInfo(){
		$data=I('param.');
		$oneUser=$this->user->field('id,isfilled')->where($data)->find();
		if($oneUser){
			if($oneUser['isfilled']){
				$this->apiReturn(200,'用户信息已完善',$oneUser);
			}else{
				$this->apiReturn(201,'用户信息未完善',$oneUser);
			}
		}else{
			$this->apiReturn(400,'未找到此用户');
		}
	}
	public function getUserInfo(){
		$data=I('param.');
		$oneUser=$this->user->where($data)->find();
		if($oneUser){
			$this->apiReturn(200,'获取用户信息成功',$oneUser);
		}else{
			$this->apiReturn(400,'未找到此用户');
		}
	}
	public function applySeller(){
		$data=I('param.');
		if(isset($data['id'])){
			$oneUser=$this->user->where(array('id'=>$data['id']))->find();
			if($oneUser){
				if($_FILES['file']){
		            $arr=$this->upload();
			        foreach($arr as $key=>$path){
			            $imgArr=getimagesize($path);
			            if($imgArr[0] < 800 && $imgArr[1] < 800){
			                $path=str_replace('\\', '/',$path);
			                $data['licence'].=strstr($path,__ROOT__.'/Uploads/image/').';';
			            }else{
			                $data['licence'].=$this->thumb($path).';';
			            }
			        }
			        $data['licence']=substr($data['licence'],0,-1);
		        }else{
		            $this->apiReturn(402,'请上传商家营业执照');
		        }
				if($data=$this->user->create($data)){
					if($this->user->save()){
						$this->apiReturn(200,'提交申请成功');
					}else{
						$this->apiReturn(404,'提交申请失败');
					}
				}else{
					$this->apiReturn(403,$this->user->getError());
				}
			}else{
				$this->apiReturn(401,'未找到此用户');
			}
		}else{
			$this->apiReturn(400,'参数错误');
		}
	}
}