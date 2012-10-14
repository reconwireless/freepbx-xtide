<?php
//
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

// check to see if user has automatic updates enabled
$cm =& cronmanager::create($db);
$online_updates = $cm->updates_enabled() ? true : false;

// check if new version of module is available
if ($online_updates && $foo = xtide_vercheck()) {
	print "<br>A <b>new version</b> of the Xtide module is available from the <a target='_blank' href='http://github.com/reconwireless/freepbx-xtide/downloads'>Reconwireless Repository on github</a><br>";
}

//tts_findengines()
if(count($_POST)){
	xtideoptions_saveconfig();
}
	$Xtidesite = xtideoptions_getconfig();
	$selected = ($Xtidesite[0]);
	$Xtidesitename = xtideoptions_getconfig();
	$selected = ($Xtidesitename[0]);

//  Get current featurecode from FreePBX registry
$fcc = new featurecode('xtide', 'xtide');
$featurecode = $fcc->getCodeActive(); 

?>
<form method="POST" action="">
	<br><h2><?php echo _("Xtide Reports")?><hr></h5></td></tr>
Xtide allows you to retrieve current tide information from any touchtone phone using nothing more than your PBX connected to the Internet. <br><br>
Current tide conditions and provides today's and tomorrow's tides, solar, and lunar info for almost any port city.. <br><br>
The feature code to access this service is currently set to <b><?PHP print $featurecode; ?></b>.  This value can be changed in Feature Codes. <br>

<br><h5>User Data:<hr></h5>
Select the Text To Speech engine and you wish the Xtide program to use.<br>The module does not check to see if the selected TTS engine is present, ensure to choose an engine that is installed on the system.<br><br>
<a href="#" class="info">Choose a service and engine:<span>Choose from the list of supported TTS engines and Tide services</span></a>

<select size="1" name="engine">
<?php
echo "<option".(($date[0]=='xtide-flite')?' selected':'').">xtide-flite</option>\n";
echo "<option".(($date[0]=='xtide-cepstral')?' selected':'').">xtide-cepstral</option>\n";
?>
</select>
<br><a href="#" class="info">Xtide Site:<span>Input xtide site example: "bluffton"</span></a>
<input type="text" name="Xtidesite" size="27" value="<?php echo $wcity[1]; ?>">  <a href="javascript: return false;" class="info"> 
<br><a href="#" class="info">Xtide Site Name:<span>Input site name: "Bluffton, May River, South Carolina"</span></a>
<input type="text" name="Xtidesitename" size="27" value="<?php echo $wstate[1]; ?>">  <a href="javascript: return false;" class="info"> 
<br><br>key:<br>
<b>Xtide</b> - provides today's and tomorrow's tides, solar, and lunar info for almost any port city.<br>
<b>Xtide Site Search</b> - Look up your Xtide Site by visiting <a target="_blank" href="http://www.flaterco.com/xtide/locations.html">Xtide Locations</a><br>
<b>flite</b> - Asterisk Flite Text to Speech Engine<br>
<b>swift</b> - Cepstral Swift Text to Speech Engine<br>

		
<br><br><input type="submit" value="Submit" name="B1"><br>

<center><br>
The module is based on the xtide scripts from Nerd Vittles and the weather-by-zip module from the developer community at <a target="_blank" href="http://pbxossa.org"> PBX Open Source Software Alliance</a>.  Support, documentation and current versions are available at the tide module <a target="_blank" href="https://github.com/reconwireless/freepbx-xtide">reconwireless dev site</a></center>
<?php
print '<p align="center" style="font-size:11px;">The xtide scripts were created and are currently maintaned by <a target="_blank" href="http://www.nerdvittles.com">Nerd Vittles</a>.';
print '<p align="center" style="font-size:11px;">The xtide harmonics and original files created and are currently maintaned by <a target="_blank" href="http://www.flaterco.com/xtide/files.html">xtide</a>.';
?>