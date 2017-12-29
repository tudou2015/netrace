<form id="pagerForm" method="post" action="<?php echo helper::createLink('race','browse'); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="name" value="<?php echo $app->post->name;?>" />
	
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('race','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				
				<td>
					活动名称：<input type="text" name="name" value="<?php echo $app->post->name;?>"/>
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
			<li><a class="add" href="<?php echo helper::createLink('race','create'); ?>" target="dialog" height="400"><span>添加</span></a></li>
			<li><a class="edit" href="<?php echo helper::createLink('race','edit',array(),array('raceID'=>'{sid_race}')); ?>" target="dialog" height="400"><span>修改</span></a></li>
			<li><a class="delete" href="<?php echo helper::createLink('race','delete',array(),array('raceID'=>'{sid_race}')); ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
			<li><a class="icon" close="refresh" href="<?php echo helper::createLink('race','judger',array(),array('raceID'=>'{sid_race}')); ?>" target="dialog" width='600' height='500' rel='race_judger'><span>设置评委</span></a></li>
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
				  <th><?php echo $lang->race->name;?></th>
          <th><?php echo $lang->race->rstart;?></th>
          <th><?php echo $lang->race->rend;?></th>
          <th><?php echo $lang->race->vstart;?></th>
          <th><?php echo $lang->race->vend;?></th>
          <th><?php echo $lang->race->type;?></th>
          <th><?php echo $lang->race->ext;?></th>
          <th><?php echo $lang->race->size;?></th>
          <th><?php echo $lang->race->judger;?></th>
          <th><?php echo $lang->race->acnt;?></th>
          
			</tr>
		</thead>
		<tbody>
        <?php
         foreach($races as $race):?>
        <tr target="sid_race" rel="<?php echo $race->id;?>">
        	<td><?php echo $race->name;?></td>
          <td><?php echo $race->rstart;?></td>
          <td><?php echo $race->rend;?></td>
          <td><?php echo $race->vstart;?></td>
          <td><?php echo $race->vend;?></td>
          <td><?php echo $this->lang->typeMap[$race->type];?></td>
          <td><?php echo $race->ext;?></td>
          <td><?php echo $race->size;?>MB</td>
          <td><?php 
          	if($race->judger)
          	{
	          	$judgers = explode(',',$race->judger); 
	          	$ret = array();
	          	foreach($judgers as $judger) 
	          	{
	          			$ret[]=$userMap[$judger];
	          	}          	
	            echo implode(',',$ret);
          	}
          	?></td>
          <td><?php echo $race->acnt;?></td>          
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

<!--
实现关闭对话框，更新父窗口功能
<li><a class="icon"><span>分配</span></a></li>
icon里需要有close属性，可选param属性
设置close属性为一个函数
点击窗口x关闭时，会调用close函数，并且以可选的param为参数
函数需要有返回值，返回值为true允许关闭,为false不允许关闭
 -->
<script type="text/javascript">
function refresh()
{
		dialogAjaxDone({"statusCode":"200"});
		return true;
}
</script>