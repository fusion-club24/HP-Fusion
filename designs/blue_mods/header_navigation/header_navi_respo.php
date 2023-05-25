<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Name: header_navi_respo.php
| Author : Harlekin
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

add_to_head('<link rel="stylesheet" href="'.THEME.'header_navigation/hnstyles.css">');

add_to_footer("<script type='text/javascript' src='".THEME."header_navigation/hnscript.js'></script>");

echo "<div id='cssmenu'>";
$msql = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='0' AND link_position>='2' ORDER BY link_order");
if (dbrows($msql) != 0) {
	echo "<ul>";
	while ($mdata = dbarray($msql)) {
		if (checkgroup($mdata['link_visibility'])) {
			$link_target = ($mdata['link_window'] == "1" ? " target='_blank'" : "");
			if (strstr($mdata['link_url'], "http://") || strstr($mdata['link_url'], "https://")) {
				if ($mdata['link_name'] == "Community" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><img src='".THEME."images/community.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Downloads" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><img src='".THEME."images/dl.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Einsendungen" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><img src='".THEME."images/submit.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Testscripte" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><img src='".THEME."images/test.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Informationen" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><img src='".THEME."images/info.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else {
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				}
			} else {
				if ($mdata['link_name'] == "Community" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><img src='".THEME."images/community.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Downloads" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><img src='".THEME."images/dl.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Einsendungen" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><img src='".THEME."images/submit.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Testscripte" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><img src='".THEME."images/test.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else if ($mdata['link_name'] == "Informationen" && $mdata['link_url'] == "") {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><img src='".THEME."images/info.png' width='16' height='16' alt='' /> <span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				} else {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
				}
			}
      
			$msql2 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$mdata['link_id']."' ORDER BY link_order");
			if (dbrows($msql2) != 0) {
				echo "<ul>";
				while ($mdata2 = dbarray($msql2)) {
					if (checkgroup($mdata2['link_visibility'])) {
						$msql4 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$mdata2['link_id']."' ORDER BY link_order");
						if (dbrows($msql4) != 0) {
							$link_target = ($mdata2['link_window'] == "1" ? " target='_blank'" : "");
							if (strstr($mdata2['link_url'], "http://") || strstr($mdata2['link_url'], "https://")) {
								echo "<li class='submenu'><a href='".$mdata2['link_url']."'".$link_target."><span>".parseubb($mdata2['link_name'], "b|i|u|color|img")."</span> <img style='vertical-align:middle;' src='".THEME."images/sub_bg.gif' width='8' height='10' alt='' title='Unterkategorie' /></a>\n";
							} else {
								echo "<li class='submenu'><a href='".BASEDIR.$mdata2['link_url']."'".$link_target."><span>".parseubb($mdata2['link_name'], "b|i|u|color|img")."</span> <img style='vertical-align:middle;' src='".THEME."images/sub_bg.gif' width='8' height='10' alt='' title='Unterkategorie' /></a>\n";
							}
						} else {
							$link_target = ($mdata2['link_window'] == "1" ? " target='_blank'" : "");
							if (strstr($mdata2['link_url'], "http://") || strstr($mdata2['link_url'], "https://")) {
								echo "<li><a href='".$mdata2['link_url']."'".$link_target."><span>".parseubb($mdata2['link_name'], "b|i|u|color|img")."</span></a>\n";
							} else {
								echo "<li><a href='".BASEDIR.$mdata2['link_url']."'".$link_target."><span>".parseubb($mdata2['link_name'], "b|i|u|color|img")."</span></a>\n";
							}
						}
			
						$msql3 = dbquery("SELECT * FROM ".DB_SITE_LINKS." WHERE link_cat='".$mdata2['link_id']."' ORDER BY link_order");
						if (dbrows($msql3) != 0) {
							echo "<ul>";
							while ($mdata3 = dbarray($msql3)) {
								if (checkgroup($mdata3['link_visibility'])) {
									$link_target = ($mdata3['link_window'] == "1" ? " target='_blank'" : "");
									if (strstr($mdata3['link_url'], "http://") || strstr($mdata3['link_url'], "https://")) {
										echo "<li'><a href='".$mdata3['link_url']."'".$link_target."><span>".parseubb($mdata3['link_name'], "b|i|u|color|img")."</span></a></li>\n";
									} else {
										echo "<li><a href='".BASEDIR.$mdata3['link_url']."'".$link_target."><span>".parseubb($mdata3['link_name'], "b|i|u|color|img")."<span></a></li>\n";
									}
								}
							}
							echo "</ul>\n";
						}
						echo "</li>\n";
					}
				}
				echo "</ul>\n";
			}
			echo "</li>\n";
		}
	}
	echo "</ul>\n";
}
echo "</div>";
?>