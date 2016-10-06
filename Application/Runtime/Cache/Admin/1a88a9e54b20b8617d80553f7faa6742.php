<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>栏目管理-添加栏目</title>
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
        <a href="/api/index.php?s=/Admin/Category/logout">退出</a>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 栏目管理-添加栏目</h1>
        <div class="bloc">
		    <div class="title"><?php if(is_array($categrouplist)): $i = 0; $__LIST__ = $categrouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="/api/index.php?s=/Admin/Category/index/gid/<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?>栏目列表</a>&nbsp;&gt;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?><a href="/api/index.php?s=/Admin/Category/add">添加栏目</a></div>
		    <form method="post" action="" enctype="multipart/form-data">
		    <div class="content">
	    		<div class="input">栏目名称：<input type="text" name="name" /> <em class="red">*</em></div>
	    		<?php if($oneParentCate['id'] == ''): ?><div class="input">
	    			栏目　组：<select name="groupid" id="group">
		                <option value="0">--请选择栏目分组--</option>
		                <?php if(is_array($categrouplist)): $i = 0; $__LIST__ = $categrouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		            </select> <em class="red">*</em>
	    		</div>
	    		<div class="input">
		            上级栏目：
		            <select name="pid" id="pid">
		                <option value="0">顶级栏目</option>
		                <?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($vo["id"]); ?>"><?php echo ($vo["typename"]); ?></option>
		                <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>"><?php echo ($voo["typename"]); ?></option><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
		            </select> <em class="red">*</em>
		        </div>
		        <?php else: ?>
		        <div class="input">
	    			栏目　组：<input type="text" name="pgroup" value="<?php echo ($oneParentCate["groupname"]); ?>" readonly="true" />
		            <input type="hidden" name="groupid" value="<?php echo ($oneParentCate["groupid"]); ?>" />
	    		</div>
		        <div class="input">
		        	上级栏目：<input type="text" name="pname" value="<?php echo ($oneParentCate["name"]); ?>" readonly="true" />
		            <input type="hidden" name="pid" value="<?php echo ($oneParentCate["id"]); ?>" />
		        </div>
		        <?php if($gid == 2): ?><div class="input brand">关联品牌：<?php if(is_array($brandlist)): $i = 0; $__LIST__ = $brandlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="normal"><input type="checkbox" value="<?php echo ($vo["id"]); ?>" name="brandids[]" class="auto"><?php echo ($vo["name"]); ?></label>　<?php endforeach; endif; else: echo "" ;endif; ?></div><?php endif; endif; ?>
		        <div class="input">
	    			<span class="cusattr">栏目属性：</span>
	    			<div class="pad-left">
		    			<?php if(is_array($attrlist)): $i = 0; $__LIST__ = $attrlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><label class="normal"><input type="checkbox" name="attr[]" value="<?php echo ($vo["id"]); ?>" /><?php echo ($vo["name"]); ?></label>　<?php endforeach; endif; else: echo "" ;endif; ?>
	    			</div>
	    		</div>
		        <div class="input">
		            缩略　图：<input type="file" name="pic" /> <em class="red">*</em>
		        </div>
		        <div class="input textarea">
		            <span class="middle">栏目简介：</span><textarea name="descript" rows="3" cols="5"></textarea>
		        </div>
		        <div class="input textarea">
		        	栏目详情：<textarea name="content" id="editor"></textarea>
		        </div>
		        <div class="input">
		        	栏目类型：
		            <input type="radio" id="radio1" name="type" value="1" checked="checked" /><label for="radio1" class="inline">列表栏目</label>
		            <input type="radio" id="radio2"  name="type" value="0" /><label for="radio2" class="inline">单页栏目</label>
		        </div>
		        <div class="submit">
		            <input type="submit" value="提交" />
		            <input type="reset" value="重置" class="white"/>
		        </div>
		    </div>
		    </form>
		</div>
	</div>
	<script>
        KindEditor.ready(function(K){
            var opts={
                uploadJson : '/api/Public/kindeditor/php/upload_json.php',
                fileManagerJson : '/api/Public/kindeditor/php/file_manager_json.php',
                allowFileManager : true,
                height : '450px',
                width : '100%',
                afterBlur:function(){this.sync();}
            };
            var editor = K.create('textarea[name="content"]', opts);
        });
    </script>
    <script type="text/javascript">
    	$('#group').change(function(){
    		$('#pid').children('option:first').siblings().remove();
    		var gid=$(this).val();
    		$.ajax({
    			url:'/api/index.php?s=/Admin/Category/getSortNavByGid',
    			data:{"gid":gid},
    			type:'post',
    			dataType:'json',
    			success:function(response){
    				var html='';
    				if(response.errno == 0){
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
    					html='<option value="0">'+response.errmsg+'</option>';
    				}
    				$('#pid').children('option:first').after(html);
    			}
    		});
    	});
    	$('#pid').change(function(){
    		if($(this).parent().parent().siblings().hasClass('brand')){
    			$(this).parent().parent().siblings('.brand').remove();
    		}
    		var cateid=$(this).val();
    		$.ajax({
    			url:'/api/index.php?s=/Admin/Brand/getBrand',
    			data:{"cateid":cateid},
    			type:'post',
    			dataType:'json',
    			success:function(response){
    				if(response.errno == 0){
    					var str='';
    					var list=response.brandlist;
    					var leng=list.length;
    					for(var i=0;i<leng;i++){
    						str+='<label class="normal"><input type="checkbox" class="auto" name="brandids[]" value="'+list[i].id+'" />'+list[i].name+'</label>　';
    					}
    					var html='<div class="input brand">关联品牌：'+str+'</div>';
    					$('#pid').parent().parent().after(html);
    				}
    			}
    		});
    	});
    </script>

        
    </body>
</html>