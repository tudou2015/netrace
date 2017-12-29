<div class="pageContent">
	<form method="post" enctype="multipart/form-data" action="<?php echo helper::createLink('player','edit',array('playerID'=>$player->id)); ?>" class="pageForm required-validate" onsubmit="return iframeCallback(this, dialogAjaxDone);">
		<div class="pageFormContent" layoutH="56">
			
			<p>
				<label>所属活动：</label>
				<?php echo $raceMap[$player->race]; ?>				
			</p>
			<p>
				<label>所属单位：</label>
				<?php echo html::select('org',$orgMap,$player->org,'class="required combox"');?>			
			</p>
			<p>
				<label>学号：</label>
				<input name="number" class="number" type="text" size="30" value="<?php echo $player->number;?>"/>
			</p>
	    <p>
				<label>选手姓名：</label>
				<input name="name" class="required" type="text" size="30" value="<?php echo $player->name;?>"/>
			</p>
			<p>
				<label>性别：</label>
				<?php echo html::select('sex', $lang->player->sexMap,$player->sex, 'class="required combox"', '请选择'); ?>
			</p>
			<p>
				<label>身份证号：</label>
				<input name="idcode" class="required" type="text" size="30" value="<?php echo $player->idcode;?>"/>
			</p>
			<p>
				<label>手机号码：</label>
				<input name="phone" class="required" type="text" size="30" value="<?php echo $player->phone;?>"/>
			</p>
			<p>
				<label>指导教师：</label>
				<input name="teacher"  type="text" size="30" value="<?php echo $player->teacher;?>" />
			</p>
			<p>
				<label>参赛宣言：</label>
				<input name="sdeclare" class="required" type="text" size="30" value="<?php echo $player->sdeclare;?>"/>
			</p>
			<p>
				<label>照片：</label>
				<input type='file' name='face' />			
			<?php if(file_exists($player->face)):?>
			<a target="_blank" href="<?php echo path2url($player->face),'?_=',rand();?>">查看</a> <!--连带图片+随机数，强制浏览器发送是否最新请求-->				
			<?php endif;?>
			</p>
			<p>
				<label>作品名称：</label>
				<input name="wname" class="required" type="text" size="30" value="<?php if($work) echo $work->name;?>"/>
			</p>
			<p>
				<label>参赛作品：</label>
				<input type='file' name='wfile' />			
			<?php if($work && file_exists($work->path)):?>
			<a target="_blank" href="<?php echo path2url($work->path),'?_=',rand();?>">查看</a> <!--连带图片+随机数，强制浏览器发送是否最新请求-->				
			<?php endif;?>
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