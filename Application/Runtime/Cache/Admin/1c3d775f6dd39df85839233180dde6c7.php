<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
 <head>
        <title>积分商品管理-添加积分商品</title>
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
        <a href="/api/index.php?s=/Admin/CreditGoods/logout">退出</a>
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
	<div id="content" class="white">
        <h1><img src="/api/Public/Admin/img/icons/posts.png" alt="" /> 积分商品管理-添加积分商品</h1>
        <div class="bloc">
		    <div class="title"><a href="/api/index.php?s=/Admin/CreditGoods/index">积分商品列表</a> &gt; <a href="/api/index.php?s=/Admin/CreditGoods/add">添加积分商品</a></div>
		    <form method="post" action="">
		    <div class="content">
	    		<div class="input medium">商品标题：<input type="text" name="title" /> <em class="red">*</em></div>
                <div class="input">商品积分：<input type="text" name="credit" /> <em class="red">*</em></div>
                <div class="input">商品库存：<input type="text" name="inventory" /> <em class="red">*</em></div>
				<div class="input">缩略　图：
					<input type="hidden" name="mainpic" id="mainpic" />
					<input type="text" name="thumbpic" id="thumbpic" /> <em class="red">* <a href="javascript:;" class="button" onclick="openUp();">上传图片</a></em>
					<div id="thumb" style="padding: 10px 0 0 63px;"></div>
				</div>
		        <div class="input textarea">
		            <span class="middle">商品简介：</span><textarea name="descript" rows="3" cols="5"></textarea>
		        </div>
		        <div class="input textarea">
		        	商品详情：<textarea name="content" id="editor"></textarea>
		        </div>
		        <div class="input">
		        	是否推荐：
		            <input type="radio" id="radio1" name="isrec" value="1" checked="checked" /><label for="radio1" class="inline">是</label>
		            <input type="radio" id="radio2"  name="isrec" value="0" /><label for="radio2" class="inline">否</label>
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
    	function openUp(){
    		var url='/api/index.php?s=/Admin/Up/index';
    		var name='上传图片';
    		var iWidth='750';
    		var iHeight='550';
    		var iTop=(window.screen.availHeight - 30 - iHeight)/2;
    		var iLeft=(window.screen.availWidth - 10 - iWidth)/2;
    		window.open(url,name,'height='+iHeight+',width='+iWidth+',top='+iTop+',left='+iLeft+',toolbar=no,menubar=no,scrollbars=no,resizeable=no,location=no,status=no');
    	}
    </script>

        
    </body>
</html>