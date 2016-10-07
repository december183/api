<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>地区管理-添加地区</title>
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
        <a href="/api/index.php?s=/Admin/Region/logout">退出</a>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 地区管理-添加地区</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Region/index">地区列表</a>&nbsp;&gt;&nbsp;<a href="/api/index.php?s=/Admin/Region/add">添加地区</a></div>
		    <form method="post" action="">
		    <div class="content">
	    		<div class="input">地区名称：<input type="text" name="name" /> <em class="red">*</em></div>
	    		<div class="input">
		            所在省分：
		            <select name="pid" id="pid">
		            <option value="0">--请选择所在省分--</option>
	                <?php if(is_array($provincelist)): $i = 0; $__LIST__ = $provincelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		            </select> <em class="red">*</em>
		        </div>
		        <div class="input">
		            所在城市：
		            <select name="cityid" id="cityid">
		            <option value="0">--请选择所在城市--</option>
		            </select> <em class="red">*</em>
		        </div>
		        <div class="submit">
		            <input type="submit" value="提交" />
		            <input type="reset" value="重置" class="white"/>
		        </div>
		    </div>
		    </form>
		</div>
	</div>
	<script type="text/javascript">
		$('#pid').change(function(){
			$('#cityid').children('option:first').siblings('option').remove();
			var pid=$(this).val();
			$.ajax({
				url:'/api/index.php?s=/Admin/City/getCityList',
				data:{"pid":pid},
				type:'post',
				dataType:'json',
				success:function(response){
					var html='';
					if(response.errno == 0){
						var list=response.list;
						var leng=list.length;
						for(var i=0; i<leng; i++){
							html+='<option value="'+list[i].id+'">'+list[i].name+'</option>';
						}
					}else{
						html=response.errmsg;
					}
					$('#cityid').children('option:first').after(html);
				}
			});
		});
	</script>

        
    </body>
</html>