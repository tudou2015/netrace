<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('user','right',array('userID'=>$user->id)); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
			<table class='list' width='100%' layoutH="33">				
			<tbody>					
			<tr>
				<td width='80'>用户名</td>
				<td><?php echo $user->name;?></td>
			</tr>			
			<tr>
				<td>单位</td>
				<td><?php echo $orgTree;?></td>
			</tr>
			<tr>				
				<td>专业</td>
				<td><?php echo $profTree;?></td>
			</tr>
			<tr>				
				<td>季节</td>
				<td><?php echo $ysTree;?></td>
			</tr>
			<tr>				
				<td>菜单</td>
				<td><?php echo $menuTree;?></td>
			</tr>
			</tbody>
			</table>
		<div class="formBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">保存</button></div></div></li>
				<li>
					<div class="button"><div class="buttonContent"><button type="button" class="close">取消</button></div></div>
				</li>
			</ul>
		</div>
	</form>
</div>
