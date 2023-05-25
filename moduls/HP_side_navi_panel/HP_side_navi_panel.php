<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: HP_side_navi_panel.php
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

add_to_head("<style type='text/css'>
.fusion_css_navigation_panel {margin: 0px;padding-top: 0px;padding-bottom: 0px;//font-size: 12px !important;}
.fusion_css_navigation_panel ul li a {display: block;padding: 3px !important;text-decoration: none;transition: all 0.1s ease-in-out 0s;}
.fusion_css_navigation_panel ul li a:hover, .fusion_css_navigation_panel ul li a:focus {font-weight: bold;text-decoration: underline;}
#css_p_navigation ul {list-style-type: none;}
.sub-nav-nb {display: none;border-left-width: 2px;border-left-style: solid;border-left-color: #F8A500;margin-left: 5px !important;padding-left: 2px !important;}
.main-nav-nb {margin: 0 !important;padding-left: 2px !important;}
.up_icon {background: url(".MODULS."HP_side_navi_panel/images/sub_dw.gif) no-repeat right;}
.pull-right {float: right;}
</style>");

global $userdata,$locale, $settings ;

openside($locale['global_001'],true, "on");

function tree_links_full($db, $id_col, $cat_col, $filter = FALSE, $query_replace = "") {
	$data = array();
	$index = array();
	$query = "SELECT * FROM ".$db." ".$filter;
	if (!empty($query_replace)) {
		$query = $query_replace;
    }
    $query = dbquery($query);
	while ($row = dbarray($query)) {
		$id = $row[$id_col];
		$parent_id = $row[$cat_col] === NULL ? "0" : $row[$cat_col];
		$data[$id] = $row;
		$index[$parent_id][$id] = $row;
	}
	return (array) $index;
}

function showsidelinks(array $options = array(), $id = 0) {
	global $userdata;
	static $data = array();
	$acclevel = isset($userdata['user_level']) ? $userdata['user_level'] : 0;
	$res = &$res;
	if (empty($data)) {
		$data = tree_links_full(DB_SITE_LINKS, "link_id", "link_cat", "WHERE ".groupaccess('link_visibility')."  
		AND link_position='1' OR link_position='2'  ORDER BY link_cat, link_order");
	}
	if (!$id) {
		$res .= "<ul class='main-nav-nb' >\n";
	} else {
		$res .= "<ul class='sub-nav-nb'>\n";
	}

	foreach($data[$id] as $link_id => $link_data) {
		$li_class = "";
		if (checkgroup($link_data['link_visibility'])) {
            $link_target = ($link_data['link_window'] == "1" ? " target='_blank'" : "");
			if (preg_match("!^(ht|f)tp(s)?://!i", $link_data['link_url'])) {
				$item_link = $link_data['link_url'];
			} else {
				$item_link = BASEDIR.$link_data['link_url'];
			}
            $res .= "<li style='margin-left: 0px;'>\n";

            $res .= "<a class='display-block ' style='cursor:pointer' href='".$item_link."' ".$link_target." >\n";
            $res .=  "<img src='".MODULS."HP_side_navi_panel/images/sub_mp.gif' alt=''> ".parseubb($link_data['link_name'], "b|i|u|color|img")."";
            $res .= "</a>\n";
	//		}
			if (isset($data[$link_id])) {
				$res .= showsidelinks($options, $link_data['link_id']);
			}
			$res .= "</li>\n";
		}
	}
	$res .= "</ul>\n";
	return $res;
}


echo "<div class='fusion_css_navigation_panel' id='css_p_navigation'>\n";
	echo showsidelinks();
echo "</div>\n";
closeside();


echo "<script type='text/javascript'>
    $('.fusion_css_navigation_panel ul li').click(
		function(event) {
			event.stopPropagation();
			$(this).find('ul:first').toggle(600);
		}
    );
	$('.fusion_css_navigation_panel li:has(ul)').find('a:first').addClass('up_icon');
	$('.fusion_css_navigation_panel li:has(ul)').find('a:first').removeAttr('href');
</script>\n";

?>