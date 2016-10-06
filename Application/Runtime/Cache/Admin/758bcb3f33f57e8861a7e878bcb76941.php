<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>公告管理-栏目列表</title>
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
        
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 公告管理-栏目列表</h1>
        <div class="bloc">
		    <div class="title"><?php if(is_array($categrouplist)): $i = 0; $__LIST__ = $categrouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="/api/index.php?s=/Admin/Category/index/gid/<?php echo ($vo["id"]); ?>"><?php echo ($vo["name"]); ?>栏目列表</a>&nbsp;&gt;&nbsp;<?php endforeach; endif; else: echo "" ;endif; ?><a href="/api/index.php?s=/Admin/Category/add">添加栏目</a></div>
		    <div class="content">
		   	<form method="post" action="">
				<table>
					<thead>
		                <tr>
		                    <th><input type="checkbox" class="checkall"/></th>
		                    <th>栏目名称</th>
		                    <th>栏目简介</th>
		                    <th>自定义属性</th>
		                    <th>栏目类型</th>
		                    <th>栏目分组</th>
		                    <th style="text-align:center;">排序</th>
		                    <th style="text-align:center;">操作</th>
		                </tr>
		            </thead>
		            <tbody>
		            	<?php if(is_array($catelist)): $i = 0; $__LIST__ = $catelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                    <td><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		                    <td class="pad-left-level<?php echo ($vo["level"]); ?>"><?php echo ($vo["name"]); ?></td>
		                    <td><?php echo ($vo["descript"]); ?></td>
		                    <td><?php echo ($vo["attr"]); ?></td>
		                    <td><?php if($vo['type'] == 1): ?>列表栏目<?php else: ?>单页栏目<?php endif; ?></td>
		                    <td><?php if($vo['groupid'] == 1): ?>公告<?php elseif($vo['groupid'] == 2): ?>商品<?php elseif($vo['groupid'] == 3): ?>服务<?php elseif($vo['groupid'] == 4): ?>活动<?php elseif($vo['groupid'] == 5): ?>论坛<?php else: endif; ?></td>
		                    <td style="text-align:center;"><input type="text" name="sort[<?php echo ($vo["id"]); ?>]" class="small" value="<?php echo ($vo["sort"]); ?>" /></td>
		                    <td class="actions"><?php if($vo['type'] == 1): ?><a href="/api/index.php?s=/Admin/Category/add/gid/<?php echo ($gid); ?>/id/<?php echo ($vo["id"]); ?>" title="Add this content"><?php else: ?><a href="javascript:;" title="Add this content"><?php endif; ?><img src="/api/Public/Admin/img/add.png" alt="新增"></a>　<a href="/api/index.php?s=/Admin/Category/edit/gid/<?php echo ($gid); ?>/id/<?php echo ($vo["id"]); ?>" title="Edit this content"><img src="/api/Public/Admin/img/icons/actions/edit.png" alt="修改" /></a>　<a href="/api/index.php?s=/Admin/Category/del/gid/<?php echo ($gid); ?>/id/<?php echo ($vo["id"]); ?>" title="Delete this content" onclick="return confirm('你确定要删除这个栏目吗？');"><img src="/api/Public/Admin/img/icons/actions/delete.png" alt="删除" /></a></td>
		                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
		            </tbody>
				</table>
				<div class="left input">
					<select name="action" id="tableaction">
		                <option value="">Action</option>
		                <option value="delete">Delete</option>
		                <option value="sort">Sort</option>
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

        
    </body>
</html>