Installing xtide reports Module<br>
<h5>Zip Code Database (v1.0)</h5>
<?php
if ( (isset($amp_conf['ASTVARLIBDIR'])?$amp_conf['ASTVARLIBDIR']:'') == '') {
	$astlib_path = "/var/lib/asterisk";
} else {
	$astlib_path = $amp_conf['ASTVARLIBDIR'];
}
// Need to add check here to check existing mysql table, get rid of zipcode and add wgroundkey
// add primary key index 


?><br>Installing Default Configuration values.<br>
<?php

$sql ="INSERT INTO xtideoptions (engine, Xtidesite) ";
$sql ="INSERT INTO xtideoptions (engine, Xtidesitename) ";
$sql .= "               VALUES ('xtide-flite',        '')";
$check = $db->query($sql);
if (DB::IsError($check)) {
        die_freepbx( "Can not create default values in `xtideoptions` table: " . $check->getMessage() .  "\n");
}

// Add dialplan include to asterisk conf file
$filename = '/etc/asterisk/extensions_custom.conf';
$includecontent = "#include custom_xtide.conf\n";

// First we need to look for existing occurances of the include line from past sloppy uninstall/upgrade and remove all of them
function replace_file($path, $string, $replace)
{
    set_time_limit(0);
    if (is_file($path) === true)
    {
        $file = fopen($path, 'r');
        $temp = tempnam('./', 'tmp');
        if (is_resource($file) === true)
        {
            while (feof($file) === false)
            {
                file_put_contents($temp, str_replace($string, $replace, fgets($file)), FILE_APPEND);
            }
            fclose($file);
        }
        unlink($path);
    }
    return rename($temp, $path);
}

replace_file($filename, $includecontent, '');

// Now add back include line
if (is_writable($filename)) {
 
    if (!$handle = fopen($filename, 'a')) {
         echo "Cannot open file ($filename)";
         exit;
    }
    // Write $somecontent to our opened file.
    if (fwrite($handle, $includecontent) === FALSE) {
        echo "Cannot write to file ($filename)";
        exit;
    }
    echo "<br>Success, wrote ($includecontent)<br> to file ($filename)<br><br>";

    fclose($handle);

} else {
    echo "The file $filename is not writable";
}
?>Verifying / Installing cronjob into the FreePBX cron manager.<br>
<?php
$sql = "SELECT * FROM `cronmanager` WHERE `module` = 't' LIMIT 1;";

$res = $db->query($sql);

if($res->numRows() != 1)
{
$sql = "INSERT INTO	cronmanager (module,id,time,freq,command) VALUES ('xtide','every_day',23,24,'/usr/bin/find /var/lib/asterisk/sounds/tts -name \"*.wav\" -mtime +1 -exec rm {} \\\;')";

$check = $db->query($sql);
if (DB::IsError($check))
	{
	die_freepbx( "Can not create values in cronmanager table: " . $check->getMessage() .  "\n");
	}
}
?>Verifying / Creating TTS Folder.<br>
<?php
$parm_tts_dir = '/var/lib/asterisk/sounds/tts';
if (!is_dir ($parm_tts_dir)) mkdir ($parm_tts_dir, 0775);
?>Creating Feature Code.<br>
<?php
// Register FeatureCode - Xtide Reports;
$fcc = new featurecode('xtide', 'xtide');
$fcc->setDescription('Xtide Reports');
$fcc->setDefault('8433');
$fcc->update();
unset($fcc);
?>
