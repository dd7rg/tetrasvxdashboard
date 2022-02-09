 <div class="card">
    <div class="card-header">
      Reflector connection Status
    </div>
    <div class="table-responsive">
      <table id="currtx" class="table table-condensed table-striped table-hover">
	<thead>
	  <tr>
<th><center>Reflector</center></th>
<th><center>Connected</center></th>
<th><center>Link Active</center></th>
<th><center>Current TG</center></th>
<th><center>Monitor TGs</center></th>
	  </tr>
	</thead>
	<tbody id="tgline">
<?php
$tgs = getSvxTGLines($logLines);
$r=setActiveLinks($reflector_config);
$r=setConnectedLinks($reflector_config);

foreach ($reflector_config as $value) {
	$mon=getConfigItem($value['config_name'], "MONITOR_TGS", $configs);
	$buttons =makeButtons($reflector_config, $value['config_name']);
	echo "<tr>";
	echo "<td><center>".$value['display_name']."</center></td>";
        echo "<td><center>".$value['is_connected']."</center></td>";
        echo "<td><center>".$value['link_is_active']."</center></td>";
	echo  "<td><center>".$tgs[$value['config_name']]."</center></td>";
	echo "<td><center>".$mon."</center></td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td><center></center></td>";
	echo $buttons;
	echo "</tr>";
}
?>
	</tbody>
      </table>
    </div>
  </div>
