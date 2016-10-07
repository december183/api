<?php
namespace Home\Controller;
use Think\Controller;

class ServiceController extends Controller{
	private $service=null;
	public function __construct(){
		parent::__construct();
		$this->service=D('Service');
	}
	public function releaseService(){
		$data=I('param.');
		if($_FILES['file']){
			$arr=$this->upload();
			foreach($arr as $key=>$path){
				$
				$imgArr=getimagesize($path);
				if($imgArr[0] < 800 && $imgArr[1] < 800){
					$path=str_replace('\\', '/',$path);
					$data['mainnail'].=strstr($path,__ROOT__.'/Uploads/image/').';';
				}else{
					$data['mainnail'].=$this->thumb($path).';';
				}
				if($key == 0){
					$data['thumbpic']=$this->thumb($path,100,100);
				}
			}
			$data['mainpic']=substr($data['mainpic'],0,-1);
		}else{
			$this->apiNotice(402,'请上传商品主图');
		}
		if($this->service->create($data)){
			if($this->service->add()){
				$this->apiNotice(200,'商品发布成功');
			}else{
				$this->apiNotice(400,'商品发布失败');
			}
		}else{
			$this->apiNotice(401,$this->service->getError());
		}
	}
	public function editService(){
		
	}
}