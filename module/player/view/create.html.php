<div class="pageContent">

	<form method="post"  enctype="multipart/form-data" action="<?php echo helper::createLink('player','create'); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			
			<p>
				<label>所属活动：</label>
				<?php echo html::select('race', $raceMap, '', 'class="required combox"', '请选择'); ?>
			</p>
			<p>
				<label>所属单位：</label>
				<?php echo html::select('org',$orgMap,'','class="required combox"', '请选择');?>
			</p>
			<p>
				<label>学号：</label>
				<input name="number" class="number required" type="text" size="30" />
			</p>
			<p>
				<label>选手姓名：</label>
				<input name="name" class="required" type="text" size="30" />
			</p>
			<p>
				<label>性别：</label>
				<?php echo html::select('sex', $lang->player->sexMap, '', 'class="required combox"', '请选择'); ?>
			</p>
			<p>
				<label>身份证号：</label>
				<input name="idcode" class="required" type="text" size="30" />
			</p>
			<p>
				<label>联系方式：</label>
				<input name="phone" class="required" type="text" size="30" />
			</p>
		  <p>
				<label>指导教师：</label>
				<input name="teacher"  type="text" size="30" />
			</p>
			<p>
				<label>参赛宣言：</label>
				<input name="sdeclare" class="required" type="text" size="30" />
			</p>
			<p>
				<label>照片：</label>
				<input type='file' name='face' />			
			</p>
			<p>
				<label>作品名称：</label>
				<input name="wname" class="required" type="text" size="30" />
			</p>
			<p>
				<label>参赛作品：</label>
				<input type='file' name='wfile' />			
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