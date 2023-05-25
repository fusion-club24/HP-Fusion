<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: infusion_db.php
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

if (!defined("DB_HP_QUASSELBOX")) {
	define("DB_HP_QUASSELBOX", DB_PREFIX."hp_quasselbox");
}

if (!defined("DB_HP_QUASSELBOX_LIKES")) {
	define("DB_HP_QUASSELBOX_LIKES", DB_PREFIX."hp_quasselbox_likes");
}

if (!defined("DB_HP_QUASSELBOX_SETTINGS")) {
	define("DB_HP_QUASSELBOX_SETTINGS", DB_PREFIX."hp_quasselbox_settings");
}
?>