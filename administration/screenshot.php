<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: screenshot.php
| Author: Harlekin
+--------------------------------------------------------+
| Angepasst neue Google PageSpeed Insights API 
| Author: 21Matze
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../maincore.php";
require_once DESIGNS."templates/admin_header.php";
include LOCALE.LOCALESET."admin/screenshot.php";

if (!checkrights("W") || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

opentable($locale['400']);
echo "<div style='width:80%; text-align:left; margin:0 auto; padding:4px;' class='tbl'>";
	echo $locale['401']."<br />".$locale['402'];
echo "</div>";
echo "<br />";
echo "<div style='width:80%; text-align:center; margin:0 auto; padding:4px;' class='tbl'>";
	echo "<form name='screenshot' method='post' action='".FUSION_SELF.$aidlink."' >";
		echo $locale['410']." <input type='text' class='textbox' name='url' style='width:300px;' value='' /> ";
		echo "<input type='submit' name='submit' value='".$locale['411']."' class='button' />";
	echo "</form>";
echo "</div>";

if (!empty($_POST['url'])){
	 // Website url 
    $site_url = $_POST['url'];
	$img_name = str_replace(array('https:', 'http:', 'www', '/', '.'),array('', '', '', '', ''),$site_url);
	
	if (preg_match('/^(http|https):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $site_url)) {
    
// Call Google PageSpeed Insights API 
$googlePagespeedData = file_get_contents("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$site_url&screenshot=true"); 
 
// Decode json data 
$googlePagespeedData = json_decode($googlePagespeedData, true); 
 
// Retrieve screenshot image data 
$screenshot_data = $googlePagespeedData['lighthouseResult']['audits']['final-screenshot']['details']['data']; 
 
// Display screenshot image 
echo "<div style='width:80%; text-align:center; margin:0 auto; padding:4px;' class='tbl'>";
echo '<br /><img src="'.$screenshot_data.'" />';
 echo "</div>"; 
$create = "".$screenshot_data."";
file_put_contents(IMAGES.'weblink_sites/'.$img_name.'.jpg', base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $create)));    
    
	} else {
		echo "<div style='width:80%; text-align:center; margin:0 auto; padding:4px;' class='tbl'>";
			echo "<div class='admin-message'><strong>".$locale['420']."</strong></div>";
		echo "</div>";
	}
}
closetable();
require_once DESIGNS."templates/footer.php";
?>
