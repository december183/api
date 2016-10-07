<?php
namespace Home\Controller;
use Think\Controller;

class ComController extends Controllerd{
	public function upload(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->upload();
        if(!$info){
            $this->error($upload->getError());
            $this->apiReturn(400,'图片上传失败',$upload->getError());
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
        $thumb_path=$_start.'_thumb'.$_end;
        $image->thumb($width,$height)->save($thumb_path);
        $thumb_path=str_replace('\\', '/', $thumb_path);
        return strstr($thumb_path,__ROOT__.'/Uploads/image/');
    }
}