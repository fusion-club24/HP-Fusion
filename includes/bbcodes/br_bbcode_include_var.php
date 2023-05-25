<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| BB-Code br
| Filename: br_bbcode_include_var.php
| Author: Harlekin
| Mail: harlekin67@gmx.de
| Web: https://harlekin-power.de
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

if (!preg_match("/\/forum\//i", FUSION_REQUEST)) {
$__BBCODE__[] = 
array(
"description"		=>	$locale['br_description'],
"value"			=>	"br",
"bbcode_start"	=>	"<br />",
"bbcode_end"		=>	"",
"usage"			=>	$locale['br_usage']
);
} else {
$__BBCODE__[] = 
array(
"description"		=>	$locale['br_description'],
"value"			=>	"",
"bbcode_start"	=>	"",
"bbcode_end"		=>	"",
"usage"			=>	$locale['br_usage']
);
}
?>