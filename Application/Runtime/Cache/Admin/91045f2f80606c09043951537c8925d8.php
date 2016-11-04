<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>文章管理-添加文章</title>
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
        <a href="/api/index.php?s=/Admin/Article/logout">退出</a>
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
    <script type="text/javascript" src="/api/Public/laydate/laydate.dev.js"></script>
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 文章管理-添加文章</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Article/index">文章列表</a> &gt; <a href="/api/index.php?s=/Admin/Article/add">添加文章</a></div>
		    <form method="post" action="" enctype="multipart/form-data">
		    <div class="content">
	    		<div class="input medium">文章标题：<input type="text" name="title" /> <em class="red">*</em></div>
	    		<div class="input medium">文章标签：<input type="text" name="tags" /> (<em class="red">*</em> 多个关键词以“,”分开)</div>
	    		<div class="input">文章作者：<input type="text" name="author" /></div>
	    		<div class="input">文章来源：<input type="text" name="source" /></div>
	    		<div class="input">发布时间：<input type="text" name="date" id="sub_date" /> <em class="red">*</em></div>
	    		<div class="input">
		            文章栏目：
		            <select name="cateid" id="cateid">
		            <option value="0">--请选择文章栏目--</option>
		            <?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><optgroup label="<?php echo ($vo["name"]); ?>">
		                <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><option value="<?php echo ($voo["id"]); ?>"><?php echo ($voo["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
		                </optgroup><?php endforeach; endif; else: echo "" ;endif; ?>
		            </select> <em class="red">*</em>
		        </div>
				<div class="input">
		            缩略　图：<input type="file" name="pic" /> <em class="red">*</em>
		        </div>
		        <div class="input textarea">
		            <span class="middle">文章简介：</span><textarea name="descript" rows="3" cols="5"></textarea>
		        </div>
		        <div class="input textarea">
		        	文章详情：<textarea name="content" id="editor"></textarea>
		        </div>
		        <div class="input">
		        	是否推荐：
		            <input type="radio" id="radio1" name="is_rec" checked="checked" value="1" /><label for="radio1" class="inline">是</label>
		            <input type="radio" id="radio2"  name="is_rec" value="0" /><label for="radio2" class="inline">否</label>
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
        laydate({
            elem:'#sub_date',
            istime:true, 
            format:'YYYY-MM-DD hh:mm:ss',
        });
    </script>
    <script type="text/javascript">
    	$('#cateid').change(function(){
        	var cateid=$(this).val();
        	$.ajax({
        		url:'/api/index.php?s=/Admin/Category/getAttr',
        		data:{"id":cateid},
        		type:'post',
        		dataType:'json',
        		success:function(response){
        			$('#cateid').parent().parent().siblings('.cateattr').remove();
        			if(response.errno == 0){
        				var data = response.list;
        				var length = data.length;
        				var html = '';
        				for(var i=0;i<length;i++){
        					var id = data[i].id;
        					var name = data[i].name;
        					if(data[i].value != ''){
        						var valArr = data[i].value.split(';');
        						var len = valArr.length;
        						var str = '';
        						for(var j=0;j<len;j++){
        							var val=valArr[j].split(':');
        							if(data[i].type == 2){
        								str+='<label class="new_label"><input type="radio" name="attr[item'+id+']" value="'+val[0]+'" />'+val[1]+'</label>　';
        							}else if(data[i].type == 3){
        								str+='<label class="normal"><input type="checkbox" class="auto" name="attr[item'+id+'][]" value="'+val[0]+'" />'+val[1]+'</label>　';
        							}
        						}
        					}
        					if(data[i].type == 1){
                                if(id == 7){
                                    var start_time_descript=' (<em class="red">*</em> 格式：2016/9/20 09:00:00)';
                                }else{
                                    var start_time_descript='';
                                }
        						html+='<div class="input cateattr">'+name+'：<input type="text" name="attr[item'+id+']" />'+start_time_descript+'</div>';
        					}else if(data[i].type == 2){
        						html+='<div class="input cateattr">'+name+'：'+str+'</div>';
        					}else if (data[i].type == 3){
        						html+='<div class="input cateattr"><span class="cusattr">'+name+'：</span><div class="pad-left">'+str+'</div></div>';
        					}else if(data[i].type == 4){
        						html+='<div class="input cateattr textarea"><span class="middle">'+name+'：</span><textarea name="attr[item'+id+']" rows="3" cols="5"></textarea></div>';
        					}
        				}
        				$('#cateid').parent().parent().after(html);
        			}
        		}
        	});
        });
    </script>

        
    </body>
</html>