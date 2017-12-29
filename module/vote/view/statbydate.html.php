<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('vote','statbydate'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				
				<td>
  				<?php echo html::select('race', $raceMap, $app->post->race, 'class="combox"', '请选择活动'); ?>
				</td>
				<td>
					开始时间:<input class='date' type="text" name="start" value="<?php echo $app->post->start;?>"/>
				</td>
				<td>
					结束时间:<input class='date' type="text" name="end" value="<?php echo $app->post->end;?>"/>
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
          <th><?php echo $lang->vote->date;?></th>
          <th><?php echo $lang->vote->vote;?></th>
			</tr>
		</thead>
		<tbody>
	        <?php
	        	$sum = 0;
         		foreach($votes as $vote):
         ?>
         	
        <tr>                 
          <td><?php echo $vote->date;?></td>
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
