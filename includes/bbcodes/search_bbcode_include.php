<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: search_bbcode_include.php
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

//include LOCALE.LOCALESET."bbcodes/search.php";

	if (!function_exists('replace_searchparams')) {	
		function replace_searchparams($m) {
			global $settings;
			// first convert searchstring to eliminate all unwanted chars
			$search_string = htmlspecialchars_decode($m['content'],ENT_QUOTES);
			$search_string = preg_replace('#\s+#' , ' ' , preg_replace("/[^.:a-zA-Z�������0-9-�]/"," ",$search_string));
		
			if(strlen(trim($search_string))!=0) {
				include LOCALE.LOCALESET."bbcodes/search.php";
				$search_type = (!empty($m['search']) ? $m['search'] : "all");
				if(IsSet($m['search']) && $m['search']!="members" && $m['search']!="downloads" && $m['search']!="weblinks" && $m['search']!="photos" && $m['search']!="forums"  && $m['search']!="custompages" && $m['search']!="faqs" && $m['search']!="articles" && $m['search']!="news") {
					$search_type = "all";
				} else {
					$search_type = $m['search'];
				}
				$searcharea_locale = "bb_search_".$search_type;
		
				$content = "<strong>".$locale['bb_search_prefix']." <a href='".BASEDIR."search.php?stype=".$m['search']."&amp;method=AND&amp;stext=".urlencode($search_string)."' target='_blank'>".$m['content']."</a></strong> ".$locale['bb_search_suffix']." ".$settings['sitename']." (".$locale[$searcharea_locale].")\n";
				return $content;
			} else { return NULL; }
		}
	}
	$text = preg_replace_callback('#\[search(=(?P<search>(.*?)))?\](?P<content>.*?)\[/search\]#i', 'replace_searchparams', $text);

?>