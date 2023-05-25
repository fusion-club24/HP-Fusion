<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: youtube_bbcode_include.php
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

$text = preg_replace('#\[youtube\](http:|https:)?(\/\/www.youtube-nocookie\.com\/watch\?v=|\/\/youtube\.com\/)?(.*?)\[/youtube\]#si', '<strong>'.$locale['bb_youtube'].'</strong><br /><iframe width="425" height="350" src="https://www.youtube-nocookie.com/embed/\3" frameborder="0"></iframe>', $text);
?>