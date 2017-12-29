<?php include('head.html.php');?>
<iframe frameborder='0' height='0' name='hiddenwin' id='hiddenwin' scrolling='no' class='hidden'></iframe>
<form method="post" target="hiddenwin" enctype="multipart/form-data" onsubmit="return checkAgreement();">
<div id="registration">
 <div class="content">
 <div class="inner">
 <div class="form  up_video">
   
   <li>      
    <label class="label">报名起止时间:</label>
    <?php echo $race->rstart,'~',$race->rend;?>
  </li>
   
  <li>  	
		<label class="label">单&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;位:</label>
		        <select class="select" name="org">
		<option selected="selected" value="">请选择所属单位</option>
		<?php foreach($orgMap as $orgID => $orgName):?>
		<option value="<?php echo $orgID;?>"><?php echo $orgName;?></option>
		<?php endforeach;?>
		</select>*
   </li>
   
   <li><label class="label">学&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;号:</label>
       <input class="text" name="number" placeholder="请输入您的学号" type="text">*
   </li>
   
   <li><label class="label">姓&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;名:</label>
       <input class="text" name="name" placeholder="您的姓名" type="text">*
   </li>
   
   <li><label class="label">性&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;别:</label>       
     <select class="select" name="sex">
			<option value="" selected="selected">请选择您的性别</option>
			<?php foreach($lang->player->sexMap as $code => $name):?>
			<option value="<?php echo $code;?>"><?php echo $name;?></option>
			<?php endforeach;?>
		</select>*    
   </li>
   
   <li><label class="label">身&nbsp;份&nbsp;证&nbsp;号:</label>
       <input class="text" name="idcode" placeholder="您的身份证号码" type="text">*
   </li>
   
   <li><label class="label">联系方式:</label>
   	<input class="text" name="phone" placeholder="请留下您的最常用联系方式" type="text">*
   </li>
   
   <li><label class="label">指导教师:</label>
   	<input class="text" name="teacher" placeholder="您的指导教师" type="text">
   </li>
   
   <li><label class="label">参赛宣言:</label>
   	<textarea class="textarea" name="sdeclare" rows="10" cols="10" placeholder="请简要说明下您的参赛宣言~"></textarea>*
   </li>
   
   <li><label class="label">选手照片:</label>
   	<input name="face" placeholder="选择照片" type="file" class="file">*(照片大小不超过1MB)
   </li>

   <li><label class="label">作品名称:</label>
   	<input name="wname" class="text longtext" placeholder="填写作品名称" type="text">*
   </li>

   <li><label class="label">参赛作品:</label>
   	<input name="wfile" placeholder="上传作品" type="file" class="file" />*
   </li>

   <li class="tip">      
    <p><img src="/res/front/4/images/error.jpg" alt="" title=""> 上传作品要求：</p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;1.参赛作品必须为<strong><?php echo $race->ext;?>类型</strong>，大小限制为<strong><?php echo $race->size;?>M以内。</strong></p>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;2.<b>不接受清唱或伴奏带。</b></p>
  </li>
  
 </div>
     <div class="btn">
     	<div class="agreement">
         <label>
                <input id="agreement" type="checkbox">我已经阅读并同意</label>
                <span class="xieyi" onclick='xuanshouchengnuo()'>《参赛承诺书》</span>
     </div>
  	 <input type="hidden" name="race" value="<?php echo $raceID;?>" />
     <input class="submitbtn" value="" type="submit" />

     </div>
 
 </div>
</div>
</div>
</form>
   
<script type="text/javascript" src="/res/front/4/js/jquery.js"></script>
<link rel='stylesheet' href='/res/admin/plugin/colorbox/colorbox.css?v=1361791026' type='text/css' media='screen' />
<script src='/res/admin/plugin/colorbox/min.js?v=1361791026' type='text/javascript'></script>
  
<script type="text/javascript">
	function xuanshouchengnuo(){
$.colorbox({href:'<?php echo helper::createLink('guest','promise',array('raceID'=>$raceID));?>',width:800, height:600, iframe:true, transition:'elastic', speed:500, scrolling:true});
}

function checkAgreement()
{
	var v=$('#agreement').prop('checked');
	if(!v){alert('必须同意参赛承诺书!');}
	return v;
}

function checkTime()
{
<?php
	$now = date('Y-m-d');
	if($now < $race->rstart):
?>
	alert('报名还未开始[报名起止时间:<?php echo $race->rstart,'~',$race->rend;?>]!');
	$(':input').attr('disabled','disabled');
<?php elseif($race->rend < $now):?>
	alert('报名已经截止[报名起止时间:<?php echo $race->rstart,'~',$race->rend;?>]!');
	$(':input').attr('disabled','disabled');
<?php else:; ?>
	$(':input').removeAttr('disabled');
<?php endif;?>
}

checkTime();
</script>   
   
   
<?php include('foot.html.php');?>