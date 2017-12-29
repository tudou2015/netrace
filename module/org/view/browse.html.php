<form id="pagerForm" method="post" action="<?php echo helper::createLink('org','browse'); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="code" value="<?php echo $app->post->code;?>" />
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('org','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
					<?php echo $lang->org->code;?>：<input type="text" name="code" value="<?php echo $app->post->code;?>"/>
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
			<li><a class="add" href="<?php echo helper::createLink('org','create'); ?>" target="dialog" height="450"><span>添加</span></a></li>
			<li><a class="edit" href="<?php echo helper::createLink('org','edit',array(),array('orgID'=>'{sid_org}')); ?>" target="dialog" height="450"><span>修改</span></a></li>
			<li><a class="delete" href="<?php echo helper::createLink('org','delete',array(),array('orgID'=>'{sid_org}')); ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
          <th><?php echo $lang->id;?></th>
          <th><?php echo $lang->org->code;?></th>
          <th><?php echo $lang->org->name;?></th>
       
          <th><?php echo $lang->org->address;?></th>
          <th><?php echo $lang->org->phone;?></th>
          <th><?php echo $lang->org->teacher;?></th>
                   
			</tr>
		</thead>
		<tbody>
        <?php foreach($orgs as $org):
        	$flag = strlen($org->code) == 7 ? true : false;
        ?>
        <tr target="sid_org" rel="<?php echo $org->id;?>">
          <td><?php echo $org->id;?></td>
        	<td><?php echo $org->code;?></td>
        	<td><?php echo str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', strlen($org->code)-3), $org->name;?></td>
         
          <td><?php if($flag) echo $org->address;?></td>
          <td><?php if($flag) echo $org->phone;?></td>
          <td><?php if($flag) echo $org->teacher;?></td>
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