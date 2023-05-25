<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: menu.php
| Author : Smokeman
| Email: smokeman@esenet.dk
| Web: http://www.phpfusion-tips.dk/
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
					echo "<li class='active'><a href='".$mdata['link_url']."'".$link_target."><span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
			} else {
					echo "<li class='active'><a href='".BASEDIR.$mdata['link_url']."'".$link_target."><span>".parseubb($mdata['link_name'], "b|i|u|color|img")."</span></a>\n";
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
								echo "<li class='submenu'><a href='".$mdata2['link_url']."'".$link_target."><span>".parseubb($mdata2['link_name'], "b|i|u|color|img")."</span></a>\n";
							} else {
								echo "<li class='submenu'><a href='".BASEDIR.$mdata2['link_url']."'".$link_target."><span>".parseubb($mdata2['link_name'], "b|i|u|color|img")."</span></a>\n";
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