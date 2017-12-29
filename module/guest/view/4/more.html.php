<?php include('head.html.php');?>
<div id="topnews_scroll2">
    
    <div class="top_ba_img baise">
        <div class="saishi imgh">  
        </div>
        <div class="inner">
        </div>
    </div>        
    
    
    <div class="body_m">
 <div class="body">
 <div id="newscontent">

  <ul class="list">
      	 <?php 
      	 	$i=0;
      	 foreach($contents as $content):
      	 $i++;
      	 ?>
      <li><label></label><span><?php echo $content->ptime;?></span><a target="_blank" href="<?php echo helper::createLink('guest','show',array('raceID'=>$raceID, 'contentID'=>$content->id))?>"><?php echo $content->title;?></a></li> 
      <?php endforeach;?>
      <?php while($i++ < 10): ?>
      	<li></li>
      <?php endwhile;?>
  </ul>
  
 </div> 
 </div>
 </div>
</div>
</div>



<?php include('foot.html.php');?>