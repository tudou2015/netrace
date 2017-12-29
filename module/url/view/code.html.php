<div class="pageContent" layoutH="0">	  	  	  
		<fieldset>
			<legend>请输入要进行编码或解码的字符:</legend>						
			<textarea id="codeSrc" cols="160" rows="10"></textarea>
		</fieldset>
		
		<br />				
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="编码" onclick="encode()" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<input type="button" value="解码" onclick="decode()" />
		<br />
		<br />
		
		<fieldset>
			<legend>编码或解码结果:</legend>
			<textarea id="codeDst" cols="160" rows="10"></textarea>
		</fieldset>
		
		需要省校管理员权限才能执行本操作!
		
</div>

<script type="text/javascript">
	function encode()
	{
		v=$('#codeSrc').val();
		$.post('<?php echo helper::createLink('url','code'); ?>',{type:1,str:v},function(r){
					$('#codeDst').val(r);
			}
		);
	}
	
	function decode()
	{
		v=$('#codeSrc').val();
		$.post('<?php echo helper::createLink('url','code'); ?>',{type:2,str:v},function(r){
					$('#codeDst').val(r);
			}
		);		
	}
</script>