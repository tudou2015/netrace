<form id="pagerForm" method="post" action="<?php echo helper::createLink('race','judger',array('raceID'=>$raceID)); ?>">
	<input type="hidden" name="pageNum" value="<?php echo $app->post->pageNum;?>" />
	<input type="hidden" name="numPerPage" value="<?php echo $app->post->numPerPage;?>" />
	<input type="hidden" name="type" value="<?php echo $app->post->type;?>" />
	<input type="hidden" name="name" value="<?php echo $app->post->name;?>" />
</form>


<div class="pageHeader">
	<form onsubmit="return dialogSearch(this);" action="<?php echo helper::createLink('race','judger',array('raceID'=>$raceID)); ?>" method="post">
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
	<table class="table" width="100%" layoutH="108">
		<thead>
			<tr>
          <th><?php echo $lang->user->org;?></th>
          <th><?php echo $lang->user->type;?></th>
          <th><?php echo $lang->user->name;?></th>
          <th><?php echo $lang->user->set;?></th>
			</tr>
		</thead>
		<tbody>
        <?php
         foreach($users as $user):
         	$check = in_array($user->id,$judgerMap);
         ?>
        <tr target="sid_user" rel="<?php echo $user->id;?>">
          <td><?php echo $orgMap[$user->org];?></td>
          <td><?php echo $lang->user->typeMap[$user->type];?></td>
          <td><?php echo $user->name;?></td>
          <td><input type="checkbox" id='d<?php echo $user->id;?>' onchange='savex(this)' <?php if($check) echo 'checked';?>></td>
        </tr>
        <?php endforeach;?>
		</tbody>
	</table>
	<div class="panelBar">
		<div class="pages">
			<span>每页</span>
			<select class="combox" name="numPerPage" onchange="dialogPageBreak({numPerPage:this.value})">
				<?php foreach($pager->recPerPageMap as $value):?>
				<option value="<?php echo $value;?>" <?php if($app->post->numPerPage == $value) echo 'selected';?>><?php echo $value;?></option>
				<?php endforeach;?>
			</select>
			<span>条，共<?php echo $pager->recTotal;?>条</span>
		</div>		
		<div class="pagination" targetType="dialog" totalCount="<?php echo $pager->recTotal;?>" numPerPage="<?php echo $pager->recPerPage;?>" pageNumShown="10" currentPage="<?php echo $app->post->pageNum;?>"></div>
	</div>
</div>


<script type="text/javascript">
function savex(o)
{
	var id=$(o).attr('id');
	id = id.substring(1);
	var v=$(o).prop("checked");
		
	$.post("<?php echo helper::createLink('race','setJudge', array('raceID' => $raceID)); ?>", {userID:id, check:v}, 
	function(result){
		if(result.length > 0) {
			alert(result);
			$(o).prop("checked",!v); //还原
		}
	}
	);
}
</script>