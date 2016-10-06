<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>广告管理-广告列表</title>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 广告管理-广告列表</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Adver/index">广告列表</a> &gt; <a href="/api/index.php?s=/Admin/Adver/add">添加广告</a></div>
		    <div class="content">
		   	<form method="post" action="">
				<table>
					<thead>
		                <tr>
		                    <th><input type="checkbox" class="checkall"/></th>
		                    <th>缩略图</th>
		                    <th>标题</th>
		                    <th>链接</th>
		                    <th>链接类型</th>
		                    <th>广告位置</th>
		                    <th>年龄范围</th>
		                    <th>前台显示</th>
		                    <th style="text-align:center;">操作</th>
		                </tr>
		            </thead>
		            <tbody>
		            	<?php if(is_array($adverlist)): $i = 0; $__LIST__ = $adverlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                    <td><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		                    <td class="picture" style="width:100px;"><a href="<?php echo ($vo["thumb"]); ?>" class="zoombox"><img src="<?php echo ($vo["thumb"]); ?>" class="thumb" alt="<?php echo ($vo["title"]); ?>" /></a></td>
		                    <td><a href="<?php echo ($vo["url"]); ?>"><?php echo ($vo["title"]); ?></a></td>
		                    <td><?php echo ($vo["url"]); ?></td>
		                    <td><?php if($vo['type'] == 1): ?>url链接<?php elseif($vo['type'] == 2): ?>商品列表<?php elseif($vo['type'] == 3): ?>活动列表<?php elseif($vo['type'] == 4): ?>商品<elseif condition="$vo['type'] eq 5">活动<?php else: endif; ?></td>
		                    <td><?php echo ($vo["typename"]); ?></td>
		                    <td><?php if($vo['agerange'] == 1): ?>0-3<?php elseif($vo['agerange'] == 2): ?>3-6<?php elseif($vo['agerange'] == 3): ?>6-12<?php else: endif; ?></td>
		                    <td>
		                    <?php if($vo['status'] == 1): ?>是 | <a href="javascript:;" onclick="isRec(<?php echo ($vo["id"]); ?>,this);">取消</a><?php else: ?>否 | <a href="javascript:;" onclick="isRec(<?php echo ($vo["id"]); ?>,this);">推荐</a><?php endif; ?>
		                    </td>
		                    <td class="actions"><a href="/api/index.php?s=/Admin/Adver/edit/id/<?php echo ($vo["id"]); ?>" title="Edit this content"><img src="/api/Public/Admin/img/icons/actions/edit.png" alt="修改" /></a>　<a href="/api/index.php?s=/Admin/Adver/del/id/<?php echo ($vo["id"]); ?>" title="Delete this content" onclick="return confirm('你确定要删除这条广告吗？');"><img src="/api/Public/Admin/img/icons/actions/delete.png" alt="删除" /></a></td>
		                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
		            </tbody>
				</table>
				<div class="left input">
					<select name="action" id="tableaction">
		                <option value="">Action</option>
		                <option value="delete">Delete</option>
		            </select>
		            <div class="submit">
		            	<input type="submit" value="提交" />
		            </div>
				</div>
				<div class="pagination">
					<?php echo ($page); ?>
				</div>
			</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function isRec(id,target){
			var $target=$(target);
			$.ajax({
				url:'/api/index.php?s=/Admin/Adver/isRec',
				data:{"id":id},
				type:'post',
				dataType:'json',
				success:function(response){
					if(response.errno == 0){
						if(response.status == 1){
							$target.parent().html('是 | <a href="javascript:;" onclick="isRec('+id+',this);">取消</a>');
							alert('已推荐');
						}else{
							$target.parent().html('否 | <a href="javascript:;" onclick="isRec('+id+',this);">推荐</a>');
							alert('已取消');
						}
					}
				}
			});
		}
	</script>

        
    </body>
</html>