<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2013 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: theme.php
| Author: Fangree Productions
| Version: v1.1
| Developers: Fangree_Craig + Dimi
| Site: http://www.fangree.com
| Site: http://www.hellasplus.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { header("Location: ../../index.php"); exit; }
require_once INCLUDES."theme_functions_include.php";
define("THEME_WIDTH", "985px");
define("THEME_BULLET", "<img src='".THEME."images/widget-list.png' alt='' class='theme-bullet'/>");
require_once THEME."includes/set_image.php";
function render_page($license=false) {
global $settings, $locale, $main_style, $mysql_queries_time, $aidlink, $userdata;
$locale['search'] = str_replace($locale['global_200'], "", $locale['global_202']);
echo "<div class='outer-container'>\n";
//echo"<div class='float-left sub-date'>".showsubdate()."</div>\n";
require_once THEME."includes/Social_Icons.php";
echo "<div class='header clearfix'>\n
<div class='banners'>".showbanners()." </div>\n</div>\n";
echo "<div class='sub-header clearfix'>\n
<div class='float-left'>"; 
/*require_once THEME."includes/menu/functions.php";
echo navigation();*/
include DESIGN."header_navigation/header_navi_respo.php";
include THEME."includes/search.php";
echo "<div class='container clearfix $main_style'>\n";
	
if (LEFT) { echo "<div class='side-border-left'>".LEFT."</div>\n"; }
	if (RIGHT) { echo "<div class='side-border-right'>".RIGHT."</div>\n"; }
	echo "<div class='main-content'><div class='main-container'>".U_CENTER.CONTENT.L_CENTER."</div></div>\n";
	echo "</div>\n";
	 
	echo "<div class='footer-main'>\n
    <div class=''>\n
<div class='cleaner'>\n</div>\n
\n <!-- end of content -->\n";

require_once THEME."includes/footer/footer.php";

echo "<div class='clear' ></div></div>\n";
        
	echo"</div></div>\n";
	}

function render_comments($c_data, $c_info){
	global $locale, $settings;
	opentable($locale['c100']);
	if (!empty($c_data)){
		echo "<div class='comments floatfix'>\n";
			$c_makepagenav = '';
			if ($c_info['c_makepagenav'] !== FALSE) { 
			echo $c_makepagenav = "<div style='text-align:center;margin-bottom:5px;'>".$c_info['c_makepagenav']."</div>\n"; 
		}
			foreach($c_data as $data) {
	        $comm_count = "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$data['i']."</a>";
			echo "<div class='tbl2 clearfix floatfix'>\n";
			if ($settings['comments_avatar'] == "1") { echo "<span class='comment-avatar'>".$data['user_avatar']."</span>\n"; }
	        echo "<span style='float:right' class='comment_actions'>".$comm_count."\n</span>\n";
			echo "<span class='comment-name'>".$data['comment_name']."</span>\n<br />\n";
			echo "<span class='small'>".$data['comment_datestamp']."</span>\n";
	if ($data['edit_dell'] !== false) { echo "<br />\n<span class='comment_actions'>".$data['edit_dell']."\n</span>\n"; }
			echo "</div>\n<div class='tbl1 comment_message'>".$data['comment_message']."</div>\n";
		}
		echo $c_makepagenav;
		if ($c_info['admin_link'] !== FALSE) {
			echo "<div style='float:right' class='comment_admin'>".$c_info['admin_link']."</div>\n";
		}
		echo "</div>\n";
	} else {
		echo $locale['c101']."\n";
	}
	closetable();   
}

require_once THEME."includes/news_articles.php";
function opentable($title) {  
echo "<div class='cap-main'>$title</div>\n
<div class='main-border'>\n";
}

function closetable() { echo "</div>\n"; }

function openside($title, $collapse = false, $state = "on") {
global $panel_collapse; $panel_collapse = $collapse;

echo "<div class='scap-main'>";
if ($collapse == true) {
$boxname = str_replace(" ", "", $title);
echo "<div class='panelbutton'></div>";
}
echo $title."</div>\n<div class='side-body floatfix'>\n";
if ($collapse == true) { echo panelstate($state, $boxname); }
}

function closeside($collapse = false) {
global $panel_collapse;
if ($panel_collapse == true) { echo "</div>\n"; }
echo "</div>\n\n";
}
?>