<form id="pagerForm" method="post" action="<?php echo helper::createLink('user','browse'); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="type" value="<?php echo $app->post->type;?>" />
	<input type="hidden" name="name" value="<?php echo $app->post->name;?>" />
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('user','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
				<?php echo html::select('type', $lang->user->typeMap, $app->post->type, 'class="combox"', '全部类型'); ?>
				</td>
				<td>
					用户名：<input type="text" name="name" value="<?php echo $app->post->name;?>"/>
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
			<li><a class="add" href="<?php echo helper::createLink('user','create'); ?>" target="dialog"><span>添加</span></a></li>
			<li><a class="edit" href="<?php echo helper::createLink('user','password',array(),array('userID'=>'{sid_user}')); ?>" target="dialog"><span>修改密码</span></a></li>
			<li><a class="delete" href="<?php echo helper::createLink('user','delete',array(),array('userID'=>'{sid_user}')); ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
          <th><?php echo $lang->user->org;?></th>
          <th><?php echo $lang->user->type;?></th>
          <th><?php echo $lang->user->name;?></th>
          <th><?php echo $lang->user->visit;?></th>
          <th><?php echo $lang->user->ip;?></th>
          <th><?php echo $lang->user->last;?></th>
			</tr>
		</thead>
		<tbody>
        <?php
         foreach($users as $user):?>
        <tr target="sid_user" rel="<?php echo $user->id;?>">
          <td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', strlen($user->orgcode)-3),$orgMap[$user->org];?></td>
          <td><?php echo $lang->user->typeMap[$user->type];?></td>
          <td><?php echo $user->name;?></td>
          <td><?php if($user->visit) echo $user->visit;?></td>
          <td><?php echo $user->ip;?></td>
          <td><?php echo $user->last;?></td>
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
