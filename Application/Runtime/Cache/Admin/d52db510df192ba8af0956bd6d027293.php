<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>价格区间管理-修改价格区间</title>
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
        <a href="/api/index.php?s=/Admin/Price/logout">退出</a>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 价格区间管理-修改价格区间</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Price/index">价格区间列表</a> &gt; <a href="/api/index.php?s=/Admin/Price/add">新增价格区间</a> &gt; <a href="/api/index.php?s=/Admin/Price/edit/id/<?php echo ($onePrice["id"]); ?>">修改价格区间</a></div>
		    <form method="post" action="">
		    <input type="hidden" name="id" value="<?php echo ($onePrice["id"]); ?>" />
		    <div class="content">
	    		<div class="input">价格区间：<input type="text" name="minprice" class="small" value="<?php echo ($onePrice["minprice"]); ?>" /> - <input type="text" name="maxprice" class="small" value="<?php echo ($onePrice["maxprice"]); ?>" /> <em class="red">*</em></div>
	    		<div class="input">
	    			栏目分组：
					<select name="gid" id="gid">
						<option value="0">--请选择栏目分组--</option>
						<option value="1" <?php if($onePrice['gid'] == 1): ?>selected="selected"<?php endif; ?>>商品</option>
						<option value="2" <?php if($onePrice['gid'] == 2): ?>selected="selected"<?php endif; ?>>服务</option>
					</select> <em class="red">*</em>
	    		</div>
	    		<div class="input">
		            关联栏目：
		            <input type="hidden" id="cate" name="cate" value="<?php echo ($onePrice["cateid"]); ?>" />
		            <select name="cateid" id="cateid">
		            	<option value="0">--请选择栏目类别--</option>
		            </select> <em class="red">*</em>
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
			var groupid=$('#gid').val();
			var gid=(groupid == 1) ? 2 : 3;
			var cate=$('#cate').val();
			$.ajax({
				url:'/api/index.php?s=/Admin/Category/getSortNavByGid',
				data:{"gid":gid},
				type:'post',
				dataType:'json',
				success:function(response){
					if(response.errno == 0){
						var html='';
						var list=response.catelist;
						var leng=list.length;
						for(var i=0;i<leng;i++){
							var child=list[i].child;
							if(cate == list[i].id){
								html+='<option value="'+list[i].id+'" selected="selected">'+list[i].name+'</option>';
							}else{
								html+='<option value="'+list[i].id+'">'+list[i].name+'</option>';
							}
							if(child){
								var len=child.length;
								for(var j=0;j<len;j++){
									var left=25*child[j].level;
									if(cate == child[j].id){
										html+='<option value="'+child[j].id+'" selected="selected" style="padding-left:'+left+'px;">'+child[j].name+'</option>';
									}else{
										html+='<option value="'+child[j].id+'" style="padding-left:'+left+'px;">'+child[j].name+'</option>';
									}
								}
							}
						}
					}else{
						html=response.errmsg;
					}
					$('#cateid').children('option:first').after(html);
					var catename=$('#cateid').children('option:selected').text();
					$('#cateid').siblings('span').text(catename);
				}
			});
		});
		$('#gid').change(function(){
			$('#cateid').siblings('span').text('--请选择栏目类别--');
			$('#cateid').children('option:first').siblings().remove();
			var groupid=$(this).val();
			var gid=(groupid == 1) ? 2 : 3;
			$.ajax({
				url:'/api/index.php?s=/Admin/Category/getSortNavByGid',
				data:{"gid":gid},
				type:'post',
				dataType:'json',
				success:function(response){
					if(response.errno == 0){
						var html='';
						var list=response.catelist;
						var leng=list.length;
						for(var i=0;i<leng;i++){
							html+='<option value="'+list[i].id+'">'+list[i].name+'</option>';
							var child=list[i].child;
							if(child){
								var len=child.length;
								for(var j=0;j<len;j++){
									var left=25*child[j].level;
									html+='<option value="'+child[j].id+'" style="padding-left:'+left+'px;">'+child[j].name+'</option>';
								}
							}
						}
					}else{
						html=response.errmsg;
					}
					$('#cateid').children('option:first').after(html);
				}
			});
		});
	</script>

        
    </body>
</html>