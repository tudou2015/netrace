<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title><?php echo $race->name;?></title>
    <link href="/res/front/4/css/global.css" rel="stylesheet" type="text/css">
    <link href="/res/front/4/css/home.css" rel="stylesheet" type="text/css"></link>
		<link href="/res/front/4/css/default.css" rel="stylesheet" type="text/css"></link>
		<style type="text/css" media="screen">#altContent {visibility:hidden}</style>
		<script type="text/javascript" src="/res/front/4/js/jquery.js"></script>
		<script type="text/javascript" src="/res/front/4/js/default.js"></script>
</head>

<body>

<div id="headerscroll">
 <div id="header">
 <h1 id="logo"><a href="" target="_blank"></a></h1>
 <div class="link">
           <a href="#">帮助</a>

 </div>
  </div>
</div> 

<div id="bigbanner" style="background-image:url(/res/front/4/images/banner.jpg);">
</div>

<div id="topnews_scroll">
<div id="topnews">
 <div class="nav">
  <ul>
  	<?php
  		$uri = '/?'.$app->server->query_string;
  		$menu = array
  		(
	  		'首页'=>helper::createLink('guest','index',array('raceID'=>$raceID)),
	  		'活动方案'=>helper::createLink('guest','show',array('raceID'=>$raceID,'contentID'=>11)),
	  		'我要报名'=>helper::createLink('guest','reg',array('raceID'=>$raceID)),
	  		'我要投票'=>helper::createLink('guest','browse',array('raceID'=>$raceID,'type'=>2,'numPerPage'=>16)),
	  		'录歌攻略'=>helper::createLink('guest','show',array('raceID'=>$raceID,'contentID'=>10))
  		);
			foreach($menu as $name => $url):  		
  	?>
   <li<?php if($uri == $url) echo ' class="current"'; ?>><a href="<?php echo $url;?>"><?php echo $name;?></a></li>
   <?php endforeach;?>   
  </ul>
 </div>

</div>
</div>


