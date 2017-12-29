<?php include('head.html.php');?>
<DIV id=topnews_scroll2>
<DIV id=topnews>
	<DIV class=body>
		<DIV id=newsdetail>
			<DIV class=title>
				<label></label><h2><?php echo $content->title;?></h2>
				<div align="right">发布日期:<?php echo $content->ptime;?>&nbsp;&nbsp;访问次数:<?php echo $content->acnt;?></div>
			</DIV>
			<DIV class=newsbody>
				<?php echo base64_decode($content->body);?>
			</DIV>
		</DIV>
	</DIV>
</DIV>
</div>

<?php include('foot.html.php');?>