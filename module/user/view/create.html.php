<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('user','create'); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>单位：</label>
				<?php echo html::select('org', $orgMap, '', 'class="required combox"', '请选择'); ?>
			</p>
			<p>
				<label>用户类型：</label>
				<?php echo html::select('type', $lang->user->typeMap, '', 'class="required combox"', '请选择'); ?>
			</p>
			<p>
				<label>用 户 名：</label>
				<input name="name" class="required" type="text" size="30" />
			</p>
			<p>
				<label>密    码：</label>
				<input name="password1" class="required" type="password" size="30" />
			</p>
			<p>
				<label>确认密码：</label>
				<input name="password2" class="required" type="password" size="30" />
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
