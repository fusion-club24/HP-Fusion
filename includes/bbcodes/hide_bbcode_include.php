<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: hide_bbcode_include.php
| Author: Wooya
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

if (iADMIN) {
	$text = preg_replace('#\[hide\](.*?)\[/hide\]#si', '<div class=\'quote\'><strong>'.$locale['bb_hide'].'</strong><br /><span style=\'color:red;font-weight:bold\'>\1</span></div>', $text);
} else {
	$text = preg_replace('#\[hide\](.*?)\[/hide\]#si', '', $text);
}
?>