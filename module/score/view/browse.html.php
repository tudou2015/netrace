<form id="pagerForm" method="post" action="<?php echo helper::createLink('score','browse'); ?>">
	<input type="hidden" name="race" value="<?php echo $app->post->race;?>" />	
</form>

<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('score','browse'); ?>" method="post">
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
	
			<li><a class="edit" href="<?php echo helper::createLink('score','scoreAll',array('raceID'=>$app->post->race)); ?>" target="ajaxTodo" title="确定成绩合成操作吗?"><span>合成总分</span></a></li>
				
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>				  
          <th><?php echo $lang->score->playername;?></th>
          <th><?php echo $lang->score->workstype;?></th>
          <th><?php echo $lang->score->worksname;?></th>
          <th><?php echo $lang->score->org;?></th>
          <th><?php echo $lang->score->vote;?></th>
          <th><?php echo $lang->score->score;?></th>
			</tr>
		</thead>
		<tbody>
        <?php
         foreach($players as $playerID => $player):
         	$work = $works[$playerID];         
         ?>
        <tr target="sid_player" rel="<?php echo $player->id;?>">        
          <td><?php echo $player->name;?></td>                    
          <td><?php echo $lang->score->typeMap[$work->type];?></td>
          <td><?php echo $work->name;?></td>
          <td><?php echo $orgMap[$player->org];?></td>          
          <td><?php echo $player->vote;?></td>
          <td><?php echo $player->score;?></td>
        </tr>
        <?php endforeach;?>
		</tbody>
	</table>
</div>



