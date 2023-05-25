<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: infusion.php
| Author: Harlekin
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

// Infusion general information
$inf_title = "Fonts Icon Viewer";
$inf_description = "Fonts Icon Viewer";
$inf_version = "1.0";
$inf_developer = "Rolly8-HL";
$inf_email = "";
$inf_weburl = "https://rolly8-hl.de";

$inf_folder = "fonts_icon_viewer"; // The folder in which the infusion resides.

$inf_sitelink[1] = array(
	"title" => "Fonts Icon Viewer",
	"url" => "fonts_icon_viewer.php",
	"visibility" => "0"
);

$inf_adminpanel[1] = array(
	"title" => "Fonts Icon Viewer",
	"image" => "fonts-icon.png",
	"panel" => "admin.php",
	"rights" => "FIVW"
);
?>