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
		$data['id']=I('param.id');
		$oneUser=$this->user->field('avatar,level,username,momstatus,birthday,account')->where($data)->find();
		if($oneUser){
			$this->apiReturn(200,'返回登陆用户信息成功',$oneUser);
		}else{
			$this->apiReturn(404,'获取登陆用户信息失败');
		}
	}
	/**
	 * [filledUserInfo 完善用户信息]
	 * @return [type] [description]
	 */
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
	/**
	 * [isFilledUserInfo 查询是否完善用户信息]
	 * @return boolean [description]
	 */
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
	/**
	 * [getUserInfo 获取用户信息]
	 * @return [type] [description]
	 */
	public function getUserInfo(){
		$data=I('param.');
		$oneUser=$this->user->where($data)->find();
		if($oneUser){
			$this->apiReturn(200,'获取用户信息成功',$oneUser);
		}else{
			$this->apiReturn(400,'未找到此用户');
		}
	}
	/**
	 * [editUserInfo 修改用户信息]
	 * @return [type] [description]
	 */
	public function editUserInfo(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['file']){
				$data['avatar']=$this->upAvatar();
			}
			if($this->user->create($data)){
				if($this->user->save()){
					$this->apiReturn(200,'修改成功');
				}else{
					$this->apiReturn(404,'修改失败');
				}
			}else{
				$this->apiReturn(402,$this->user->getError());
			}
		}else{
			$data['id']=I('get.id');
			if(isset($data)){
				$oneUser=$this->user->field('id,avatar,username,realname,shopname,shopdescript,shopbanner,phone,tel,address,location,weichat,alipay,account,classes,agerange,momstatus,duedate,gender,birthday')->where($data)->find();
				if($oneUser){
					$this->apiReturn(200,'获取用户信息成功',$oneUser);
				}else{
					$this->apiReturn(404,'未找到此用户');
				}
			}else{
				$this->apiReturn(401,'参数错误，需传入用户ID');
			}
		}
	}
	/**
	 * [editPass 验证手机]
	 * @return [type] [description]
	 */
	public function checkPhone(){
		$data=I('param.');
		$data['type']=2;
        if($data['mscode'] == $_SESSION[$data['phone'].$data['type']]){
        	unset($_SESSION[$data['phone'].$data['type']]);
        	$oneUser=$this->user->field('id,phone')->where(array('phone'=>$data['phone']))->find();
        	if($oneUser){
	            $this->apiReturn(200,'手机验证成功',$oneUser);
        	}else{
        		$this->apiReturn(403,'未找到该用户');
        	}
        }else{
            $this->apiReturn(402,'短信验证码失败！');
        }
	}
	/**
	 * [editPass 修改密码]
	 * @return [type] [description]
	 */
	public function editPass(){
		$data=I('param.');
		if($data=$this->user->create($data)){
			$data['pass']=password($data['pass']);
			if($this->user->save($data)){
				$this->apiReturn(200,'密码修改成功');
			}else{
				$this->apiReturn(404,'密码修改失败');
			}
		}else{
			$this->apiReturn(401,$this->user->getError());
		}
	}
	/**
	 * [applySeller 申请商家]
	 * @return [type] [description]
	 */
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