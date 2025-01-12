<?php

function getSvxConfig() {
        // loads svxlink.conf into array for further use
        $conf = array();
        if ($configs = fopen(SVXCONFPATH."/".SVXCONFFILENAME, 'r')) {
                while ($config = fgets($configs)) {
                        array_push($conf, trim ( $config, " \t\n\r\0\x0B"));
                }
                fclose($configs);
        }
        return $conf;
}

function getConfigItem($section, $key, $configs) {
        // retrieves the corresponding config stanza within a [section]
        $sectionpos = array_search("[" . $section . "]", $configs) + 1;
        $len = count($configs);
        while(startsWith($configs[$sectionpos],$key."=") === false && $sectionpos <= ($len) ) {
                if (startsWith($configs[$sectionpos],"[")) {
                        return null;
                }
                $sectionpos++;
        }

        return substr($configs[$sectionpos], strlen($key) + 1);
}

function getGitVersion(){
	// retrieves the current Git version of the dashboard, if available
	if (file_exists(".git")) {
		exec("git rev-parse --short HEAD", $output);
		return 'GitID #<a href="https://github.com/dd7rg/tetrasvxdashboard/commit/'.$output[0].'" target="_blank">'.$output[0].'</a>';
	} else {
		return 'GitID unknown';
	}
}

function getSvxLog() {
	// retrieves the current SvxLink log file
        $logLines = array();
        if ($log = fopen(SVXLOGPATH."/".SVXLOGPREFIX, 'r')) {
                while ($logLine = fgets($log)) {
                        array_push($logLines, $logLine);
                }
                fclose($log);
        }
        return $logLines;
}

function getSvxTXLines() {
	// returns the SvxLink transmitter log lines
	$logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	$logLines = `egrep -h "transmitter" $logPath | tail -1`;
	return $logLines;
}


function getLHLines() {
	$logLines = array();
        // returns the SvxLink Last Heard log lines
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        $logLines = `egrep -h "Talker stop" $logPath | tail -1`;
        return $logLines;
}

function getBootLines() {
        // returns the SvxLink Boot log line
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        $logLines = `egrep -h "Tobias" $logPath | tail -1`;
        return $logLines;
}

function getStopLines() {
        // returns the SvxLink stop log line
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        $logLines = `egrep -h "Shutting" $logPath | tail -1`;
        return $logLines;
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}

function makeButtons($reflector_config, $reflector_name) {

	// mit array walk oder so das sub array finden	
	//echo "<td><center>Hallo Welt</center></td>";
	foreach($reflector_config as $reflector) {
		if($reflector['config_name'] == $reflector_name) {	
			$tg_array=$reflector['tg_choices'];
			$activate_key = $reflector['activate_key'];
			$activate_link = $activate_key."1#";
			$deactivate_link = $activate_key."#";
			$status_link = $activate_key."*#";
			if(is_array($tg_array)) {
			$buttons="<td colspan=4><form method='post'>";
			$buttons.="<button name='action' class='green' value='".$activate_link."'>Activate</button>";
			$buttons.="<button name='action' class='red' value='".$deactivate_link."'>Deactivate</button>";
			$buttons.="<button name='action' class='green' value='".$status_link."'>Status</button>";
				foreach($tg_array as $tgs) {
					$buttons.="<button name='action' class='blue' value='".$activate_key."1".$tgs."#'>TG".$tgs."</button>";
				}

			$buttons.="</td></form>";
			}
		}
	}
	return $buttons;
}


function getSvxTGLines() {
        // returns the SvxLink TG log lines
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
        //$logLines = `egrep -h "Selecting" $logPath | tail -1`;
	$reflectors = SVXREFLECTORS;
	$selTGs = array();
	foreach ($reflectors as $reflectorname) {
		$regex="^.*".$reflectorname.": Selecting.*";
		$selLine = `egrep -h '$regex' $logPath | tail -1`;
		$tgParts = explode(" ", $selLine);
        	$tg = substr($tgParts[5],0);
		 $selTGs[$reflectorname]= $tg;
	}

        return $selTGs;
}

