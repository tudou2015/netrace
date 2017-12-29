<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('content','create',array('raceID'=>$raceID)); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<table>
				<tr>
				<td>
					<label><?php echo $lang->content->race;?></label>
				<?php 
				//echo html::select('race', $raceMap, '', 'class="combox"', '请选择'); 
				echo $raceMap[$raceID];
				?>
				</td>
				</tr>
			<tr>				
				<td>		
				<label><?php echo $lang->content->type;?></label>
				<?php echo html::select('type', $lang->content->typeMap, '', 'class="combox"', '请选择'); ?>	
	  </td></tr>
	  <tr><td>
				<label><?php echo $lang->content->title;?></label>
				<input name="title" class="required" type="text" size="100" />
			  </td></tr>
			<tr><td>
				<label><?php echo $lang->content->body;?></label>
				<textarea class="editor" name="body" rows="100" cols="80"
								upLinkUrl="<?php echo $uploadUrl; ?>" upLinkExt="zip,rar,txt" 
								upImgUrl="<?php echo $uploadUrl; ?>" upImgExt="jpg,jpeg,gif,png" 
								upFlashUrl="<?php echo $uploadUrl; ?>" upFlashExt="swf"
								upMediaUrl="<?php echo $uploadUrl; ?>" upMediaExt:"avi">
							</textarea>
				  </td></tr>
				</table>		
			
			
		</div>
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
