<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>用户组管理-修改用户组</title>
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
        <a href="/api/index.php?s=/Admin/Group/logout">退出</a>
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
        
	<script charset="utf-8" src="/api/Public/kindeditor/kindeditor-min.js"></script>
    <script charset="utf-8" src="/api/Public/kindeditor/lang/zh_CN.js"></script>
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 用户组管理-修改用户组</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Group/index">用户组列表</a> &gt; <a href="/api/index.php?s=/Admin/Group/add">添加用户组</a> &gt; <a href="javascript:;">修改用户组</a></div>
		    <form method="post" action="">
		    <input type="hidden" name="id" value="<?php echo ($oneGroup["id"]); ?>" />
		    <div class="content">
	    		<div class="input">用户组名：<input type="text" name="groupname" value="<?php echo ($oneGroup["groupname"]); ?>" /> <em class="red">*</em></div>
	    		<div class="input">
	    			权限设置：
	    			<div class="pad-left">
		    			<?php if(is_array($authlist)): $i = 0; $__LIST__ = $authlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label><input type="checkbox" name="auth[]" value="<?php echo ($vo["id"]); ?>" <?php if(in_array(($vo['id']), is_array($oneGroup['auth'])?$oneGroup['auth']:explode(',',$oneGroup['auth']))): ?>checked="checked"<?php endif; ?> /><?php echo ($vo["name"]); ?></label><br/>
		    			<?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><label><input type="checkbox" name="auth[]" value="<?php echo ($voo["id"]); ?>" <?php if(in_array(($voo['id']), is_array($oneGroup['auth'])?$oneGroup['auth']:explode(',',$oneGroup['auth']))): ?>checked="checked"<?php endif; ?> /><?php echo ($voo["name"]); ?></label>　<?php endforeach; endif; else: echo "" ;endif; ?><br/><?php endforeach; endif; else: echo "" ;endif; ?>
	    			</div>
	    		</div>
		        <div class="input">
		        	是否启用：
		            <input type="radio" id="radio1" name="status"  checked="checked" value="1" /><label for="radio1" class="inline">是</label>
		            <input type="radio" id="radio2"  name="status" value="0" /><label for="radio2" class="inline">否</label>
		        </div>
		        <div class="submit">
		            <input type="submit" value="提交" />
		            <input type="reset" value="重置" class="white"/>
		        </div>
		    </div>
		    </form>
		</div>
	</div>

        
    </body>
</html>