<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>菜单管理-菜单列表</title>
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
        <a href="/api/index.php?s=/Admin/Auth/logout">退出</a>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 菜单管理-菜单列表</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Auth/index">菜单列表</a> &gt; <a href="/api/index.php?s=/Admin/Auth/add">添加菜单</a></div>
		    <div class="content">
		   	<form method="post" action="">
				<table>
					<thead>
		                <tr>
		                    <th><input type="checkbox" class="checkall"/></th>
		                    <th>菜单ID</th>
		                    <th>菜单名称</th>
		                    <th>图标样式</th>
		                    <th>菜单链接</th>
		                    <th>显示菜单</th>
		                    <th style="text-align:center;">操作</th>
		                </tr>
		            </thead>
		            <tbody>
		            <?php if(is_array($authlist)): $i = 0; $__LIST__ = $authlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                    <td><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		                    <td><?php echo ($vo["id"]); ?></td>
		                    <td><?php echo ($vo["name"]); ?></td>
		                    <td><?php echo ($vo["icon"]); ?></td>
		                    <td><?php echo ($vo["url"]); ?></td>
		                    <td><?php if($vo['status'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
		                    <td class="actions"><a href="/api/index.php?s=/Admin/Auth/add/id/<?php echo ($vo["id"]); ?>" title="Add this content"><img src="/api/Public/Admin/img/add.png" alt="新增"></a>　<a href="/api/index.php?s=/Admin/Auth/edit/id/<?php echo ($vo["id"]); ?>" title="Edit this content"><img src="/api/Public/Admin/img/icons/actions/edit.png" alt="修改" /></a>　<a href="/api/index.php?s=/Admin/Auth/del/id/<?php echo ($vo["id"]); ?>" title="Delete this content" onclick="return confirm('你确定要删除这个菜单吗？');"><img src="/api/Public/Admin/img/icons/actions/delete.png" alt="删除" /></a></td>
		                </tr>
		                <?php if(is_array($vo['child'])): $i = 0; $__LIST__ = $vo['child'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i;?><tr>
			                    <td><input type="checkbox" name="id[]" value="<?php echo ($voo["id"]); ?>" /></td>
			                    <td><?php echo ($voo["id"]); ?></td>
			                    <td style="padding-left:25px;"><?php echo ($voo["name"]); ?></td>
			                    <td><?php echo ($voo["icon"]); ?></td>
			                    <td><?php echo ($voo["url"]); ?></td>
			                    <td><?php if($voo['status'] == 1): ?>是<?php else: ?>否<?php endif; ?></td>
			                    <td class="actions"><a href="/api/index.php?s=/Admin/Auth/add/id/<?php echo ($voo["id"]); ?>" title="Add this content"><img src="/api/Public/Admin/img/add.png" alt="新增"></a>　<a href="/api/index.php?s=/Admin/Auth/edit/id/<?php echo ($voo["id"]); ?>" title="Edit this content"><img src="/api/Public/Admin/img/icons/actions/edit.png" alt="修改" /></a>　<a href="/api/index.php?s=/Admin/Auth/del/id/<?php echo ($voo["id"]); ?>" title="Delete this content" onclick="return confirm('你确定要删除这个菜单吗？');"><img src="/api/Public/Admin/img/icons/actions/delete.png" alt="删除" /></a></td>
			                </tr><?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?>
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
				</div>
			</form>
			</div>
		</div>
	</div>

        
    </body>
</html>