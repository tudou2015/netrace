<?php include('head.html.php');?>

		<UL class=appbox>
			<?php 
				$i=0;
				foreach($raceMap as $raceID => $raceName):
				$i++;
			?>
		  <LI onClick="window.open('<?php echo helper::createLink('guest','index',array('raceID'=>$raceID));?>')"><DIV><STRONG><EM><?php echo $raceName;?></EM></STRONG><P></P></DIV></LI>
		  <?php endforeach;?>
		  <?php while($i < 12):?>
				<LI><DIV><STRONG><EM></EM></STRONG><P></P></DIV></LI>
		  <?php 
		  	$i++;
		  endwhile;?>
		 </UL>

<?php include('foot.html.php');?>