function setActiveLinks(&$reflector_config) {
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	foreach ($reflector_config as &$reflector) {
		$linkname = $reflector["link_name"];
		$regex="^.*: Activating.*".$linkname;
		$ActiveLine = `egrep -hn '$regex' $logPath | tail -1 | cut -f1 -d:`;
		$regex="^.*: Deactivating.*".$linkname;
		$DeactiveLine = `egrep -hn '$regex' $logPath | tail -1 | cut -f1 -d:`;
		if((int)$ActiveLine > (int)$DeactiveLine) {
			$is_active = 1;
		} else {
			$is_active = 0;
		}	
		
		$reflector["link_is_active"] = $is_active;
		unset($reflector);
	}

        return 1;
}

function setConnectedLinks(&$reflector_config) {
        $logPath = SVXLOGPATH."/".SVXLOGPREFIX;
	foreach ($reflector_config as &$reflector) {
		$regex="^.*".$reflector['config_name'].": Connection established to.*";
		$ActiveLine = `egrep -hn '$regex' $logPath | tail -1 | cut -f1 -d:`;
		$regex="^.*".$reflector['config_name'].": Disconnected from.*";
		$DeactiveLine = `egrep -hn '$regex' $logPath | tail -1 | cut -f1 -d:`;
		if((int)$ActiveLine > (int)$DeactiveLine) {
			$is_connected = 1;
		} else {
			$is_connected = 0;
		}	
		


		$reflector['is_connected'] = $is_connected;
		unset($reflector);
	}

        return 1;
}



function getDefaultTG($config) {
        // returns the default TG  at svxlink.conf
        $confPath = SVXCONFPATH."/".SVXCONFFILENAME;
        $configLine = `egrep -h "DEFAULT_TG" $confPath | tail -1`;
        return $configLine;
}

function getMonitorTGs($config) {
        // returns Monitor TGs  at svxlink.conf
        $confPath = SVXCONFPATH."/".SVXCONFFILENAME;
        $configLine = `egrep -h "MONITOR_TGS" $confPath | tail -1`;
        return $configLine;
}


function getConnectedEcholink($logLines) {
	// retrieves the current EchoLink users connected to the SvxLink
        $users = Array();
        foreach ($logLines as $logLine) {
                if(strpos($logLine,"Echolink QSO")){
                        $users = Array();
                }
                if(strpos($logLine,"state changed to CONNECTED")) {
                        $lineParts = explode(" ", $logLine);
			if (!array_search($lineParts[5], $users)) {
                                array_push($users, Array('callsign'=>substr($lineParts[5],0,-1),'timestamp'=>substr($logLine,0,24)));
                        }
                }
                if(strpos($logLine,"state changed to DISCONNECTED")) {
                        $lineParts = explode(" ", $logLine);
			$pos = array_search(substr($lineParts[5],0,-1), $users);
			array_splice($users, $pos, 1);
                }
        }
        return $users;
}

function getEcholinkCount($logLines) {
	$getCount = getConnectedEcholink($logLines);
	$count = count($getCount);
	return $count;
}

function initModuleArray() {
	// this initializes the active SvxLink module array for further use - move to tools.php?
	$modules = Array();
	foreach (SVXMODULES as $enabled) {
                $modules[$enabled] = 'Off';
        }
	return $modules;
}

function getActiveModules($logLines) {
	// this updates the module array with the status of the modules - could use cleanup
	$modules = initModuleArray();
        foreach ($logLines as $logLine) {
                if(strpos($logLine,"Activating module")) {
                        $lineParts = explode(" ", $logLine);
			$modul = substr($lineParts[5],0,-4);
                        if (!array_search($modul, $modules)) {
                                $modules[$modul] = 'On';
                        }
			if (array_search($modul, $modules)) {
				$modules[$modul] = 'On';
			}
                }
                if(strpos($logLine,"Deactivating module")) {
                        $lineParts = explode(" ", $logLine);
			$modul = substr($lineParts[5],0,-4);
			$modules[$modul] = 'Off';
                }

        }
        return $modules;
}


function getBoot($logLines) {
        $lineaboot = getBootLines($logLines);
        return $lineaboot;
}

function getStop($logLines) {
        $lineastop = getStopLines($logLines);
        return $lineastop;
}


function getSize($filesize, $precision = 2) {
	// this is for the system info card
	$units = array('', 'K', 'M', 'G', 'T', 'P', 'E', 'Z', 'Y');
	foreach ($units as $idUnit => $unit) {
		if ($filesize > 1024)
			$filesize /= 1024;
		else
			break;
	}
	return round($filesize, $precision).' '.$units[$idUnit].'B';
}

?>
