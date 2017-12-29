<form id="pagerForm" method="post" action="<?php echo helper::createLink('judge','browse'); ?>">
	<input type="hidden" name="race" value="<?php echo $app->post->race;?>" />	
</form>
<!--/以上form为执行项目检索后，保持当前检索条件情况下加载browse/-->
<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('judge','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
  				<?php echo html::select('race', $raceMap, $app->post->race, 'class="combox"', '请选择活动'); ?>
				</td>			
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>
<div class="pageContent">
	<div class="panelBar">
		<ul class="toolBar">
			<!--跳转到judge文件夹下，执行score.html.php-->
			<li><a class="edit" href="<?php echo helper::createLink('judge','score',array(),array('playerID'=>'{sid_player}')); ?>" target="dialog"><span>评分</span></a></li>
			
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>				  
					<th><?php echo $lang->judge->xh;?></th>
          <th><?php echo $lang->judge->playername;?></th>
          <th><?php echo $lang->judge->workstype;?></th>
          <th><?php echo $lang->judge->worksname;?></th>
          <th><?php echo $lang->judge->score;?></th>
			</tr>
		</thead>
		<tbody>
        <?php
        	$xh = 1;
         foreach($players as $playerID => $player):
         if(!isset($works[$playerID])) continue;
         	$work = $works[$playerID];
         	$score = isset($scores[$playerID])? $scores[$playerID]->score : '';
         ?>
        <tr target="sid_player" rel="<?php echo $player->id;?>">        
        	<td><?php echo $xh++;?></td>
          <td><?php echo $player->name;?></td>                    
          <td><?php echo $lang->judge->typeMap[$work->type];?></td>
          <td><?php echo $work->name;?></td>
          <td><?php echo $score;?></td>
        </tr>
        <?php endforeach;?>
		</tbody>
	</table>
</div>



