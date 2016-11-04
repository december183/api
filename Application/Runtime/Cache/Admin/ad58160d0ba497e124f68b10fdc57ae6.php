<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>订单管理-订单列表</title>
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
        <a href="/api/index.php?s=/Admin/Order/logout">退出</a>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 订单管理-订单列表</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Order/index">订单列表</a></div>
		    <div class="content">
		   	<form method="post" action="">
				<table>
					<thead>
		                <tr>
		                    <th><input type="checkbox" class="checkall"/></th>
		                    <th>缩略图</th>
		                    <th>标题</th>
		                    <th>价格</th>
		                    <th>数量</th>
		                    <th>总价格</th>
		                    <th>收货人</th>
		                    <th>联系电话</th>
		                    <th>收货地址</th>
		                    <th>备注</th>
		                    <th>订单状态</th>
		                    <th style="text-align:center;">操作</th>
		                </tr>
		            </thead>
		            <tbody>
		            	<?php if(is_array($orderlist)): $i = 0; $__LIST__ = $orderlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                    <td rowspan="<?php echo ($vo["count"]); ?>"><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		                    <?php if(is_array($vo['info'])): $i = 0; $__LIST__ = $vo['info'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$voo): $mod = ($i % 2 );++$i; if($key == 0): ?><td class="picture" style="width:100px;"><a href="<?php echo ($voo["thumbpic"]); ?>" class="zoombox"><img src="<?php echo ($voo["thumbpic"]); ?>" class="thumb" alt="<?php echo ($voo["title"]); ?>" /></a></td>
		                    <td><?php echo ($voo["title"]); ?></td>
		                    <td><?php echo ($voo["price"]); ?></td>
		                    <td><?php echo ($voo["num"]); ?></td>
		                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["totalfee"]); ?></td>
		                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["name"]); ?></td>
		                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["phone"]); ?></td>
		                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["place"]); ?></td>
		                    <td rowspan="<?php echo ($vo["count"]); ?>"><?php echo ($vo["remark"]); ?></td>
		                    <td rowspan="<?php echo ($vo["count"]); ?>">
		                    <?php if($vo['status'] == 0): ?>已取消<?php elseif($vo['status'] == 1): ?><a href="javascript:;">已完成</a><?php elseif($vo['status'] == 2): ?><a href="javascript:;">待确认</a><?php elseif($vo['status'] == 3): ?><a href="javascript:;">待发货</a><?php elseif($vo['status'] == 4): ?><a href="javascript:;">待付款</a><?php else: endif; ?>
		                    </td>
		                    <td  rowspan="<?php echo ($vo["count"]); ?>" style="text-align:center;"><a href="/api/index.php?s=/Admin/Order/del/id/<?php echo ($vo["id"]); ?>" title="Delete this content" onclick="return confirm('你确定要删除这条订单吗？');"><img src="/api/Public/Admin/img/icons/actions/delete.png" alt="删除" /></a></td>
		                    <?php else: ?>
		                    <tr>
		                    <td class="picture" style="width:100px;"><a href="<?php echo ($voo["thumbpic"]); ?>" class="zoombox"><img src="<?php echo ($voo["thumbpic"]); ?>" class="thumb" alt="<?php echo ($voo["title"]); ?>" /></a></td>
		                    <td><?php echo ($voo["title"]); ?></td>
		                    <td><?php echo ($voo["price"]); ?></td>
		                    <td><?php echo ($voo["num"]); ?></td>
		                    </tr><?php endif; endforeach; endif; else: echo "" ;endif; ?>
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

        
    </body>
</html>