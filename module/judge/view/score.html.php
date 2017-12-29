<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('judge','score',array('playerID'=>$playerID)); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr height='50'>
				<td>作品：</td>
				<td>
					<?php if($work&&$work->path&&file_exists($work->path)):?>
						<?php if(!isset($mobile)): ?>
							<embed width="400" height="50" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" allowfullscreen="false" allowscriptaccess="sameDomain" bgcolor="#ffffff" scale="scale" quality="high" menu="false" loop="false" wmode="transparent" src="/res/front/4/swf/player.swf?url=<?php echo path2url($work->path);?>&Autoplay=0" />      
						<?php else: ?>
							<video width="400" height="50" src="<?php  echo path2url($work->path);?>" controls="controls">您的浏览器不支持 video 标签。</video>
						<?php endif;?>
				 <?php else: ?>
				 		 作品不存在
				<?php endif;?>				
				</td>
			</tr>
			<tr height='50'>
				<td>选手得分：</td>
				<td>
					<input name="score" class="required" type="text" size="30" value="<?php echo $score?$score->score:'';?>"/>
				</td>
			</tr>			
		</table>
		<!--以上$playeID和$score两个参数来自于contol.php中的Score()函数中的view中参数-->
		</div>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>