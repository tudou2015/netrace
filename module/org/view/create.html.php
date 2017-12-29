<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('org','create'); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<p>
				<label><?php echo $lang->org->code;?></label>
				<input name="code" class="required" type="text" size="30" />
			</p>
			<p>
				<label><?php echo $lang->org->name;?></label>
				<input name="name" class="required" type="text" size="30" />
			</p>
						
			<p>
				<label><?php echo $lang->org->address;?></label>
				<input name="address" type="text" size="30" />
			</p>
			<p>
				<label><?php echo $lang->org->phone;?></label>
				<input name="phone" type="text" size="30" />
			</p>
			<p>
				<label><?php echo $lang->org->teacher;?></label>
				<input name="teacher" type="text" size="30" />
			</p>
			
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
