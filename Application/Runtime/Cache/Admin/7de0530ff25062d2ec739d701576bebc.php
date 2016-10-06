<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>修改密码</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        
        <!-- jQuery AND jQueryUI -->
        <script type="text/javascript" src="/api/Public/Admin/js/libs/jquery/1.6/jquery.min.js"></script>
        <script type="text/javascript" src="/api/Public/Admin/js/libs/jqueryui/1.8.13/jquery-ui.min.js"></script>
        <!--  -->
        <link rel="stylesheet" href="/api/Public/Admin/css/min.css" />
        <script type="text/javascript" src="/api/Public/Admin/js/min.js"></script>
    </head>
    <body>
        
        <script type="text/javascript" src="/api/Public/Admin/content/settings/main.js"></script>
        <link rel="stylesheet" href="/api/Public/Admin/content/settings/style.css" />
       
        <!--              
                HEAD
                        --> 
        <div id="head">
    <div class="left">
        <a href="#" class="button profile"><img src="/api/Public/Admin/img/icons/top/huser.png" alt="" /></a>
        你好, 
        <a href="#"><?php echo ($user["username"]); ?></a>
        |
        <a href="/api/index.php?s=/Admin/Manage/logout">退出</a>
    </div>
</div>                
                
        <!--            
                SIDEBAR
                         --> 
        <div id="sidebar">
    <ul>
        <li>
            <a href="/api/index.php?s=/Admin/Index/index">
                <img src="/api/Public/Admin/img/icons/menu/inbox.png" alt="" />
                仪表盘
            </a>
        </li>
        <?php if(is_array($menulist)): $i = 0; $__LIST__ = $menulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(in_array(($vo['id']), is_array($userAuth)?$userAuth:explode(',',$userAuth))): if($curAuth['pid'] == $vo['id']): ?><li class="<?php echo ($vo["icon"]); ?> current"><?php else: ?><li class="<?php echo ($vo["icon"]); ?>"><?php endif; ?>
                <?php if($vo['url'] != ''): ?><a href="<?php echo ($vo["url"]); ?>"> <?php echo ($vo["name"]); ?></a><?php else: ?><a href="javascript:;"> <?php echo ($vo["name"]); ?></a><?php endif; ?>
                <ul>
                    <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i; if(in_array(($voo['id']), is_array($userAuth)?$userAuth:explode(',',$userAuth))): ?><li <?php if($curAuth['url'] == $voo['url']): ?>class="current"<?php endif; ?>><a href="/api/index.php?s=/Admin/<?php echo ($voo["url"]); ?>"><?php echo ($voo["name"]); ?></a></li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
                </ul>
            </li><?php endif; endforeach; endif; else: echo "" ;endif; ?>
    </ul>
</div>

        <!--            
              CONTENT 
                        --> 
        
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 修改密码</h1>
        <div class="bloc">
		    <div class="title"><a href="JavaScript:;">修改密码</a></div>
		    <form method="post" name="edit" id="edit" enctype="multipart/form-data">
		    <input type="hidden" name="flag" id="flag" value="0" />
		    <div class="content">
		    	<div class="input">会员名称：<?php echo ($oneManage["username"]); ?></div>
		    	<div class="input">原　密码：<input type="password" name="oldpass" value="" onblur="checkPass(this);" /> <em class="red">*</em><span class="oldpass">原密码必须填写</span></div>
		    	<div class="input">新　密码：<input type="password" name="userpass" value="" /> <em class="red">*</em>留空则不修改</div>
		    	<div class="input">确认密码：<input type="password" name="ckuserpass" value="" /> <em class="red">*</em>必须与新密码一致</div>
		    	<div class="input">用户邮箱：<input type="text" name="email" value="<?php echo ($oneManage["email"]); ?>" /></div>
	    		<div class="input">用户手机：<input type="text" name="phone" value="<?php echo ($oneManage["phone"]); ?>" /></div>
		    	<div class="input">
		            会员头像：<input type="file" name="pic" /> <img src="<?php echo ($oneManage["head_img"]); ?>" class="thumb" alt="<?php echo ($oneManage["username"]); ?>" />
		        </div>
		        <div class="input textarea">
		            <span class="middle">签名备注：</span><textarea name="remark" rows="3" cols="5"><?php echo ($oneManage["remark"]); ?></textarea>
		        </div>
		        <div class="submit">
		            <input type="submit" value="提交" onclick="return checkEditForm();" />
		            <input type="reset" value="重置" class="white"/>
		        </div>
		    </div>
		    </form>
	   	</div>
	</div>
	<script type="text/javascript">
		function checkPass(target){
	        var $target=$(target);
	        var pass=$target.val();
	        $.ajax({
	            url:'/api/index.php?s=/Admin/Manage/checkPass',
	            data:{"userpass":pass},
	            type:'post',
	            dataType:'json',
	            success:function(response){
	                if(response.errno == 1){
	                    $('#flag').val('0');
	                    $('.oldpass').remove();
	                    if($target.parent().find('span.success').text().length != 0){
	                        $target.parent().find('span.success').remove();
	                    }
	                    if($target.parent().find('span.error').text().length == 0){
	                        $target.parent().append('<span class="error">'+response.errmsg+'</span>');
	                    }
	                }else{
	                    $('#flag').val('1');
	                    $('.oldpass').remove();
	                    if($target.parent().find('span.error').text().length != 0){
	                        $target.parent().find('span.error').remove();
	                    }
	                    if($target.parent().find('span.success').text().length == 0){
	                        $target.parent().append('<span class="success">'+response.errmsg+'</span>');
	                    }
	                }
	            }
	        });
	    }
	    function checkEditForm(){
	    	var fm = document.getElementById('edit');
	    	var flag = document.getElementById('flag');
	    	if(fm.oldpass.value == ''){
	    		alert('原密码必须填写！');
	    		fm.oldpass.focus();
	    		return false;
	    	}
	    	if(flag.value == 0){
	    		alert('原密码不正确！');
	    		fm.oldpass.focus();
	    		return false;
	    	}
	    	if(fm.userpass.value != ''){
	    		if(fm.userpass.value != fm.ckuserpass.value){
	    			alert('密码与确认密码必须一致！');
	    			fm.ckuserpass.focus();
	    			return false;
	    		}
	    	}
	    	return true;
	    }
	</script>

        
    </body>
</html>