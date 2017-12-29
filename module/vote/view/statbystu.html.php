<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('vote','statbyStu'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				
				<td>
  				<?php echo html::select('race', $raceMap,$app->post->race, 'class="combox"', '请选择活动'); ?>
				</td>
				
				<td>
					姓名：<input type="text" name="name" value="<?php echo $app->post->name;?>"/>
				</td>
					<td>
					证件号码:<input type="text" name="idcode" value="<?php echo $app->post->idcode;?>"/>
				</td>
			</tr>
		</table>
		<div class="subBar">
			<ul>
				<li><div class="buttonActive"><div class="buttonContent"><button type="submit">检索</button></div></div></li>
			</ul>
		</div>
	</div>
	</form>
</div>

<div class="pageContent">
	
	<table class="table" width="100%" layoutH="138">
		<thead>
			<tr>				  
          <th><?php echo $lang->vote->student;?></th>
          <th><?php echo $lang->vote->org;?></th>
          <th><?php echo $lang->vote->vote;?></th>
			</tr>
		</thead>
		<tbody>
        <?php 
	        	$sum = 0;
        	foreach($votes as $vote):?>
        <tr>                 
          <td><?php echo $vote->name;?></td>
          <td><?php echo $orgMap[$vote->org];?></td>
          <td><?php echo $vote->vote;$sum += $vote->vote;?></td>
        </tr>
	       <?php endforeach;?>
        <?php if(count($votes)):?>
        <tr>
          <td>合计</td>
          <td><?php echo $sum;?></td>
        </tr>
        <?php endif;?>
		</tbody>
	</table>
</div>
