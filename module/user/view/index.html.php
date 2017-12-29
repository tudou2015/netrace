<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php 
	echo $lang->title;
?>		
	</title>

<link href="/res/admin/themes/default/style.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="/res/admin/css/core.css" rel="stylesheet" type="text/css" media="screen"/>
<link href="/res/admin/css/print.css" rel="stylesheet" type="text/css" media="print"/>
<link href="/res/admin/plugin/uploadify/css/uploadify.css" rel="stylesheet" type="text/css" media="screen"/>
<!--[if IE]>
<link href="/res/admin/css/ieHack.css" rel="stylesheet" type="text/css" media="screen"/>
<![endif]-->

<!--[if lte IE 9]>
<script src="/res/admin/js/speedup.js" type="text/javascript"></script>
<![endif]-->

<script src="/res/admin/js/jquery-1.7.2.js" type="text/javascript"></script>
<script src="/res/admin/js/jquery.cookie.js" type="text/javascript"></script>
<script src="/res/admin/js/jquery.validate.js" type="text/javascript"></script>
<script src="/res/admin/js/jquery.bgiframe.js" type="text/javascript"></script>
<script src="/res/admin/plugin/xheditor/xheditor-1.2.1.min.js" type="text/javascript"></script>
<script src="/res/admin/plugin/xheditor/xheditor_lang/zh-cn.js" type="text/javascript"></script>
<script src="/res/admin/plugin/uploadify/scripts/jquery.uploadify.js" type="text/javascript"></script>

<!-- svg图表  supports Firefox 3.0+, Safari 3.0+, Chrome 5.0+, Opera 9.5+ and Internet Explorer 6.0+ -->
<script type="text/javascript" src="/res/admin/plugin/chart/raphael.js"></script>
<script type="text/javascript" src="/res/admin/plugin/chart/g.raphael.js"></script>
<script type="text/javascript" src="/res/admin/plugin/chart/g.bar.js"></script>
<script type="text/javascript" src="/res/admin/plugin/chart/g.line.js"></script>
<script type="text/javascript" src="/res/admin/plugin/chart/g.pie.js"></script>
<script type="text/javascript" src="/res/admin/plugin/chart/g.dot.js"></script>

<!--
<script src="js/dwz.core.js" type="text/javascript"></script>
<script src="js/dwz.util.date.js" type="text/javascript"></script>
<script src="js/dwz.validate.method.js" type="text/javascript"></script>
<script src="js/dwz.barDrag.js" type="text/javascript"></script>
<script src="js/dwz.drag.js" type="text/javascript"></script>
<script src="js/dwz.tree.js" type="text/javascript"></script>
<script src="js/dwz.accordion.js" type="text/javascript"></script>
<script src="js/dwz.ui.js" type="text/javascript"></script>
<script src="js/dwz.theme.js" type="text/javascript"></script>
<script src="js/dwz.switchEnv.js" type="text/javascript"></script>
<script src="js/dwz.alertMsg.js" type="text/javascript"></script>
<script src="js/dwz.contextmenu.js" type="text/javascript"></script>
<script src="js/dwz.navTab.js" type="text/javascript"></script>
<script src="js/dwz.tab.js" type="text/javascript"></script>
<script src="js/dwz.resize.js" type="text/javascript"></script>
<script src="js/dwz.dialog.js" type="text/javascript"></script>
<script src="js/dwz.dialogDrag.js" type="text/javascript"></script>
<script src="js/dwz.sortDrag.js" type="text/javascript"></script>
<script src="js/dwz.cssTable.js" type="text/javascript"></script>
<script src="js/dwz.stable.js" type="text/javascript"></script>
<script src="js/dwz.taskBar.js" type="text/javascript"></script>
<script src="js/dwz.ajax.js" type="text/javascript"></script>
<script src="js/dwz.pagination.js" type="text/javascript"></script>
<script src="js/dwz.database.js" type="text/javascript"></script>
<script src="js/dwz.datepicker.js" type="text/javascript"></script>
<script src="js/dwz.effects.js" type="text/javascript"></script>
<script src="js/dwz.panel.js" type="text/javascript"></script>
<script src="js/dwz.checkbox.js" type="text/javascript"></script>
<script src="js/dwz.history.js" type="text/javascript"></script>
<script src="js/dwz.combox.js" type="text/javascript"></script>
<script src="js/dwz.print.js" type="text/javascript"></script>
-->

<!-- 可以用dwz.min.js替换前面全部dwz.*.js (注意：替换是下面dwz.regional.zh.js还需要引入) -->
<script src="/res/admin/js/dwz.min.js" type="text/javascript"></script>
<!-- -->
<script src="/res/admin/js/dwz.regional.zh.js" type="text/javascript"></script>

<link rel='stylesheet' href='/res/admin/plugin/colorbox/colorbox.css?v=1361791026' type='text/css' media='screen' />
<script src='/res/admin/plugin/colorbox/min.js?v=1361791026' type='text/javascript'></script>

<script type="text/javascript" src="/res/admin/plugin/raty/jquery.raty.min.js"></script>

