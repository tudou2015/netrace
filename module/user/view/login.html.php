<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $lang->title; ?></title>
<link href="/res/admin/css/login.css" rel="stylesheet" type="text/css" />
<script src="/res/admin/js/jquery-1.7.2.min.js" type="text/javascript"></script>
<script language="JavaScript">
<!--
var lasttime = new Date().getTime();
function fleshVerify(){ 
	//重载验证码
	var timenow = new Date().getTime();
	var diff = timenow - lasttime;
	if(diff < <?php echo $app->config->minTime;?>000)
	{
		return;//刷新过快
	}	
	lasttime = timenow;
	$('#verifyImg').attr("src", "<?php echo helper::createLink('user','code',array('type'=>1),array('t'=>'"+timenow+"')); ?>");
}
//-->
</script>
</head>

<body>
	<div id="login">
		<div id="login_header">
			<h1 class="login_logo">
				<a><img src="/res/admin/images/title.png" /></a>
			</h1>
			<div class="login_headerContent">
				<div class="navList">
					<ul>
						<li><a href='/'>系统首页</a></li>
					</ul>
				</div>
				<h2 class="login_title"><img src="/res/admin/images/login_title.png" /></h2>
			</div>
		</div>
		<div id="login_content">
			<div class="loginForm">
				<form action="<?php echo helper::createLink('user','login'); ?>" method="post" target="hiddenwin">
					<p>
						<label>用户名：</label>
						<input type="text" name="name" size="20" class="login_input" />
					</p>
					<p>
						<label>密码：</label>
						<input type="password" name="password" size="20" class="login_input" />
					</p>
					<p>
						<label>验证码：</label>
						<input class="code" type="text" name="code" size="4" maxlength="4" />看不清，可点击图片可更换验证码
					</p>
					<p>
						<img id="verifyImg" src="<?php echo helper::createLink('user','code',array('type'=>1)); ?>" alt="点击刷新验证码" onClick="fleshVerify()" width="230" style="cursor:pointer;border:1px solid black" />
					</p>
					<div class="login_bar">
						<input class="sub" type="submit" value=" " />
					</div>
				</form>
			</div>
			<div class="login_banner"><img src="/res/admin/images/login_banner.jpg" /></div>
			<div class="login_main">
				<ul class="helpList">
				</ul>
				<div class="login_inner">
<?php echo $lang->user->support;?>
				</div>
			</div>
		</div>
		<div id="login_footer">
			<?php echo $lang->copyright;?>
		</div>
	</div>
<iframe frameborder='0' height='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='hidden'></iframe>
</body>
</html>