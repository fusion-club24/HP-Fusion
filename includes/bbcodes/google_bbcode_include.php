<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: google_bbcode_include.php
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

$text = preg_replace('#\[google\](.*?)\[/google\]#si', '<img src=\'https://www.google.com/logos/Logo_25wht.gif\' width=\'38\' height=\'18\' alt=\'Google Search\' border=\'0\' style=\'vertical-align:middle;\'> <a href=\'https://www.google.com/search?hl=&amp;lr=&amp;q=\1\' target=\'_blank\'>\1</a>', $text);
?>