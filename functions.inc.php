<?php /* $Id: $ */
//
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.


if ( (isset($amp_conf['ASTVARLIBDIR'])?$amp_conf['ASTVARLIBDIR']:'') == '') {
	$astlib_path = "/var/lib/asterisk";
} else {
	$astlib_path = $amp_conf['ASTVARLIBDIR'];
}
$tts_astsnd_path = $astlib_path."/sounds/tts/";


function xtide_xtide($c) {
	global $ext;
	global $asterisk_conf;

	$date = xtideoptions_getconfig();
	$ttsengine = $date[0];
	

	$id = "app-xtide"; // The context to be included
	
	$ext->addInclude('from-internal-additional', $id); // Add the include from from-internal
	$ext->add($id, $c, '', new ext_goto('1', 's', $ttsengine));
}


function xtide_get_config($engine) {
	$modulename = 'xtide';
	
	// This generates the dialplan
	global $ext;  
	global $asterisk_conf;
	switch($engine) {
		case "asterisk":
			if (is_array($featurelist = featurecodes_getModuleFeatures($modulename))) {
				foreach($featurelist as $item) {
					$featurename = $item['featurename'];
					$fname = $modulename.'_'.$featurename;
					if (function_exists($fname)) {
						$fcc = new featurecode($modulename, $featurename);
						$fc = $fcc->getCodeActive();
						unset($fcc);
						
						if ($fc != '')
							$fname($fc);
					} else {
						$ext->add('from-internal-additional', 'debug', '', new ext_noop($modulename.": No func $fname"));
					}	
				}
			}
		break;
	}
}

function xtideoptions_getconfig() {
	#print_r($results);
	#die();
	require_once 'DB.php';
	$sql = "SELECT * FROM xtideoptions LIMIT 1";
	$results= sql($sql, "getAll");
	$tmp = $results[0][4];
	$tmp = eregi_replace('"', '', $tmp);
	$tmp = eregi_replace('>', '', $tmp);
	$res = explode('<', $tmp);
	$results[0][] = trim($res[1]);
	$results[0][] = trim($res[0]);
	return $results[0];
}

function xtideoptions_saveconfig() {

	require_once 'DB.php';

	# clean up
	$engine = mysql_escape_string($_POST['engine']);
	$Xtidesite = mysql_escape_string($_POST['Xtidesite']);
	$Xtidesitename = mysql_escape_string($_POST['Xtidesitename']);
	


	# Make SQL thing
	$sql = "UPDATE `xtideoptions` SET";
	$sql .= " `engine`='{$engine}',";
	$sql .= " `Xtidesite`='{$Xtidesite}'";
	$sql .= " LIMIT 1;";

	$sql = "UPDATE `xtideoptions` SET";
	$sql .= " `engine`='{$engine}',";
	$sql .= " `$Xtidesitename`='{$Xtidesitename}'";
	$sql .= " LIMIT 1;";
	
	
	sql($sql);
	needreload();
}

$tts_installed = array();
$tts_engines = array("text2wave", "flite", "swift");
$config = parse_amportal_conf( "/etc/amportal.conf" );


function xtide_vercheck() {
// compare version numbers of local module.xml and remote module.xml 
// returns true if a new version is available
	$newver = false;
	if ( function_exists(xml2array)){
		$module_local = xml2array("modules/xtide/module.xml");
		$module_remote = xml2array("https://raw.github.com/reconwireless/freepbx-xtide/master/module.xml");
		if ( $foo= empty($module_local) or $bar = empty($module_remote) )
			{
			//  if either array is empty skip version check
			}
		else if ( $module_remote[module][version] > $module_local[module][version])
			{
			$newver = true;
			}
		return ($newver);
		}
	}