<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<title>妈妈应用APP管理后台登录</title> 
<meta content="" name="keywords">
<meta content="" name="description">
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<script src="/api/Public/Admin/js/jquery.min.js" type="text/javascript"></script>
<script src="/api/Public/Admin/js/login.js" type="text/javascript"></script>
<link type="text/css" rel="stylesheet" href="/api/Public/Admin/css/login.css">
</head>
<body>
<h1>妈妈应用APP管理后台登录<sup>V2016</sup></h1>

<div style="margin-top:50px;" class="login">
    
    <div class="header">
        <div id="switch" class="switch"><a tabindex="7" href="javascript:void(0);" id="switch_qlogin" class="switch_btn_focus">快速登录</a>
        </div>
    </div>    
  
    
    <div style="display: block; height: 235px;" id="web_qr_login" class="web_qr_login">    

            <!--登录-->
            <div id="web_login" class="web_login">
               
               
               <div class="login-box">
    
            
			<div class="login_form">
				<form method="post" class="loginForm" id="login_form" accept-charset="utf-8" name="loginform" action="">
                <input type="hidden" value="0" name="did">
                <input type="hidden" value="log" name="to">
                <div id="uinArea" class="uinArea">
                <label for="u" class="input-tips">帐号：</label>
                <div id="uArea" class="inputOuter">
                    
                    <input type="text" class="inputstyle" name="username" id="u">
                </div>
                </div>
                <div id="pwdArea" class="pwdArea">
               <label for="p" class="input-tips">密码：</label> 
               <div id="pArea" class="inputOuter">
                    
                    <input type="password" class="inputstyle" name="userpass" id="p">
                </div>
                </div>
               
                <div style="padding-left:50px;margin-top:20px;"><input type="submit" class="button_blue" style="width:150px;" value="登 录"></div>
              </form>
           </div>
           
            	</div>
               
            </div>
            <!--登录end-->
    </div>

 
</div>
<div class="jianyi">*推荐使用ie8或以上版本ie浏览器或Chrome内核浏览器访问本站</div>
</body></html>