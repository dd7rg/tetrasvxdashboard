<?php
date_default_timezone_set('Europe/Berlin');
define("CONTACTEMAIL", "dd7rg@murgs.org");
define("SVXLOGPATH", "/var/log");
define("SVXLOGPREFIX", "svxlink");
define("SVXCONFPATH", "/etc/svxlink/");
define("SVXCONFFILENAME", "svxlink.conf");
define("SVXLINKPATH", "/usr/bin/");
define("SVXLOGICSECTION", "TetraLogic");
define("SVXMODULES", array('Parrot', 'MetarInfo'));
define("SVXREFLECTORS", array("ReflectorLogicFLW", "ReflectorLogicOE9XFP", "ReflectorLogicBH", "ReflectorLogicBM262","ReflectorLogicTHU"));
define("TIMEZONE", "Europe/Berlin");
define("REFRESHAFTER", "60");
define("SHOWPROGRESSBARS", "on");
define("SHOWOLDMHEARD", "60");
define("TEMPERATUREALERT", "on");
define("TEMPERATUREHIGHLEVEL", "85");
define("SHOWQRZ", "on");
// Available colours for  keys background:  black, blue, red, magenta, green, cyan, yellow (Powered by ZX Spectrum palette)
define("KEY8", array('METAR','D61010#','green'));
define("KEY9", array('PARROT ON','D61004#','green'));
define("KEY10", array('PARROT OFF','D61005#','red'));
define("DASHCONFIG", "/var/www/html/config/config.php");

$reflector_config= array(
	"REF1" => array("config_name" => "ReflectorLogicFLW",
			"display_name" => "Tetra DL Reflector Leipzig (DL1FLW)",
			"link_name"    => "LinkToDL1FLW", 
			"link_is_active" => "0",
			"is_connected"   => "0",
			"activate_key"  => "9",
			"tg_choices"   => array(9,91,262,264,505,2329,3100)),

	"REF2" => array("config_name" => "ReflectorLogicOE9XFP",
			"display_name" => "Tetra OE9 Reflector OE9XFP",
			"link_name"   => "LinkToOE9XFP",
			"link_is_active" => "0",
			"is_connected"   => "0",
			"activate_key"  => "6",
			"tg_choices"   => array(2329, 23229)),


	"REF3" => array("config_name" => "ReflectorLogicBH",
			"display_name" => "Tetra Test Reflector DL1BH",
			"link_name"   => "LinkToDL1BH",
			"link_is_active" => "0",
			"is_connected"   => "0",
			"activate_key"  => "8",
			"tg_choices"   => array(8,9,264,4036)),

	"REF4" => array("config_name" => "ReflectorLogicBM262",
			"display_name" => "Tetra Brandmeister Reflector BM262",
			"link_name"   => "LinkToBM262",
			"link_is_active" => "0",
			"is_connected"   => "0",
			"activate_key"  => "99",
			"tg_choices"   => array(73,75,263)),
			
	"REF5" => array("config_name" => "ReflectorLogicTHU",
			"display_name" => "Analog Thüringen Link",
			"link_name"   => "LinkToTHU",
			"link_is_active" => "0",
			"is_connected"   => "0",
			"activate_key"  => "98",
			"tg_choices"   => array(8,20,66,89,91,262,264,777,9999))
		);


?>
