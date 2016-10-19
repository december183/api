<?php
namespace Admin\Controller;
use Think\Controller;

class UpController extends BaseController{
	private $upload=null;
	public function __construct(){
		parent::__construct();
		$this->upload=new \Think\Upload();
		$this->upload->maxSize=3145728;
        $this->upload->exts=array('jpg','gif','png','jpeg');
        $this->upload->rootPath='./Uploads/image/';
        $this->upload->savePath='';
	}
	public function index(){
		if(IS_POST){
	        $arr=$this->uploads();
        	foreach($arr as $key=>$path){
				if(isset($arr) && is_array($arr)){
		        	foreach($arr as $key=>$path){
						$imgArr=getimagesize($path);
						if($imgArr[0] < 800 && $imgArr[1] < 800){
							$path=str_replace('\\', '/',$path);
							$mainpic.=strstr($path,__ROOT__.'/Uploads/image/').';';
						}else{
							$mainpic.=$this->thumb($path).';';
						}
						if($key == 0){
							$thumbpic=$this->thumb($path,100,100);
						}
					}
					$mainpic=substr($mainpic,0,-1);
					$response=array('errno'=>0,'path'=>array('thumb'=>$thumbpic,'main'=>$mainpic));
		        }else{
		        	$response=array('errno'=>1,'errmsg'=>'上传失败');
		        }
		        $this->ajaxReturn($response,'JSON');
			}
		}else{
			$this->display();
		}
	}
	public function plupload(){
		if(IS_POST){
	        $arr=$this->uploads();
	        if(isset($arr) && is_array($arr)){
	        	foreach($arr as $key=>$path){
					$imgArr=getimagesize($path);
					if($imgArr[0] < 800 && $imgArr[1] < 800){
						$path=str_replace('\\', '/',$path);
						$mainpic.=strstr($path,__ROOT__.'/Uploads/image/').';';
					}else{
						$mainpic.=$this->thumb($path).';';
					}
					if($key == 0){
						$thumbpic=$this->thumb($path,100,100);
					}
				}
				$mainpic=substr($mainpic,0,-1);
				$response=array('errno'=>0,'path'=>array('thumb'=>$thumbpic,'main'=>$mainpic));
	        }else{
	        	$response=array('errno'=>1,'errmsg'=>'上传失败');
	        }
	        $this->ajaxReturn($response,'JSON');
		}else{
			$this->display();
		}
	}
	public function mark(){
		if(IS_POST){
			$data=I('param.');
			if($_FILES['pic']['tmp_name']){
				$info=$this->up();
				if(is_array($info)){
					$path=APP_ROOT.'/Uploads/image/'.$info[0];
					$imgArr=getimagesize($path);
					if($imgArr[0] < $data['width'] && $imgArr[1] < $data['height']){
						$path=str_replace('\\', '/',$path);
						$data['mark']=strstr($path,__ROOT__.'/Uploads/image/');
					}else{
						$data['mark']=$this->thumb($path,$data['width'],$data['height']);
					}
					$response=array('errno'=>0,'path'=>$data['mark']);
				}else{
					$response=array('errno'=>1,'errmsg'=>$info);
				}
			}else{
				$response=array('errno'=>2,'errmsg'=>'未选择上传图片');
			}
			$this->ajaxReturn($response,'JSON');
		}else{
			$this->display();
		}
	}
	protected function up(){
		$info=$this->upload->upload();
        if(!$info){
            return $this->error($upload->getError());
        }else{
            foreach($info as $file){
				$path[]=$file['savepath'].$file['savename'];
			}
			return $path;
        }
	}
	/*public function thumb($path,$width=600,$height=600){
        $image=new \Think\Image();
        $image->open($path);
        $_start=substr($path,0,-strlen(strrchr($path,'.')));
        $_end=strrchr($path,'.');
        $thumb_path=$_start.'_thumb'.$_end;
        $image->thumb($width,$height)->save($thumb_path);
        $thumb_path=str_replace('\\', '/', $thumb_path);
        return strstr($thumb_path,__ROOT__.'/Uploads/image/');
    }*/
}