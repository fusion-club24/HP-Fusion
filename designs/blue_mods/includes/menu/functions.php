<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2012 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Black Mods Theme
| Filename: functions.php
| Author: Johan Wilson (Barspin) & PHP-Fusion Mods UK
| Version: v1.00
| Developers: Johan Wilson (Barspin) & Craig
| Site: http://www.phpfusionmods.co.uk
+--------------------------------------------------------+
| Superfish v1.4.8 - jQuery menu widget -  Joel Birch
| Superfish v1.4.8 - jQuery menu widget Implemented for PHP-Fusion by 
| Fangree_Craig, Mangee & W3C_Valid
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

function navigation($main_menu=true){
	global $colour_switcher;
	if ($main_menu) {
		$result = dbquery("SELECT link_name, link_url, link_window, link_visibility FROM ".DB_SITE_LINKS." WHERE link_position='3' ORDER BY link_order");
		if (dbrows($result) > 0) {
			if(isset($colour_switcher)) {
				$menu_style = "colour/".$colour_switcher->selected.".css";
			} else {
				$menu_style = "menu.css";
			}
			add_to_head("<script type='text/javascript' src='".THEME."includes/menu/hoverIntent.js'></script>");
			add_to_head("<script type='text/javascript' src='".THEME."includes/menu/superfish.js'></script>");
			add_to_head("<script type='text/javascript' src='".THEME."includes/menu/menu.js'></script>");
			

			while ($data = dbarray($result)) {
				$link[] = $data;
			}
			
			echo "\n<ul class='sf-menu'>\n";
			$i = 0;
			$flysub_class = "";
			
			foreach($link as $data) {
				if (checkgroup($data['link_visibility'])) {
					$link_target = $data['link_window'] == "1" ? " target='_blank'" : "";
					$li_class = preg_match("/^".preg_quote(START_PAGE, '/')."/i", $data['link_url']) ? " class='current'" : "";
					
					if (strstr($data['link_name'], "%submenu% ")) {
						echo "<li$li_class><a href='/".$data['link_url']."'$link_target><span>".parseubb(str_replace("%submenu% ", "",$data['link_name']), "b|i|u|color")."</span></a>\n";
						echo "<ul>\n";
						$i++;
					} elseif (strstr($data['link_name'], "%endmenu% ")) {
						echo "<li$li_class><a href='/".$data['link_url']."'$link_target><span>".parseubb(str_replace("%endmenu% ", "",$data['link_name']), "b|i|u|color")."</span></a></li>\n";
						echo "</ul>\n";
						echo "</li>\n";
					} elseif (strstr($data['link_url'], "http://") || strstr($data['link_url'], "https://")) {
						echo "<li$li_class><a href='".$data['link_url']."'$link_target><span>".parseubb($data['link_name'], "b|i|u|color")."</span></a></li>\n";
					} else {
						echo "<li$li_class><a href='/".$data['link_url']."'$link_target><span>".parseubb($data['link_name'], "b|i|u|color")."</span></a></li>\n";
					}
				}
			}
			echo "</ul>\n";
		}
	} else {
		
	}
}
?>