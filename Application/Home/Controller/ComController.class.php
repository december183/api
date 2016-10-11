<?php
namespace Home\Controller;
use Think\Controller;

class ComController extends Controller{
	public function upload(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->upload();
        if(!$info){
            $this->apiReturn(401,$upload->getError());
        }else{
        	foreach($info as $file){
        		$pathArr[] = APP_ROOT.'/Uploads/image/'.$file['savepath'].$file['savename'];
        	}
            return $pathArr;
        }
    }
    public function thumb($path,$width=800,$height=800){
        $image=new \Think\Image();
    	$image->open($path);
        $_start=substr($path,0,-strlen(strrchr($path,'.')));
        $_end=strrchr($path,'.');
        $thumb_path=$_start.$width.'x'.$height.'_thumb'.$_end;
        $image->thumb($width,$height)->save($thumb_path);
        $thumb_path=str_replace('\\', '/', $thumb_path);
        return strstr($thumb_path,__ROOT__.'/Uploads/image/');
    }
    public function uploadMainPic(){
        if($_FILES['file']){
            //$this->apiReturn('101','调试',$_FILES);
            $arr=$this->upload();
            foreach($arr as $key=>$path){
                $imgArr=getimagesize($path);
                if($imgArr[0] < 800 && $imgArr[1] < 800){
                    $path=str_replace('\\', '/',$path);
                    $data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
                }else{
                    $data['mainpic'].=$this->thumb($path).';';
                }
                if($key == 0){
                    $data['thumbpic']=$this->thumb($path,100,100);
                }
            }
            $data['mainpic']=substr($data['mainpic'],0,-1);
            $this->apiReturn(200,'图片上传成功',$data);
        }else{
            $this->apiReturn(402,'请上传商品主图');
        }
    }
    public function uploadPic(){
        $arr=$this->upload();
        foreach($arr as $key=>$path){
            $imgArr=getimagesize($path);
            if($imgArr[0] < 800 && $imgArr[1] < 800){
                $path=str_replace('\\', '/',$path);
                $data['mainpic'].=strstr($path,__ROOT__.'/Uploads/image/').';';
            }else{
                $data['mainpic'].=$this->thumb($path).';';
            }
            if($key == 0){
                $data['thumbpic']=$this->thumb($path,100,100);
            }
        }
        $data['mainpic']=substr($data['mainpic'],0,-1);
        return $data;
    }
}