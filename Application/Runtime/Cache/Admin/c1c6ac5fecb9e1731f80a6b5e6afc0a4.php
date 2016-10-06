<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
    <title>网站配置-其他配置</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>

    <link rel="stylesheet" href="/api/Public/Admin/css/min.css" />
    <script type="text/javascript" src="/api/Public/jquery/jquery.min.js"></script>
    <script type="text/javascript" src="/api/Public/Admin/js/nav.js"></script>
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
        <a href="/api/index.php?s=/Admin/Config/logout">退出</a>
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
    
	<script type="text/javascript" src="/api/Public/layer/layer.js"></script>
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 网站配置-其他配置</h1>
        <div class="bloc">
		    <div class="title"><a href="javascript:;">其他配置</a></div>
		    <form method="post" action="">
		    <div class="content">
		    <?php if(is_array($configlist)): $i = 0; $__LIST__ = $configlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; switch($vo['type']): case "1": ?><div class="input"><?php echo ($vo["title"]); ?>：<input type="text" name="<?php echo ($vo["name"]); ?>" value="<?php echo ($vo["value"]); ?>" /> <em class="red">*</em> <?php if($vo['name'] == WEB_PICMARK_PATH): ?><a href="javascript:;" class="button" onclick="openUpload();">点击上传</a><img src="<?php echo ($vo["value"]); ?>" alt="图片水印" class="mark" /><?php endif; ?></div><?php break;?>
	    	<?php case "2": ?><div class="input textarea">
		            <span class="middle"><?php echo ($vo["title"]); ?>：</span><textarea name="<?php echo ($vo["name"]); ?>" rows="3" cols="5"><?php echo ($vo["value"]); ?></textarea>
		        </div><?php break;?>
	    	<?php case "3": ?><div class="input">
		        	<?php echo ($vo["title"]); ?>：
		            <label class="new_label"><input type="radio" name="<?php echo ($vo["name"]); ?>" value="1" <?php if($vo['value'] == 1): ?>checked="checked"<?php endif; ?> />是</label>
		            <label class="new_label"><input type="radio" name="<?php echo ($vo["name"]); ?>" value="0" <?php if($vo['value'] == 0): ?>checked="checked"<?php endif; ?> />否</label>
		        </div><?php break;?>
	    	<?php default: ?>
	    		<div class="input"><?php echo ($vo["title"]); ?>：<input type="text" name="<?php echo ($vo["name"]); ?>" value="<?php echo ($vo["value"]); ?>" /> <em class="red">*</em>
	    		</div><?php endswitch; endforeach; endif; else: echo "" ;endif; ?>
		        <div class="submit">
		            <input type="submit" value="提交" />
		            <input type="reset" value="重置" class="white"/>
		        </div>
		    </div>
		    </form>
		</div>
	</div>
	<script type="text/javascript">
		function openUpload(){
			layer.open({
				type:2,
				title:'上传水印图片',
				shadeClose:true,
				shade:0.8,
				area:['450px','240px'],
				content:'/api/index.php?s=/Admin/Up/mark',
			});
		}
	</script>

        
</body>
</html>