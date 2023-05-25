<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Design: HP Fusion Blue
| Filename: theme.php
| Author: Harlekin
| Co-Author: Rolly8-HL
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

require_once INCLUDES."theme_functions_include.php";
require_once DESIGN."navigation/nav_func.php";

define("THEME_BULLET", "");
set_image("folder", DESIGN."forum/folder.png");
set_image("folderhot", DESIGN."forum/folderhot.png");
set_image("folderlock", DESIGN."forum/folderlock.png");
set_image("foldernew", DESIGN."forum/foldernew.png");
set_image("stickythread", DESIGN."forum/stickythread.png");
set_image("printer", DESIGN."images/printer.png");
	
function render_page($license = false)
{
	add_to_head("<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans' type='text/css'>");
	add_to_head("<link rel='stylesheet' href='".DESIGN."navigation/hp_top_menu.css' type='text/css' media='screen' />");
	add_to_head('<script src="'.DESIGN.'HP_membercard/inc/HP_mc.js"></script><script type="text/javascript">//<![CDATA[
		DESIGN = "'.DESIGN.'";
		//]]></script>
		<link rel="stylesheet" type="text/css" href="'.DESIGN.'HP_membercard/inc/HP_mc.css" />');

	global $settings, $main_style, $locale, $mysql_queries_time, $userdata, $aidlink;
	
	//Check for locale
	if (file_exists(DESIGN."locale/".$settings['locale'].".php")) {
		include DESIGN."locale/".$settings['locale'].".php";
	} else {
		include DESIGN."locale/English.php";
	}
    
	echo "<div id='toppage' class='clearfix'>";
		echo "<div id='header' class='clearfix'>".render_menu();
			echo "<div id='mainheader' class='theme-width center clearfix'>";
			echo "<!--HP-F_blue-->";
			echo "</div>";
		echo "</div>";
	echo "</div>";
	echo '<div id="top2" class="clearfix"><div class="member_area clearfix"><div class="member_area-text flright">';
		if (iMEMBER) {
			$msg_count = dbcount("(message_id)", DB_MESSAGES, "message_to='".$userdata['user_id']."' AND message_read='0' AND message_folder='0'");
			if ($userdata['user_avatar'] && file_exists(IMAGES."avatars/{$userdata['user_avatar']}")) {
				$avatar = $userdata['user_avatar'];
			} else {
				$avatar = "noavatar50.png";
			}

			echo "<a href='".BASEDIR."profile.php?lookup=".$userdata['user_id']."'><img src='".IMAGES."avatars/{$avatar}' class='avatar-frame' style=' box-shadow: 3px 3px 8px #000000; border-radius: 0 !important; height: 40px; width: 40px; ' alt='".$userdata['user_name']."' title='".$locale['hpfr_001']." ".$userdata['user_name']."' /></a>&nbsp;&nbsp;";

			echo "<a href='".BASEDIR."edit_profile.php'><img src='".DESIGN."images/hp_user-blue-set.gif' alt='".$locale['hpfr_002']."' title='".$locale['hpfr_002']."' class='avatar-frame'   style='height: 35px; width: 35px;' /></a>&nbsp;&nbsp;";

			if ($msg_count != 0) {
				$msg_count_check = "<span style='margin-left: -25px; box-shadow: 2px 2px 4px #000000; background-color: #F00; color: #FFF; border-radius: 18px; padding-right: 8px; padding-left: 8px; padding-bottom: 2px; font-size: 18px; vertical-align: bottom; '>".$msg_count."</span>";
				$icon_check = "hp_envelope-open-blue.gif";
			} else {
				$msg_count_check = "";
				$icon_check = "hp_envelope-closed-blue.gif";
			}
			echo "<a href='".BASEDIR."messages.php'><img src='".DESIGN."images/".$icon_check."' alt='".$locale['hpfr_003']."' title='".$locale['hpfr_003']."' class='avatar-frame'   style='height: 35px; width: 35px;' />".$msg_count_check."</a>";
			echo "<a class='navigation'  href='".BASEDIR."members.php'><img src='".DESIGN."images/hp_users_list-blue.gif' alt='".$locale['global_122']."' title='".$locale['global_122']."' class='avatar-frame'   style='height: 35px; width: 35px;' /></a>&nbsp;";

			if (iADMIN && (iUSER_RIGHTS != "" || iUSER_RIGHTS != "C")) {
				echo "<a href='".ADMIN."index.php".$aidlink."'><img src='".DESIGN."images/hp_admin-blue-2.gif' alt='".$locale['hpfr_004']."' title='".$locale['hpfr_004']."' class='avatar-frame'   style='height: 35px; width: 35px;' /></a>&nbsp;&nbsp;";
			}
			echo "<a class='navigation'  href='".BASEDIR."index.php?logout=yes'><img src='".DESIGN."images/hp_sign-out-blue.gif' alt='".$locale['hpfr_005']."' title='".$locale['hpfr_005']."' class='avatar-frame'   style='height: 35px; width: 35px;' /></a>";
		} else {
			echo "<img src='".IMAGES."avatars/noavatar50.png' class='avatar-frame'  alt='' />";
			echo "<a href='".BASEDIR."login.php'><img src='".DESIGN."images/hp_sign-in-blue.gif' alt='".$locale['hpfr_006']."' title='".$locale['hpfr_006']."' class='avatar-frame'   style='height: 35px; width: 35px;' /></a>";
					if ($settings['enable_registration']) {
			echo "<a href='".BASEDIR."register.php'><img src='".DESIGN."images/hp_address-card-o-blue.gif' alt='".$locale['hpfr_007']."' title='".$locale['hpfr_007']."' class='avatar-frame'   style='height: 35px; width: 35px;' /></a>";
		}
        
		}
	echo '</div></div></div>';
	echo "<div id='main' class='$main_style center clearfix'>";
		echo (LEFT ? "<div id='side-border-left' class='sides flleft'>\n".LEFT."</div>\n" : "");
		echo (RIGHT ? "<div id='side-border-right' class='sides flright'>\n".RIGHT."</div>\n" : "");
		echo "<div id='main-bg'><div id='container'>";
			echo (U_CENTER ? "<div class='upper-block'>".U_CENTER."</div>" : "");
			echo "<div class='main-block'>".CONTENT."</div>";
			echo (L_CENTER ? "<div class='lower-block'>".L_CENTER."</div>" : "");
		echo "</div></div>";
	echo "</div>\n";
	echo "<div id='footer'>";
		echo "<div id='copyright'>";
			echo stripslashes($settings['footer'])."<br />".(!$license ? showcopyright() : "");
			echo "<br />HP Fusion Blue designed by <a href='https://harlekin-power.de' target='_blank'>Harlekin</a> &amp; <a href='https://rolly8-hl.de' target='_blank'>Rolly8-HL</a>";
		echo "</div>";
		echo "<div id='subfooter' class='clearfix'>";
			echo "".showcounter()."<br />";
			echo "".showrendertime()."";
		echo "</div>";
    echo "</div>";
}

function render_news($subject, $news, $info)
{
	global $locale, $settings, $aidlink;
	opentable($subject, "post", $info, "N");
		echo "<ul class='item-info news-info'>\n";
			echo "<li class='author'>".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</li>\n";
			echo "<li class='dated'>".showdate("newsdate", $info['news_date'])."</li>\n";
			echo "<li class='cat'>\n";
				if ($info['cat_id']) {
					echo "<a href='".BASEDIR."news_cats.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a>\n";
				} else {
					echo "<a href='".BASEDIR."news_cats.php?cat_id=0'>".$locale['global_080']."</a>";
				}
			echo "</li>\n";
			if ($info['news_ext'] == "y" || ($info['news_allow_comments'] && $settings['comments_enabled'] == "1")) {
				echo "<li class='reads'>\n";
					echo $info['news_reads'].$locale['global_074'];
				echo "</li>\n";
			}
			if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") {
				echo "<li class='comments'><a ".(isset($_GET['readmore']) ? "class='scroll'" : "")." href='".BASEDIR."news.php?readmore=".$info['news_id']."#comments'>".$info['news_comments']."".($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a></li>\n";
			}
			echo "<span class='flright'>\n";
			if (iADMIN && checkrights("N")) {
				echo "<span class='edit'><!--article_news_opts--> <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$info['news_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' width='16' height='16' style='border:0;' /></a></span>\n";
			}
			echo "<!--news_opts--><span class='print'><a href='print.php?type=N&amp;item_id=".$info['news_id']."' target='_blank'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' width='20' height='16' style='border:0;' /></a></span>\n";
			echo "</span>\n";
		echo "</ul>\n";
		echo $info['cat_image'].$news;
		if (!isset($_GET['readmore']) && $info['news_ext'] == "y") {
			echo "<div class='readmore flright'><a href='".BASEDIR."news.php?readmore=".$info['news_id']."' class='button'><span class='rightarrow icon'>".$locale['global_072']."</span></a></div>\n";
		}
    
		echo "<!--news_id-".$info['news_id']."_end-->";
    closetable();
}

function render_article($subject, $article, $info)
{
    global $locale, $settings, $aidlink;
    opentable($subject, "article", $info, "A");
    echo "<ul class='item-info article-info'>\n";
    echo "<li class='author'>".profile_link($info['user_id'], $info['user_name'], $info['user_status'])."</li>\n";
    echo "<li class='dated'>".showdate("newsdate", $info['article_date'])."</li>\n";
    echo "<li class='cat'>\n";
    if ($info['cat_id']) {
        echo "<a href='".BASEDIR."articles.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a>";
    } else {
        echo "<a href='".BASEDIR."articles.php?cat_id=0'>".$locale['global_080']."</a>";
    }
    
    echo "</li>\n";
    echo "<li class='reads'>".$info['article_reads'].$locale['global_074']."</li>\n";
    
    if ($info['article_allow_comments'] && $settings['comments_enabled'] == "1") {
        echo "<li class='comments'><a class='scroll' href='".BASEDIR."articles.php?article_id=".$info['article_id']."#comments'>".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a></li>\n";
    }
    echo "<span class='flright'>\n";
	if (iADMIN && checkrights("A")) {
		echo "<span class='edit'><!--article_admin_opts--> <a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$info['article_id']."'><img src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' width='16' height='16' style='border:0;' /></a></span>\n";
	}
	echo "<!--article_opts--><span class='print'><a href='print.php?type=A&amp;item_id=".$info['article_id']."' target='_blank'><img src='".get_image("printer")."' alt='".$locale['global_075']."' title='".$locale['global_075']."' width='20' height='16' style='border:0;' /></a></span>\n";
	echo "</span>\n";
    echo "</ul>\n";
    
    echo ($info['article_breaks'] == "y" ? nl2br($article) : $article)."\n";
    echo "<!--article_id-".$info['article_id']."_end-->";
    closetable();
}

function render_comments($c_data, $c_info)
{
    global $locale, $settings;
    if ($c_info['admin_link'] !== FALSE) {
        echo "<div class='comment-admin floatfix'>".$c_info['admin_link']."</div>\n";
    }
    if (!empty($c_data)) {
        echo "<div class='user-comments floatfix'>\n";
        $c_makepagenav = '';
        if ($c_info['c_makepagenav'] !== FALSE) {
            echo $c_makepagenav = "<div style='text-align:center;margin-bottom:5px;'>".$c_info['c_makepagenav']."</div>\n";
        }
        foreach ($c_data as $data) {
            echo "<div id='c".$data['comment_id']."' class='comment'>\n";
            //User avatar
            if ($settings['comments_avatar'] == "1") {
                echo "<span class='user_avatar'>".$data['user_avatar']."</span>\n";
                $noav = "";
            } else {
                $noav = "noavatar";
            }
            echo "<div class='tbl1 comment-wrap $noav'>";
            //Pointer tip
            if ($settings['comments_avatar'] == "1") {
                echo "<div class='pointer'><span>&lt;</span></div>\n";
            }
            //Options
            echo "<div class='comment-info'>";
            if ($data['edit_dell'] !== FALSE) {
                echo "<div class='actions flright'>".$data['edit_dell']."\n</div>\n";
            }
            //Info
            echo "<a class='scroll' href='".FUSION_REQUEST."#c".$data['comment_id']."'>#".$data['i']."</a> |\n";
            echo "<span class='comment-name'>".$data['comment_name']."</span>\n";
            echo "<span class='small'>".$data['comment_datestamp']."</span></div>\n";
            //The message
            echo "<div class='comment-msg'>".$data['comment_message']."</div></div></div>\n";
        }
        echo $c_makepagenav;
        echo "</div>\n";
    } else {
        echo "<div class='nocomments-message spacer'>".$locale['c101']."</div>\n";
    }
}

// Content Panels
function opentable($title, $collapse = false, $state = "on") {
	global $panel_collapse, $p_data; $panel_collapse = $collapse;

	echo "<div class='panel'>
		<h1 class='maincap'>
			<span>
				<span>
					<span class='title'>".$title."</span>\n";
					if ($collapse == true) {
						$boxname = str_replace(" ", "", $title);
						if ($_SERVER['PHP_SELF'] != "/news.php" && $_SERVER['PHP_SELF'] != "/articles.php") {
							echo "<span class='switcherbutton flright'>".panelbutton($state, $boxname)."</span>\n";
						}
					}
			echo "</span>
			</span>
		</h1>
		<div class='contentbody clearfix'>\n";
		if ($collapse == true) { echo panelstate($state, $boxname); }
		if ($p_data['panel_filename'] == "member_poll_panel"){echo "<div class='poll-panel'>";}
}

function closetable() {
	global $panel_collapse, $p_data;
	echo "</div></div>\n";
	if ($panel_collapse == true) {
		echo "</div>\n";
	}
}

function openside($title, $collapse = false, $state = "on")
{
	global $panel_collapse, $p_data; $panel_collapse = $collapse;
	echo "<div class='panel'><h2 class='panelcap'><span class='title'>{$title}</span>";
		if ($collapse == true) {
			$boxname = str_replace(" ", "", $title);
			echo "<span class='switcherbutton flright'>".panelbutton($state, $boxname)."</span>\n";
		}
		echo "</h2><div class='panelbody clearfix'>";
			if ($collapse == true) {
				echo panelstate($state, $boxname);
			}
			if ($p_data['panel_filename'] == "member_poll_panel"){
				echo "<div class='poll-panel'>";
			}
}

function closeside() {
	global $panel_collapse, $p_data;

	echo "</div></div>\n";
	if ($p_data['panel_filename'] == "member_poll_panel"){
		echo "</div>";
	}
	if ($panel_collapse == true) {
		echo "</div>\n";
	}
}
?>