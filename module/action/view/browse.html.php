<form id="pagerForm" method="post" action="<?php echo helper::createLink('action','browse'); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="objectType" value="<?php echo $app->post->objectType;?>" />
	<input type="hidden" name="objectID" value="<?php echo $app->post->objectID;?>" />
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('action','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					<select class="combox" name="objectType">
						<option value="">全部对象类型</option>
						<?php foreach($lang->action->objectTypeMap as $key => $value):?>
						<option value="<?php echo $key;?>" <?php if($app->post->objectType == $key) echo 'selected';?>><?php echo $value;?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td>
					对象ID：<input type="text" name="objectID" value="<?php echo $app->post->objectID;?>"/>
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
			<li><a class="icon" href="<?php echo helper::createLink('action','detail',array(),array('actionID'=>'{sid_action}')); ?>" target="dialog"><span>详情</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
          <th><?php echo $lang->id;?></th>
          <th><?php echo $lang->action->objectType;?></th>
          <th><?php echo $lang->action->objectID;?></th>
          <th><?php echo $lang->action->actor;?></th>
          <th><?php echo $lang->action->action;?></th>
          <th><?php echo $lang->action->date;?></th>
          <th><?php echo $lang->action->ip;?></th>
          <th><?php echo $lang->action->comment;?></th>
			</tr>
		</thead>
		<tbody>
         <?php foreach($actions as $action):?>
        <tr target="sid_action" rel="<?php echo $action->id;?>">
          <td><?php echo $action->id;?></td>
          <td><?php if(isset($objectTypeMap[$action->objectType])) echo $objectTypeMap[$action->objectType];else echo $action->objectType;?></td>
          <td><?php if($action->objectID) echo $action->objectID;?></td>
          <td><?php if($action->actor) echo $userMap[$action->actor];else echo $action->actor;?></td>
          <td><?php if(isset($actionMap[$action->action])) echo $actionMap[$action->action];else echo $action->action;?></td>
          <td><?php echo $action->date;?></td>
          <td><?php echo $action->ip;?></td>
          <td><?php if(strlen($action->comment) > 50) echo substr($action->comment,0,50),'...';else echo $action->comment;?></td>
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
