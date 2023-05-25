<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: block_function.php
| Author: firemike
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
error_reporting(E_ALL);
if (!defined("IN_FUSION")) { die("Access Denied"); }

if(iMEMBER){
	$result = dbquery("SELECT * FROM ".DB_USERS_BLOCKS." WHERE blocked_user_id='".$userdata['user_id']."' AND user_id='".$_GET['lookup']."'");

	if(dbrows($result) > 0) {
		redirect(MODULS."user_profil_block/blocked.php?id=".$_GET['lookup']."");
	}
}

?>