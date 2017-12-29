<div class="pageHeader">
	<form onsubmit="return navTabSearch(this);" action="<?php echo helper::createLink('vote','statbyTeach'); ?>" method="post">
	<div class="searchBar">
		<table class="searchContent">
			<tr>
				
				<td>
  				<?php echo html::select('race', $raceMap,$app->post->race, 'class="combox"', '请选择活动'); ?>
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
          <th><?php echo $lang->vote->teacher;?></th>
          <th><?php echo $lang->vote->vote;?></th>
			</tr>
		</thead>
		<tbody>
        <?php 
	        	$sum = 0;
        	foreach($votes as $name=>$vote):?>
        <tr>                 
          <td><?php echo $name;?></td>
          <td><?php echo $vote;$sum += $vote;?></td>
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
