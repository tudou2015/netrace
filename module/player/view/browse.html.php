<form id="pagerForm" method="post" action="<?php echo helper::createLink('player','browse'); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="race" value="<?php echo $app->post->race;?>" />
	<input type="hidden" name="org" value="<?php echo $app->post->org;?>" />
	<input type="hidden" name="name" value="<?php echo $app->post->name;?>" />
	<input type="hidden" name="idcode" value="<?php echo $app->post->idcode;?>" />
	<input type="hidden" name="number" value="<?php echo $app->post->number;?>" />
	<input type="hidden" name="audit" value="<?php echo $app->post->audit;?>" />
	
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('player','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
  				<?php echo html::select('race', $raceMap, $app->post->race, 'class="combox"', '选择活动'); ?>
				</td>
				<td>
  				<?php echo html::select('org', $orgMap, $app->post->org, 'class="combox"', '选择单位'); ?>
				</td>
				<td>
					选手姓名：<input type="text" name="name" value="<?php echo $app->post->name;?>"/>
				</td>
					<td>
					证件号码:<input type="text" name="idcode" value="<?php echo $app->post->idcode;?>"/>
				</td>
				<td>
					学号:<input type="text" name="number" value="<?php echo $app->post->number;?>"/>
				</td>
				<td>
				<?php echo html::select('audit', $lang->player->auditMap, $app->post->audit, 'class="combox"', '审核状态'); ?>
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
			<li><a class="add" href="<?php echo helper::createLink('player','create'); ?>" target="dialog" height="500" width="600"><span>添加</span></a></li>
			<li><a class="edit" href="<?php echo helper::createLink('player','edit',array(),array('playerID'=>'{sid_player}')); ?>" target="dialog" height="500" width="600"><span>修改</span></a></li>
			<li><a class="delete" href="<?php echo helper::createLink('player','delete',array(),array('playerID'=>'{sid_player}')); ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
			<li><a class="icon" href="<?php echo helper::createLink('player','audit',array(),array('playerID'=>'{sid_player}')); ?>" target="ajaxTodo" title="确定该选手通过审核吗?"><span>审核</span></a></li>
			<li><a class="icon" href="<?php echo helper::createLink('player','state',array(),array('playerID'=>'{sid_player}')); ?>" target="ajaxTodo" title="确定该选手晋级吗?"><span>晋级</span></a></li>
			<li><a class="icon" href="<?php echo helper::createLink('player','cancelstate',array(),array('playerID'=>'{sid_player}')); ?>" target="ajaxTodo" title="确定取消该选手晋级吗?"><span>取消晋级</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>				  
          <th><?php echo $lang->player->org;?></th>
          <th><?php echo $lang->player->number;?></th>
          <th><?php echo $lang->player->name;?></th>
          <th><?php echo $lang->player->sex;?></th>
          <th><?php echo $lang->player->age;?></th>
          <th><?php echo $lang->player->idcode;?></th>
          <th><?php echo $lang->player->teacher;?></th>
          <th><?php echo $lang->player->face;?></th>
          <th><?php echo $lang->player->wname;?></th>
          <th><?php echo $lang->player->wfile;?></th>
          <th><?php echo $lang->player->audit;?></th>
          <th><?php echo $lang->player->vote;?></th>
          <th><?php echo $lang->player->state;?></th>
			</tr>
		</thead>
		<tbody>
        <?php
         foreach($players as $player):
         	$work = isset($works[$player->id])? $works[$player->id]:null;
         ?>
        <tr target="sid_player" rel="<?php echo $player->id;?>">        
          <td><?php echo $orgMap[$player->org];?></td>
          <td><?php echo $player->number;?></td>
          <td><?php echo $player->name;?></td>
          <td><?php echo $lang->player->sexMap[$player->sex];?></td>
          <td><?php echo $player->age;?></td>
          <td><?php echo $player->idcode;?></td>
          <td><?php if($player->teacher) echo $player->teacher;?></td>
          <td><?php if(file_exists($player->face)):?><a target="_blank" href="<?php echo path2url($player->face),'?_=',rand();?>"><?php echo basename($player->face);?></a><?php endif;?></td>
          <td><?php echo $work?$work->name:'';?></td>
          <td><?php if($work&&file_exists($work->path)):?><a target="_blank" href="<?php echo path2url($work->path),'?_=',rand();?>"><?php echo basename($work->path);?></a><?php endif;?></td>
          <td><?php echo $lang->player->auditMap[$player->audit];?></td>
          <td><?php echo $player->vote;?></td>
          <td><?php echo $lang->player->stateMap[$player->state];?></td>
        </tr>
        <?php endforeach;?>
		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>每页</span>
			<select class="combox" name="numPerPage" onchange="navTabPageBreak({numPerPage:this.value})">
				<?php foreach($pager->recPerPageMap as $value):?>
				<option value="<?php echo $value;?>" <?php if($app->post->numPerPage == $value) echo 'selected';?>><?php echo $value;?></option>
				<?php endforeach;?>
			</select>
			<span>条，共<?php echo $pager->recTotal;?>条</span>
		</div>		
		<div class="pagination" targetType="navTab" totalCount="<?php echo $pager->recTotal;?>" numPerPage="<?php echo $pager->recPerPage;?>" pageNumShown="10" currentPage="<?php echo $app->post->pageNum;?>"></div>
	</div>
</div>
