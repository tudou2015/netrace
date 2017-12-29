<div class="pageContent">	
		<div class="pageFormContent" layoutH="36">
			<table width="100%">
				<thead>
					<tr>
						<td>			
				<?php echo $content->title;?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>				
							<textarea class="editor" name="description" rows="100" cols="120">	
			<?php echo  base64_decode($content->body);?>
							</textarea>
						</td>
					</tr>
				<tbody>
				</table>
		</div>
</div>
