<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: flash_bbcode_include_var.php
| Author: Wooya
| Improoved by: jantom
| Modified for HP-Fusion by Harlekin
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

$__BBCODE__[] = 
array(
"description"		=>	$locale['bb_flash_description'],
"value"			=>	"flash",
"bbcode_start"	=>	"[flash width=200 height=50]",
"bbcode_end"		=>	"[/flash]",
"usage"			=>	"[flash width=".$locale['bb_flash_width']." height=".$locale['bb_flash_height']."]".$locale['bb_flash_usage']."[/flash]"
);
?>