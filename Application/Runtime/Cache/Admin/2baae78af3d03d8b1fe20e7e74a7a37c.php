<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>客户管理-商家列表</title>
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
        <a href="/api/index.php?s=/Admin/Seller/logout">退出</a>
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
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 客户管理-商家列表</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/Seller/index">商家列表</a></div>
		    <div class="content">
		   	<form method="post" action="">
				<table>
					<thead>
		                <tr>
		                    <th><input type="checkbox" class="checkall"/></th>
		                    <th>营业执照</th>
		                    <th>商铺名称</th>
		                    <th>商户名称</th>
		                    <th>手机</th>
		                    <th>电话</th>
		                    <th>门店地址</th>
		                    <th>年龄范围</th>
		                    <th>是否审核</th>
		                    <th>状态</th>
		                    <th>注册日期</th>
		                    <th style="text-align:center;">操作</th>
		                </tr>
		            </thead>
		            <tbody>
		            	<?php if(is_array($sellerlist)): $i = 0; $__LIST__ = $sellerlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
		                    <td><input type="checkbox" name="id[]" value="<?php echo ($vo["id"]); ?>" /></td>
		                    <td class="picture" style="width:100px;"><a href="<?php echo ($vo["licence"]); ?>" class="zoombox"><img src="<?php echo ($vo["licence"]); ?>" class="thumb" alt="<?php echo ($vo["shopname"]); ?>" /></a></td>
		                    <td><?php echo ($vo["shopname"]); ?></td>
		                    <td><?php echo ($vo["realname"]); ?></td>
		                    <td><?php echo ($vo["phone"]); ?></td>
		                    <td><?php echo ($vo["tel"]); ?></td>
		                    <td><?php echo ($vo["location"]); ?></td>
		                    <td><?php echo ($vo["agerange"]); ?></td>
		                    <td><?php if($vo['level'] > 2): ?><a href="javascript:;" onclick="checkSeller(<?php echo ($vo["id"]); ?>,this);">已审核</a><?php else: ?><a href="javascript:;" onclick="checkSeller(<?php echo ($vo["id"]); ?>,this);">未审核</a><?php endif; ?></td>
		                    <td><?php if($vo['status'] == 1): ?><a href="javascript:;" onclick="setStatus(<?php echo ($vo["id"]); ?>,this);">正常</a><?php else: ?><a href="javascript:;" onclick="setStatus(<?php echo ($vo["id"]); ?>,this);">禁用</a><?php endif; ?></td>
		                    <td><?php echo (date('Y-m-d',$vo["date"])); ?></td>
		                    <td style="text-align:center;"><a href="/api/index.php?s=/Admin/Seller/del/id/<?php echo ($vo["id"]); ?>" title="Delete this content" onclick="return confirm('你确定要删除这条信息吗？');"><img src="/api/Public/Admin/img/icons/actions/delete.png" alt="删除" /></a></td>
		                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
		            </tbody>
				</table>
				<div class="left input">
					<select name="action" id="tableaction">
		                <option value="">Action</option>
		                <option value="delete">Delete</option>
		                <option value="check">Check</option>
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
		function setStatus(id,target){
			var $target=$(target);
			$.ajax({
				url:'/api/index.php?s=/Admin/Seller/setStatus',
				data:{"id":id},
				type:'post',
				dataType:'json',
				success:function(response){
					if(response.errno == 0){
						if(response.status == 1){
							$target.text('启用');
							alert('已启用');
						}else{
							$target.text('禁用');
							alert('已禁用');
						}
					}
				}
			});
		}
		function checkSeller(id,target){
			var $target=$(target);
			$.ajax({
				url:'/api/index.php?s=/Admin/Seller/checkSeller',
				data:{"id":id},
				type:'post',
				dataType:'json',
				success:function(response){
					if(response.errno == 0){
						if(response.level == 3){
							$target.text('已审核');
							alert('审核通过');
						}else{
							$target.text('未审核');
							alert('审核失败');
						}
					}
				}
			});
		}
	</script>

        
    </body>
</html>