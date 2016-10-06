<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>用户管理-添加用户</title>
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
    <div class="right">
        <form action="" method="post" id="search" class="search placeholder">
            <label>请输入关键字...</label>
            <input type="hidden" name="action" value="search" />
            <input type="text" value="" name="q" class="text" />
            <input type="submit" value="rechercher" class="submit" />
        </form>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 用户管理-添加用户</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Manage/index">用户列表</a> &gt; <a href="/api/index.php?s=/Admin/Manage/add">添加用户</a></div>
		    <form method="post" action="" enctype="multipart/form-data">
		    <div class="content">
	    		<div class="input">用户名称：<input type="text" name="username" /> <em class="red">*</em></div>
	    		<div class="input">用户密码：<input type="password" name="userpass" /> <em class="red">*</em></div>
	    		<div class="input">确认密码：<input type="password" name="ckuserpass" /> <em class="red">*</em></div>
	    		<div class="input">用户邮箱：<input type="text" name="email" /></div>
	    		<div class="input">用户手机：<input type="text" name="phone" /></div>
		        <div class="input">
		            用户　组：
		            <select name="groupid" id="select">
		            <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["groupname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		            </select> <em class="red">*</em>
		        </div>
		        <div class="input">
		            缩略　图：<input type="file" name="pic" /> <em class="red">*</em>
		        </div>
		        <div class="input textarea">
		            <span class="middle">用户备注：</span><textarea name="remark" rows="3" cols="5"></textarea>
		        </div>
		        <div class="input">
		        	用户状态：
		            <input type="radio" id="radio1" name="status"  checked="checked" value="1" /><label for="radio1" class="inline">启用</label>
		            <input type="radio" id="radio2"  name="status" value="0" /><label for="radio2" class="inline">禁用</label>
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