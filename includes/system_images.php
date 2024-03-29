<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: system_images.php
| Author: Max "Matonor" Toball
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

cache_smileys();
$smiley_images = array();
if (is_array($smiley_cache) && count($smiley_cache)) {
	foreach ($smiley_cache as $smiley) {
		$smiley_images["smiley_".$smiley['smiley_text']] = IMAGES."smiley/".$smiley['smiley_image'];
	}
}

$result = dbquery("SELECT news_cat_image, news_cat_name FROM ".DB_NEWS_CATS);
$nc_images = array();
while ($data = dbarray($result)) {
	$nc_images["nc_".$data['news_cat_name']] = file_exists(IMAGES_NC.$data['news_cat_image']) ? IMAGES_NC.$data['news_cat_image'] : IMAGES."imagenotfound.jpg";
}

$result = dbquery("SELECT admin_title, admin_image FROM ".DB_ADMIN);
$ac_images = array();
while ($data = dbarray($result)) {
	$ac_images["ac_".$data['admin_title']] = file_exists(ADMIN."images/".$data['admin_image']) ? ADMIN."images/".$data['admin_image'] : (file_exists($data['admin_image']) ? $data['admin_image'] : ADMIN."images/infusion_panel.gif");
}

$fusion_images = array(
	//A
	"arrow" 		=> IMAGES."arrow.png",
	//B
	"blank" 		=> DESIGN."images/blank.gif",
	//C
	"calendar" 		=> IMAGES."dl_calendar.png",
	//D
	"down" 			=> DESIGN."images/down.gif",
	"download"		=> IMAGES."dl_download.png",
	"downloads"		=> IMAGES."dl_downloads1.png",
	//E
	"edit" 			=> IMAGES."edit.png",
	//F
	"folder" 		=> DESIGN."forum/folder.gif",
	"folderlock" 	=> DESIGN."forum/folderlock.gif",
	"foldernew" 	=> DESIGN."forum/foldernew.gif",
	"forum_edit" 	=> DESIGN."forum/edit.gif",
	//G
	"go_first" 		=> IMAGES."go_first.png",
	"go_last" 		=> IMAGES."go_last.png",
	"go_next" 		=> IMAGES."go_next.png",
	"go_previous" 	=> IMAGES."go_previous.png",
	//H
	"homepage" 		=> IMAGES."dl_homepage.png",
	//I
	"info" 			=> IMAGES."dl_info.png",
	"imagenotfound" => IMAGES."imagenotfound.jpg",
	//J
	//K
	//L
	"left" 			=> DESIGN."images/left.gif",
	//M
	//N
	"newthread" 	=> DESIGN."forum/newthread.gif",
	"no" 			=> IMAGES."no.png",
	"noavatar50" 	=> "noavatar50.png",
	"noavatar100" 	=> "noavatar100.png",
	"noavatar150" 	=> "noavatar150.png",
	//O
	//P
	"panel_on" 		=> DESIGN."images/panel_on.gif",
	"panel_off" 	=> DESIGN."images/panel_off.gif",
	"pm" 			=> DESIGN."forum/pm.gif",
	"pollbar" 		=> DESIGN."images/pollbar.gif",
	"printer" 		=> IMAGES."printer.png",
	//Q
	"quote" 		=> DESIGN."forum/quote.gif",
	//R
	"reply" 		=> DESIGN."forum/reply.gif",
	"right" 		=> DESIGN."images/right.gif",
	//S
	"save"			=> IMAGES."php-save.png",
	"screenshot"	=> IMAGES."dl_screenshot.png",
	"star" 			=> IMAGES."star.png",
	"statistics"	=> IMAGES."dl_stats.png",
	"stickythread" 	=> DESIGN."forum/stickythread.gif",
	//T
	"tick" 			=> IMAGES."tick.png",
	//U
	"up" 			=> DESIGN."images/up.gif",
	//V
	//W
	"web" 			=> DESIGN."forum/web.gif",
	//X
	//Y
	"yes" 			=> IMAGES."yes.png"
	//Z
);

$fusion_images = array_merge($ac_images, $fusion_images, $nc_images, $smiley_images);

function get_image($image, $alt = "", $style = "", $title = "", $atts = "") {
	global $fusion_images;
	if (isset($fusion_images[$image])) {
		$url = $fusion_images[$image];
	} else {
		$url = IMAGES."not_found.gif";
	}
	if (!$alt && !$style && !$title) {
		return $url;
	} else {
		return "<img src='".$url."' alt='".$alt."'".($style ? " style='$style'" : "").($title ? " title='".$title."'" : "")." ".$atts." />";
	}
}

function set_image($name, $new_dir) {
	global $fusion_images;
	$fusion_images[$name] = $new_dir;
}

function redirect_img_dir($source, $target) {
	global $fusion_images;
	$new_images = array();
	foreach ($fusion_images as $name => $url) {
		$new_images[$name] = str_replace($source, $target, $url);
	}
	$fusion_images = $new_images;
}
?>