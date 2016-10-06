<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    protected $group=null;
    protected $auth=null;
    protected $user=null;
    protected $article=null;
    protected $adver=null;
    public function _initialize(){
        if(!session('user')){
        	$this->redirect('Login/index');
        }
        $this->group=D('Group');
        $this->auth=D('Auth');
        $this->user=D('Manage');
        $this->article=D('Article');
        $this->adver=D('Adver');
        $userauth=$this->group->field('auth')->where(array('id'=>session('user.groupid')))->find();
        $this->authCheck($userauth['auth']);
        $menulist=$this->auth->getMenu();
        $this->getConfig();
        $this->getCurAuth();
        $this->assign('menulist',$menulist); 
        $this->assign('userAuth',$userauth['auth']);
        $this->assign('user',session('user'));
    }
    public function authCheck($userauth){
        $url=(ACTION_NAME == 'logout') ? ACTION_NAME : CONTROLLER_NAME.'/'.ACTION_NAME;
        if(session('user.groupid') == 1 || in_array($url,C('NOT_AUTH_MODULE'))){
            return true;
        }else{
            if(session('authMenu')){
                $authMenu=session('authMenu');
            }else{
                $authMenu=$this->getAuthUrl($userauth);
            }
            if(in_array($url,$authMenu)){
                return true;
            }else{
                $this->error('你没有此操作权限！');
            }
        }
    }
    public function getCurAuth(){
        $curUrl=CONTROLLER_NAME.'/'.ACTION_NAME;
        $curAuth=$this->auth->field('id,pid,url')->where(array('url'=>$curUrl))->find();
        if($curAuth){
            $this->assign('curAuth',$curAuth);
        }
    }
    public function getConfig(){
        $config=D('Config');
        $configlist=$config->where(array('conf_group'=>2))->select();
        $web_conf=array();
        foreach($configlist as $value){
            $web_conf[$value['name']]=$value['value'];
        }
        C($web_conf);
    }
    protected function getAuthUrl($auth){
        $authArr=explode(',',$auth);
        $authMenu=array();
        foreach($authArr as $value){
            $oneAuth=$this->auth->field('url')->where(array('id'=>$value))->find();
            $authMenu[$value]=$oneAuth['url'];
        }
        session('authMenu',$authMenu);
        return $authMenu;
    }
    public function logout(){
		session('user',null);
        session('authMenu',null);
		$this->redirect('Login/index');
	}
    public function upload(){
        $upload=new \Think\Upload();
        $upload->maxSize=3145728;
        $upload->exts=array('jpg','gif','png','jpeg');
        $upload->rootPath='./Uploads/image/';
        $upload->savePath='';
        $info=$upload->uploadOne($_FILES['pic']);
        if(!$info){
            //echo json_encode(array('errmsg'=>$upload->getError(),'errno'=>0));
            $this->error($upload->getError());
        }else{
            $path=APP_ROOT.'/Uploads/image/'.$info['savepath'].$info['savename'];
            if(C('WEB_IS_PICMARK') == 1){
                $mark=SERVER_ROOT.C('WEB_PICMARK_PATH');
                return $this->mark($path,$mark);
            }else{
                return $path;
            }
        }
    }
    public function thumb($path,$width=600,$height=600){
        $image=new \Think\Image();
        $image->open($path);
        $_start=substr($path,0,-strlen(strrchr($path,'.')));
        $_end=strrchr($path,'.');
        $thumb_path=$_start.'_thumb'.$_end;
        $image->thumb($width,$height)->save($thumb_path);
        $thumb_path=str_replace('\\', '/', $thumb_path);
        return strstr($thumb_path,__ROOT__.'/Uploads/image/');
    }
    public function mark($path,$mark){
        $image=new \Think\Image();
        $_start=substr($path,0,-strlen(strrchr($path,'.')));
        $_end=strrchr($path,'.');
        $water_path=$_start.'_water'.$_end;
        $image->open($path)->water($mark)->save($water_path);
        return $water_path;
    }
    
}