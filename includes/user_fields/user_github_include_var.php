<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_github_include_var.php
| Original Author: Hans Kristian Flaatten {Starefossen} (github userfield)
| Edited by skpacman for GitHub userfield
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

// Version of the user fields api
$user_field_api_version = "1.01.00";

$user_field_name = $locale['uf_github'];
$user_field_desc = $locale['uf_github_desc'];
$user_field_dbname = "user_github";
$user_field_group = 1;
$user_field_dbinfo = "VARCHAR(100) NOT NULL DEFAULT ''";
?>