<script type="text/javascript">
$(function(){
	DWZ.init("/res/admin/dwz.frag.xml", {
		loginUrl:"login_dialog.html", loginTitle:"登录",	// 弹出登录对话框
//		loginUrl:"login.html",	// 跳到登录页面
		statusCode:{ok:200, error:300, timeout:301}, //【可选】
		pageInfo:{pageNum:"pageNum", numPerPage:"numPerPage", orderField:"orderField", orderDirection:"orderDirection"}, //【可选】
		debug:false,	// 调试模式 【true|false】
		callback:function(){
			initEnv();
			$("#themeList").theme({themeBase:"/res/admin/themes"}); // themeBase 相对于index页面的主题base路径
		}
	});
});

</script>
</head>

<body scroll="no">
	<div id="layout">
		<div id="header">
			<div class="headerNav">
				<a class="logo">标志</a>
				<ul class="nav">
					<li><a>当前用户:<?php $user = $app->session->user;echo '[',$org->name,'][',$lang->user->typeMap[$user->type],'][',$user->name,']';?></a></li>					
					<li><a href="<?php echo helper::createLink('user','password',array('userID'=>$app->session->user->id)); ?>" target="dialog">修改密码</a></li>
					<li><a href="<?php echo helper::createLink('user','logout'); ?>">退出</a></li>
				</ul>
				<ul class="themeList" id="themeList">
					<li theme="default"><div class="selected">蓝色</div></li>
					<li theme="green"><div>绿色</div></li>
					<!--<li theme="red"><div>红色</div></li>-->
					<li theme="purple"><div>紫色</div></li>
					<li theme="silver"><div>银色</div></li>
					<li theme="azure"><div>天蓝</div></li>
				</ul>
			</div>

			<!-- navMenu -->
			
		</div>

		<div id="leftside">
			<div id="sidebar_s">
				<div class="collapse">
					<div class="toggleCollapse"><div></div></div>
				</div>
			</div>
			<div id="sidebar">

				<div class="accordion" fillSpace="sidebar">
					<div class="accordionHeader">
						<h2><span>Folder</span>菜单</h2>
					</div>
					<div class="accordionContent">
						<ul class="tree treeFolder collapse">
							<li><a>竞赛管理</a>
								<ul>
									<li><a href='<?php echo helper::createLink('race','browse'); ?>' target='navTab' rel='race'>活动管理</a></li>
									<li><a href='<?php echo helper::createLink('player','browse'); ?>' target='navTab' rel='player'>选手管理</a></li>
									<li><a href='<?php echo helper::createLink('content','browse'); ?>' target='navTab' rel='content'>内容管理</a></li>
									<li><a>投票管理</a>
											<ul>
												<li><a href='<?php echo helper::createLink('vote','statbystu'); ?>' target='navTab' rel='score1'>按学生统计</a></li>
												<li><a href='<?php echo helper::createLink('vote','statbydate'); ?>' target='navTab' rel='score2'>按日期统计</a></li>
												<li><a href='<?php echo helper::createLink('vote','statbyteach'); ?>' target='navTab' rel='score3'>按指导老师统计</a></li>
											</ul>
									</li>
									<li><a href='<?php echo helper::createLink('judge','browse'); ?>' target='navTab' rel='judge'>专家评分</a></li>
									<li><a href='<?php echo helper::createLink('score','browse'); ?>' target='navTab' rel='score'>成绩统计</a></li>
								</ul>	
							</li>
							<li><a>系统管理</a>
								<ul>
									<li><a href='<?php echo helper::createLink('org','browse'); ?>' target='navTab' rel='org'>单位管理</a></li>
									<li><a href='<?php echo helper::createLink('user','browse'); ?>' target='navTab' rel='user'>用户管理</a></li>
									<li><a href='<?php echo helper::createLink('action','browse'); ?>' target='navTab' rel='action'>日志管理</a></li>	
									<li><a href='<?php echo helper::createLink('url','code'); ?>' target='navTab' rel='url'>URL管理</a></li>	
								</ul>	
							</li>
						</ul>					
					</div>
				</div>
			</div>
		</div>
		<div id="container">
			<div id="navTab" class="tabsPage">
				<div class="tabsPageHeader">
					<div class="tabsPageHeaderContent"><!-- 显示左右控制时添加 class="tabsPageHeaderMargin" -->
						<ul class="navTab-tab">
							<li tabid="main" class="main"><a href="javascript:;"><span><span class="home_icon">系统说明</span></span></a></li>
						</ul>
					</div>
					<div class="tabsLeft">left</div><!-- 禁用只需要添加一个样式 class="tabsLeft tabsLeftDisabled" -->
					<div class="tabsRight">right</div><!-- 禁用只需要添加一个样式 class="tabsRight tabsRightDisabled" -->
					<div class="tabsMore">more</div>
				</div>
				<ul class="tabsMoreList">
					<li><a href="javascript:;">系统说明</a></li>
				</ul>
				<div class="navTab-panel tabsPageContent layoutBox">
					<div class="page unitBox">
						<div class="pageFormContent" layoutH="80" style="margin-right:230px">
														
						</div>						
					</div>					
				</div>
			</div>
		</div>

	</div>

	<div id="footer"><?php echo $lang->copyright;?></div>

</body>
</html>