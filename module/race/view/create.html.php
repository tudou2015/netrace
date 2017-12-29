<div class="pageContent">
	<form method="post" action="<?php echo helper::createLink('race','create'); ?>" class="pageForm required-validate" onsubmit="return validateCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			
			<p>
				<label>活动名称：</label>
				<input name="name" class="required" type="text" size="30" />
			</p>
			<p>
				<label>活动开始时间：</label>
				<input type="input" class="date required" name="rstart" />
			</p>
			<p>
				<label>活动结束时间：</label>
				<input type="input" class="date required" name="rend"/>
			</p>
			<p>
				<label>投票开始时间：</label>
				<input type="input" class="date required" name="vstart"/>
			</p>
			<p>
				<label>投票结束时间：</label>
				<input type="input" class="date required" name="vend" />
			</p>
			<p>
				<label>作品类型：</label>
				<?php echo html::select('type', $this->lang->typeMap, '', 'class="required combox"', '请选择'); ?>
			</p>
			<p>
				<label>作品扩展名：</label>
				<input name="ext" class="required" type="text" size="10" />
			</p>
			<p>
				<label>作品大小：</label>
				<input name="size" class="required" type="text" size="10" />
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