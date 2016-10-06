<?php
return array(
	//'配置项'=>'配置值'
	//PDO连接专用定义
	'DB_TYPE'=>'mysql',
	'DB_USER'=>'root',
	'DB_PWD'=>'123456',
	'DB_PREFIX'=>'app_',
	'DB_PORT'=>3306,
	'DB_PARAMS'=>array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),
	'DB_DSN'=>'mysql:host=localhost;dbname=appmom;charset=UTF8',

	//页面调试
	'SHOW_PAGE_TRACE'=>false,
	//修改模版文件后缀
	'TMPL_TEMPLATE_SUFFIX'=>'.html',
	//允许模块访问
	'MODULE_ALLOW_LIST'=>array('Home','Admin'),
	//设置默认访问模块
	'DEFAULT_MODULE'=>'Home',
	
	//设置默认主题
	'DEFAULT_THEME'=>'default',

	//启用路由功能
	'URL_ROUTER_ON'=>true,

	//动态路由设置
	'URL_ROUTE_RULES'=>array(
		
	),
	//静态路由配置
	'URL_MAP_RULES'=>array(
		
	),
	//URL模式设置
	'URL_MODEL'=>3,

);