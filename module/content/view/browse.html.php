<form id="pagerForm" method="post" action="<?php echo helper::createLink('content','browse'); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="race" value="<?php echo $app->post->race;?>" />
	<input type="hidden" name="title" value="<?php echo $app->post->title;?>" />
</form>


<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('content','browse'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				<td>
  				<?php echo html::select('race', $raceMap, $app->post->race, 'class="combox"', '请选择活动'); ?>
				</td>
				<td>
					<?php echo $lang->content->title;?>：<input type="text" name="title" value="<?php echo $app->post->title;?>"/>
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
			<li><a class="add" href="<?php echo helper::createLink('content','create',array('raceID'=>$app->post->race)); ?>" target="dialog" width="900"  height="450"><span>添加</span></a></li>
			<li><a class="edit" href="<?php echo helper::createLink('content','edit',array(),array('contentID'=>'{sid_content}')); ?>" target="dialog" width="900" height="450"><span>编辑</span></a></li>  
			<li><a class="icon" href="<?php echo helper::createLink('content','preview',array('flag'=>1),array('contentID'=>'{sid_content}')); ?>" target="navTab" external="true"><span>预览</span></a></li>  
			<li><a class="delete" href="<?php echo helper::createLink('content','delete',array(),array('contentID'=>'{sid_content}')); ?>" target="ajaxTodo" title="确定要删除吗?"><span>删除</span></a></li>
			<li><a class="icon" href="<?php echo helper::createLink('content','publish',array(),array('contentID'=>'{sid_content}')); ?>" target="ajaxTodo" title="确定要发布吗?"><span>发布</span></a></li>
			<li><a class="icon" href="<?php echo helper::createLink('content','cancelPublish',array(),array('contentID'=>'{sid_content}')); ?>" target="ajaxTodo" title="取消发布吗?"><span>取消发布</span></a></li>	
			<li><a class="icon" href="<?php echo helper::createLink('content','top',array(),array('contentID'=>'{sid_content}')); ?>" target="ajaxTodo" title="确定要置顶吗?"><span>置顶</span></a></li>
			<li><a class="icon" href="<?php echo helper::createLink('content','cancelTop',array(),array('contentID'=>'{sid_content}')); ?>" target="ajaxTodo" title="取消置顶吗?"><span>取消置顶</span></a></li>
	
	
		</ul>
	</div>
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>
          <th><?php echo $lang->id; ?></th>
          <th><?php echo $lang->content->race; ?></th>
           <th><?php echo $lang->content->type; ?></th>
          <th><?php echo $lang->content->publish; ?></th>
           <th><?php echo $lang->content->topx; ?></th>
          <th><?php echo $lang->content->title; ?></th>
          <th><?php echo $lang->content->ptime; ?></th>       
          <th><?php echo $lang->content->acnt; ?></th>
                            
			</tr>
		</thead>
		<tbody>
        <?php $k=1; foreach($contents as $content):?>
        <tr target="sid_content" rel="<?php  echo $content->id; ?>">        	
           <td><?php echo $content->id;?></td>
           <td><?php echo $raceMap[$content->race];?></td>
           <td><?php echo $lang->content->typeMap[$content->type];?></td>
          <td><?php echo $lang->content->publishMap[$content->publish];?></td>
          <td><?php echo $lang->content->topMap[$content->topx];?></td>
        	<td><?php echo $content->title;?></td>
        	<td><?php echo $content->ptime;?></td>         
          <td><?php echo $content->acnt;?></td>
    
        </tr>
        <?php  endforeach; ?>
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