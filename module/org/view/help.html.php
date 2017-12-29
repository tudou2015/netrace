<style type="text/css">
table.gridtable {
	font-size:12px;
	color:#333333;
	border-width: 1px;
	border-color: #666666;
	border-collapse: collapse;
}
table.gridtable th {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
}
table.gridtable td {
	border-width: 1px;
	padding: 8px;
	border-style: solid;
	border-color: #666666;
	background-color: #ffffff;
}
</style>

在同一个城市请选择距离较近的单位。如果没有您所在的城市，请选择开放教育学院。
	<table class="gridtable" width="100%">
		<thead>
			<tr>
          <th><?php echo $lang->org->name;?></th>
          <th><?php echo $lang->org->address;?></th>
          <th><?php echo $lang->org->phone;?></th>
          <th>周边地图</th>
			</tr>
		</thead>
		<tbody>
        <?php foreach($orgs as $org):
        	if(strlen($org->code) <> 7) continue;
        	if(!$org->enable) continue;
        ?>
        <tr>
          <td><?php echo $org->name;?></td>
          <td><?php echo $org->address;?></td>
          <td><?php echo $org->phone;?></td>
          <td><?php if($org->map):?><a href='<?php echo helper::createLink('org','map',array('orgID'=>$org->id));?>'>查看</a><?php endif;?>&nbsp;</td>
        </tr>
        <?php endforeach;?>
		</tbody>
	</table>