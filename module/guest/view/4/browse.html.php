<?php include('head.html.php');?>

<div id="topnews_scroll">
<div id="topnews" style="width:950px;">

<div class="body">
<div id="votecontent">
   <div class="search">
   	<form method="post">
       <span>选手姓名或编号:</span>&nbsp;&nbsp;<input class="text" type="text" name="name" value="<?php echo $app->post->name; ?>" /><input class="button" type="submit" value="搜索" />       
     </form>
    
     <div class="vtime">投票起止时间:<?php echo $race->vstart,'~',$race->vend;?></div>
   </div>
   <div class="votelist pc">
<?php 
ob_start();
if($pager->pageTotal > 1):
	$pageTotal = $pager->pageTotal;
	//$numPerPage = 1;//每页显示记录数,通过GET传递
	$pageID = $pager->pageID;//当前页
	$showPage = 7;//显示多少个页链接
	$firstShowPage = $pageID - ($showPage>>1);//计算第一个显示的链接页，保证当前页剧中显示 
	if($firstShowPage<1) $firstShowPage = 1;
	$lastShowPage = $firstShowPage + $showPage - 1;//计算最后一个显示的链接页
	if($lastShowPage > $pageTotal) $lastShowPage = $pageTotal;
	//echo "[$firstShowPage,$lastShowPage]";
	if($lastShowPage == $pageTotal)//最有一页到顶,firstShowPage可能需要向左扩展
	{
		$firstShowPage = $pageTotal - $showPage + 1;
	}
	if($firstShowPage<1) $firstShowPage = 1;
	//echo "[$firstShowPage,$lastShowPage]";
	$prevPage = $pageID - 1;//前一页
	if($prevPage < 1) $prevPage = 1;
	$nextPage = $pageID + 1;//后一页
	if($nextPage > $pageTotal) $nextPage = $pageTotal;
?>    		
<div class="page" id="yw0">
<a class="first" href="<?php echo helper::createLink('guest','browse',array('raceID'=>$raceID,'type'=>$type,'numPerPage'=>$numPerPage,'pageNum'=>1));?>">&lt;&lt;</a>
<a class="previous" href="<?php echo helper::createLink('guest','browse',array('raceID'=>$raceID,'type'=>$type,'numPerPage'=>$numPerPage,'pageNum'=>$prevPage));?>">&lt;</a>
<?php $count = $pager->pageTotal>$showPage?$pager->pageTotal:$showPage;?>
<?php for($i=$firstShowPage;$i<=$lastShowPage;$i++):?>
<a class="page<?php if($pageID==$i) echo ' new_current';?>" href="<?php echo helper::createLink('guest','browse',array('raceID'=>$raceID,'type'=>$type,'numPerPage'=>$numPerPage,'pageNum'=>$i));?>"><?php echo $i;?></a>
<?php endfor;?>
<a class="next" href="<?php echo helper::createLink('guest','browse',array('raceID'=>$raceID,'type'=>$type,'numPerPage'=>$numPerPage,'pageNum'=>$nextPage));?>">&gt;</a>
<a class="last" href="<?php echo helper::createLink('guest','browse',array('raceID'=>$raceID,'type'=>$type,'numPerPage'=>$numPerPage,'pageNum'=>$pageTotal));?>">&gt;&gt;</a>
</div>    
<?php endif; ?>
<?php
$pageHtml = ob_get_contents();
ob_end_clean();
?>

<?php echo $pageHtml; ?>

	<ul class="listitem">
<?php 
   foreach($players as $player):
   	$faceUrl = helper::createLink('guest','detail',array('raceID'=>$raceID,'playerID'=>$player->id));
   ?>    
    <li>
        <div class="img">
            <a target="_blank" href="<?php echo $faceUrl;?>">
                <img src="<?php if($player->face&&file_exists($player->face)) echo path2url($player->face);else echo '/res/front/4/images/c.gif';?>" alt="" title=""></a></div>
     <dl>
         <dt><?php echo $player->id;?> <a target="_blank" href="<?php echo $faceUrl;?>"><?php echo $player->name;?></a></dt>
      <dd>
          <p><?php if(isset($works[$player->id])) echo $works[$player->id]->name;?></p>
      </dd>
     </dl>

         <div class="support"><span><?php echo $player->vote;?></span></div>
     
    
     <input class="votebtn" value="试听投票" type="button" onclick="window.open('<?php echo $faceUrl;?>')">     
    </li>
<?php endforeach;?>
</ul>

<?php echo $pageHtml; ?>
       
   </div>
 </div>
 </div>
</div>
</div>

<?php include('foot.html.php');?>