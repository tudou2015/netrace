<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="utf-8" />
		<title><?php echo $race->name;?></title>
    <link href="/res/front/4/css/global.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="/res/front/4/js/jquery.js"></script>
</head>
<body>
	<div id="headerscroll">
	 <div id="header">
	 <h1 id="logo"><a href="<?php echo helper::createLink('guest','index',array('raceID'=>$raceID));?>"></a></h1>
	 </div>
	</div> 

	<div id="video_content">
	 <div class="inner">
	  <div class="title">
	  		<div>
	      <h2><?php if($work) echo $work->name;?>
	          <span class="span">得票数：<span id="praise"><?php echo $player->vote;?></span></span>
	      </h2>
	   <p class="p"><?php echo $org->name,'  ',$player->name; ?></p>
	   <p class="p">参赛宣言：<?php echo $player->sdeclare; ?></p>     
	   <div class="vote">
					      <div id="verifyDiv">
					      	请输入验证码:
					      	<ul>
					      		<li><input type="text" id="code"/>看不清，可点击图片更换验证码</li>					      	
					      		<li><img id="verifyImg" width="230" height="100" src="" onclick="fleshVerify()" style="border:1px solid black" /></li>
					      		<li><button type="button" onclick="vote(2)">确定</button></li>
					      	</ul>
					      </div> 
	              <input type="button" class="votebtn<?php if(!isset($permitVote)) echo ' disabled';?>"  onclick="vote(1)"/> 
	                <div class="tip none"><span id="vote_msg"></span><span class="span1"></span></div>
	   </div>
	  </div>
		</div>
	
	  <div class="video">
			<center>
				<?php if(!isset($mobile)): ?>
				<embed width="800" height="400" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowfullscreen="false" allowscriptaccess="sameDomain" bgcolor="#ffffff" scale="scale" quality="high" menu="false" loop="false" wmode="transparent" src="/res/front/4/swf/player.swf?url=<?php if($work&&$work->path&&file_exists($work->path)) echo path2url($work->path);?>&Autoplay=0" />      
				<?php else: ?>
				<video width="800" height="400" src="<?php if($work&&$work->path&&file_exists($work->path)) echo path2url($work->path);?>" controls="controls">您的浏览器不支持 video 标签。</video>
				<?php endif;?>
			</center>
	  </div>
	  
	  <div class="sharecontent">
		  	  <label class="label">分享至：</label>
					<!-- JiaThis Button BEGIN -->
					<div class="jiathis_style">
						<a class="jiathis_button_qzone"></a>
						<a class="jiathis_button_tsina"></a>
						<a class="jiathis_button_tqq"></a>
						<a class="jiathis_button_weixin"></a>
						<a class="jiathis_button_renren"></a>
						<a href="http://www.jiathis.com/share?uid=1991118" class="jiathis jiathis_txt jtico jtico_jiathis" target="_blank"></a>
						<a class="jiathis_counter_style"></a>
					</div>
					
					<script type="text/javascript">
					var jiathis_config = {data_track_clickback:'true'};
					</script>
					<script type="text/javascript" src="http://v3.jiathis.com/code_mini/jia.js?uid=1407407337995935" charset="utf-8"></script>
					<!-- JiaThis Button END -->  
		</div>
	 </div>
	</div>
    	
<?php include('foot.html.php'); ?>

<script type="text/javascript">
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

function vote(t)
{
<?php
	$now = date('Y-m-d');
	if($now < $race->vstart):
?>
	alert('投票还未开始[投票起止时间:<?php echo $race->vstart,'~',$race->vend;?>]!');
<?php elseif($race->vend < $now):?>
	alert('投票已经截止[投票起止时间:<?php echo $race->vstart,'~',$race->vend;?>]!');
<?php else: ?>
	if(t < 1 || t > 2) return;
	if(t == 1)
	{
		$('#verifyDiv').css('z-index','100');
		$('#verifyImg').attr('src','<?php echo helper::createLink('user','code',array('type'=>1)); ?>');
		return;
	}
	
	var code = $('#code').prop('value');
	$.post("<?php echo helper::createLink('guest','vote',array('raceID'=>$player->race,'playerID'=>$player->id),array('code'=>'"+code+"'));?>", 
			function(r){
				if(r.e) //有错误
				{
					alert(r.m);
				}
				else
				{
					$('#praise').html(r.v);//更新票数
					alert(r.m);
					//返回首页
					location.href='<?php echo helper::createLink('guest','index',array('raceID'=>$raceID));?>';
				}
			},'json');
<?php endif;?>
}
</script>