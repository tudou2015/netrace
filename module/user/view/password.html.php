<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('user','password',array('userID'=>$user->id)); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			<p>
				<label>用 户 名：</label>
				<?php echo $user->name;?>
			</p>
<?php if($app->session->user->id == $user->id): ?>			
			<p>
				<label>原密码：</label>
				<input name="oldpassword" class="required" type="password" size="30" />
			</p>
<?php endif; ?>
			<p>
				<label>密码：</label>
				<input name="newpassword1" class="required" type="password" size="30" />
			</p>
			<p>
				<label>确认密码：</label>
				<input name="newpassword2" class="required" type="password" size="30" />
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
