<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>广告管理-编辑广告</title>
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
        <a href="/api/index.php?s=/Admin/Adver/logout">退出</a>
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
        
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 广告管理-编辑广告</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Adver/index">广告列表</a> &gt; <a href="/api/index.php?s=/Admin/Adver/add">添加广告</a> &gt; <a href="/api/index.php?s=/Admin/Adver/add">编辑广告</a></div>
		    <form method="post" action="" enctype="multipart/form-data">
		    <input type="hidden" name="id" value="<?php echo ($oneAdver["id"]); ?>" />
		    <div class="content">
	    		<div class="input medium">广告标题：<input type="text" name="title" value="<?php echo ($oneAdver["title"]); ?>" /> <em class="red">*</em></div>
	    		<div class="input medium">广告链接：<input type="text" name="url" value="<?php echo ($oneAdver["url"]); ?>" /> <em class="red">*</em></div>
	    		<div class="input">
		            链接类型：
		            <select name="type" id="type">
		            <option value="0">--请选择链接类型--</option>
		            <option value="1" <?php if($oneAdver['type'] == 1): ?>selected="selected"<?php endif; ?>>url链接</option>
		            <option value="2" <?php if($oneAdver['type'] == 2): ?>selected="selected"<?php endif; ?>>商品列表</option>
		            <option value="3" <?php if($oneAdver['type'] == 3): ?>selected="selected"<?php endif; ?>>活动列表</option>
		            <option value="4" <?php if($oneAdver['type'] == 4): ?>selected="selected"<?php endif; ?>>商品</option>
		            <option value="5" <?php if($oneAdver['type'] == 5): ?>selected="selected"<?php endif; ?>>活动</option>
		            </select> <em class="red">*</em>
		        </div>
	    		<div class="input">
		            年龄范围：
		            <select name="agerange" id="agerange">
		            <option value="0">--请选择年龄范围--</option>
		            <option value="1" <?php if($oneAdver['agerange'] == 1): ?>selected="selected"<?php endif; ?>>0-3</option>
		            <option value="2" <?php if($oneAdver['agerange'] == 2): ?>selected="selected"<?php endif; ?>>3-6</option>
		            <option value="3" <?php if($oneAdver['agerange'] == 3): ?>selected="selected"<?php endif; ?>>6-12</option>
		            </select> <em class="red">*</em>
		        </div>
	    		<div class="input">
		            广告位置：
		            <select name="typeid" id="typeid">
		                <?php if(is_array($typelist)): $i = 0; $__LIST__ = $typelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>" <?php if($oneAdver['typeid'] == $vo['id']): ?>selected="selected"<?php endif; ?>><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		            </select> <em class="red">*</em>
		        </div>
		        <div class="input">广告宽度：<input type="text" name="width" class="width" /> <em class="red">*</em></div>
		        <div class="input">广告高度：<input type="text" name="height" class="height" /> <em class="red">*</em></div>
				<div class="input">
		            缩略　图：<input type="file" name="pic" /> <a href="<?php echo ($oneAdver["thumb"]); ?>" class="zoombox"><img src="<?php echo ($oneAdver["thumb"]); ?>" class="thumb" alt="<?php echo ($oneAdver["title"]); ?>" /></a> <em class="red">*</em>
		        </div>
		        <div class="input textarea">
		            <span class="middle">广告描述：</span><textarea name="summary" rows="3" cols="5"><?php echo ($oneAdver["summary"]); ?></textarea>
		        </div>
		        <div class="input">
		        	是否推荐：
		            <input type="radio" id="radio1" name="status" value="1" <?php if($oneAdver['status'] == 1): ?>checked="checked"<?php endif; ?> /><label for="radio1" class="inline">是</label>
		            <input type="radio" id="radio2"  name="status" value="0" <?php if($oneAdver['status'] == 0): ?>checked="checked"<?php endif; ?> /><label for="radio2" class="inline">否</label>
		        </div>
		        <div class="submit">
		            <input type="submit" value="提交" />
		            <input type="reset" value="重置" class="white" />
		        </div>
		    </div>
		    </form>
		</div>
	</div>
	<script type="text/javascript">
		$(function(){
			var typeid=$('#typeid').val();
			getAdverInfo(typeid);
		});
		$('#typeid').change(function(){
			var typeid=$(this).val();
			getAdverInfo(typeid);
		});
		function getAdverInfo(id){
			$.ajax({
				url:'/api/index.php?s=/Admin/AdverType/getAdverTypeInfo',
				data:{"id":id},
				type:'post',
				dataType:'json',
				success:function(response){
					if(response.errno == 0){
						$('.width').val(response.width);
						$('.height').val(response.height);
					}
				}
			});
		}
	</script>

        
    </body>
</html>