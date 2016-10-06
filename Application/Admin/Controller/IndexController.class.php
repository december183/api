<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function index(){
        $this->display();
    }
    public function dashboard(){
    	$this->display();
    }
    public function form(){
    	$this->display('forms');
    }
    public function table(){
    	$this->display();
    }
    public function tabs(){
    	$this->display();
    }
    public function gallery(){
    	$this->display();
    }
    public function notify(){
    	$this->display();
    }
    public function charts(){
    	$this->display();
    }
    public function typography(){
    	$this->display();
    }
    public function icons(){
    	$this->display();
    }
    public function calendar(){
    	$this->display();
    }
}