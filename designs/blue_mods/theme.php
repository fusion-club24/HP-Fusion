<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Name: Blue Mods Theme
| Filename: theme.php
| Author: PHP-Fusion Mods UK
| Version: v1.00
| Developers: Craig
| Site: http://www.phpfusionmods.co.uk
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

define("THEME_WIDTH", "1000px");
define("THEME_BULLET", "<img src='".DESIGN."images/bullet.png' alt='' class='theme-bullet'/>");
set_image("author",  DESIGN."images/author.png");
set_image("date",  DESIGN."images/date.png");
set_image("reads",  DESIGN."images/reads.png");
set_image("comments",  DESIGN."images/comments.png");
set_image("readmore",  DESIGN."images/readmore.png");
set_image("edit",  DESIGN."images/edit.png");
set_image("print",  DESIGN."images/printer.png");
set_image("sticky",  DESIGN."images/sticky.png");
set_image("new",  DESIGN."images/new.png");
set_image("facebook",  DESIGN."images/facebook.png");
set_image("google+",  DESIGN."images/google.png");
set_image("twitter",  DESIGN."images/twitter.png");

function render_page($license=false) {
	
	global $settings, $locale, $main_style, $mysql_queries_time, $aidlink, $userdata;
	
	echo "<div class='outer-container'>\n";
		echo "<div class='sub-header clearfix'>\n
			<div class='float-left'>";
				include DESIGN."header_navigation/header_navi_respo.php";
			echo"</div>\n
			
		</div>\n";
    
		echo"<div id='middle_box'>
			<div style='padding-top: 7px; padding-left: 10px;'>".showbanners()."</div>
			<div class='middle_box_content'></div>
		</div>
    
    
		<div class='pattern_bg'>
			<div class='pattern_box'>
				<div class='pattern_box_icon'><img style='border: 0px;' src='".DESIGN.	"images/icon1.png' alt='' title=''  /></div>
				<div class='pattern_content'>
					<h1>HP-Fusion</h1>
					<p class='pat'>
						HP-Fusion basiert auf PHP Fusion 7.02.07, wurde und wird aber in Teilen erweitert und geändert. HP-Fusion ist ein privates Projekt und pauschal nicht mehr mit PHP Fusion kompatibel. HP-Fusion unterliegt aber weiterhin den Lizenzen von PHP Fusion!
					</p>
				</div>
			</div>

			<div class='pattern_box'>
				<div class='pattern_box_icon'><img style='border: 0px;' src='".DESIGN."images/icon2.png' alt='' title=''  /></div>
				<div class='pattern_content'>
					<h1>Unsere Community</span></h1>
					<p class='pat'>
						In unserer Community treffen sich die verschiedensten Leuten, die sich für unser privates Projekt interessieren, begeistern und gegenseitig helfen. Schau es dir einfach an und helfe mit HP-Fusion zu verbessern.
					</p>
				</div>
			</div>        
		</div>\n";

		echo "<div class='container clearfix $main_style'>\n";
			if (LEFT || RIGHT) {
				echo "<div class='side-border-left'>".LEFT."".RIGHT."</div>\n";
			}
			echo "<div class='main-content'><div class='main-container'>".U_CENTER.CONTENT.L_CENTER."</div></div>\n";   
		echo"</div>
		<div id='footer'>";
			echo" <div class='copyright'>"; 
				echo " <div class='box_content'>
					<div class='box_title'>
						<div class='title_icon'><img style='border: 0px;' src='".DESIGN."images/mini_icon1.gif' alt='' title='' /></div>
						<div class='title-title'>Neusten <span class=''>News</span></div>
					</div>

					<div class='box_text_content'>
						<div class='box_text'>";
							include LOCALE.LOCALESET."news_cats.php";
							$result = dbquery("SELECT news_id, news_subject, news_datestamp FROM ".DB_NEWS." tn 
							LEFT JOIN ".DB_NEWS_CATS." tnc 
							ON tn.news_cat=tnc.news_cat_id
							WHERE ".groupaccess('news_visibility')."
							ORDER BY news_datestamp DESC LIMIT 0,4");
							if (dbrows($result)) {
								while($data = dbarray($result)) {
									$news_subject = trimlink($data['news_subject'], 20);
									echo"<a href='".BASEDIR."news.php?readmore=".$data['news_id']."'  class='white' title='".$data['news_subject']."'>".$news_subject."</a><br />\n";
								}

							} else {
								echo $locale['404'];
							}
						echo"</div>
						<a href='".BASEDIR."news.php' class='details'>+ More</a>
					</div>
				</div>
				<div class='box_content'>
					<div class='box_title'>
						<div class='title_icon'><img style='border: 0px;' src='".DESIGN."images/mini_icon2.gif' alt='' title='' /></div>
						<div class='title-title'>Neusten <span class=''>Downloads</span></div>
					</div>
					<div class='box_text_content'>
						<div class='box_text'>";
							include LOCALE.LOCALESET."downloads.php";
							$result = dbquery("SELECT td.download_id, td.download_datestamp,td.download_title, td.download_cat,
							tc.download_cat_id, tc.download_cat_access
							FROM ".DB_DOWNLOADS." td
							LEFT JOIN ".DB_DOWNLOAD_CATS." tc ON td.download_cat=tc.download_cat_id
							WHERE ".groupaccess('download_cat_access')."
							ORDER BY download_datestamp DESC LIMIT 0,4");
							if (dbrows($result)) {
								while($data = dbarray($result)) {
									$download_title = trimlink($data['download_title'], 20);
									echo"<a href='".BASEDIR."downloads.php?cat_id=".$data['download_cat_id']."&amp;download_id=".$data['download_id']."'  class='white' title='".$data['download_title']."'>".$download_title."</a><br />\n";
								}
							} else {
								echo $locale['431'];
							}
						echo" </div>
						<a href='".BASEDIR."downloads.php' class='details'>+ More</a>
					</div>
				</div>            
				<div class='box_content'>
					<div class='box_title'>
						<div class='title_icon'><img style='border: 0px;' src='".DESIGN."images/mini_icon3.gif' alt='' title='' /></div>
						<div class='title-title'>Neusten <span class=''>Weblinks</span></div>
					</div>
					<div class='box_text_content'>
						<div class='box_text'>";
							include LOCALE.LOCALESET."weblinks.php";
							$result = dbquery("SELECT weblink_id, weblink_name, weblink_datestamp FROM ".DB_WEBLINKS." tl 
							LEFT JOIN ".DB_WEBLINK_CATS." tc 
							ON tl.weblink_cat=tc.weblink_cat_id
							WHERE ".groupaccess('tc.weblink_cat_access')."
							ORDER BY weblink_datestamp DESC LIMIT 0,4");
							if (dbrows($result)) {
								while($data = dbarray($result)) {
									$weblink_name = trimlink($data['weblink_name'], 20);
									echo"<a href='".BASEDIR."weblinks.php?weblink_id=".$data['weblink_id']."' class='white' title='".$data['weblink_name']."' target='_blank'>".$weblink_name."</a><br />\n";
								}
							} else {
								echo $locale['431'];
							}
						echo" </div>
						<a href='".BASEDIR."weblinks.php' class='details'>+ More</a>
					</div>
				</div>";
				echo "<center>".showcopyright()."<br />";
				echo "".showrendertime()."</center>";

				echo"<br /><span style='font-size: 11px;'> ".stripslashes($settings['footer'])."   </span>
			</div>
		</div>
	</div>\n";
}    
	
function render_comments($c_data, $c_info){
	global $locale, $settings;
	opentable($locale['c100']);
	if (!empty($c_data)){
		echo "<div class='comments floatfix'>";
			if ($c_info['admin_link'] !== false) {
				echo "<div class='floatfix'>";
					echo "<div class='comment_admin'>".$c_info['admin_link']."</div>";
				echo "</div>";
			}

			foreach($c_data as $data) {
				$comm_count = "<a href='".FUSION_REQUEST."#c".$data['comment_id']."' id='c".$data['comment_id']."' name='c".$data['comment_id']."'>#".$data['i']."</a>";
				echo "<div class='comment-main spacer'>";
					echo "<div class='tbl2 clearfix floatfix'>";
						if ($settings['comments_avatar'] == "1") { echo "<span class='comment-avatar'>".$data['user_avatar']."</span>"; }
						echo "<span style='float:right' class='comment_actions'>".$comm_count."</span>
						<span class='comment-name small'>".$data['comment_name']."</span>
						<span class='small'>".$data['comment_datestamp']."</span> ";
						if ($data['edit_dell'] !== false) { echo "<span class='comment_actions'>".$data['edit_dell']."</span>"; }
						echo "<div class='tbl2 comment_message'>".$data['comment_message']."</div>";
					echo "</div>";
				echo "</div>";
			}
		echo "</div>";
	} else {
		echo "<div class='nocomments-message spacer'>".$locale['c101']."</div>";
	}
	closetable();
}

function render_news($subject, $news, $info) {
    global $locale, $settings, $aidlink;
	$parameter= $settings['siteurl']."news.php?readmore=".$info['news_id'];
	$title= $settings['sitename'].$locale['global_200'].$locale['global_077'].$locale['global_201'].$info['news_subject']."".$locale['global_200'];

	$breaking_news = 7200; //Breaking News Time (1 Hour/60 Mins/3600 Seconds)
      opentable($subject.(time()-$info['news_date'] < $breaking_news ? " <img class='breaking-news' src='".get_image("new")."' title='New News Item' alt='New News Item'/>" : 
	($info['news_sticky'] == 1 ? "<img class='sticky-news' src='".get_image("sticky")."' title='Sticky News Item' alt='Sticky News Item'/>" : "")));
    echo" <div class='news-info'><img src='".get_image("author")."' alt='".$locale['global_070']." ".$info['user_name']."'  title='".$locale['global_070'].$info['user_name']."' class='news-icons' /> ".profile_link($info['user_id'], $info['user_name'], $info['user_status']);
          echo " <img src='".get_image("date")."' alt='".$locale['global_049']."".$locale['global_071']." ".showdate("%d-%m-%Y %H:%M", $info['news_date'])."' title='".$locale['global_049']." ".$locale['global_071'].showdate("%d-%m-%Y %H:%M", $info['news_date'])."' class='news-icons' /> ".showdate("%d-%m-%Y %H:%M", $info['news_date'])."\n
		  <img src='".get_image("reads")."' alt='".$info['news_reads']." ".$locale['global_074']."' title='".$info['news_reads']." ".$locale['global_074']."' class='news-icons' /> ".$info['news_reads']." ".$locale['global_074']."";
             if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") { echo "&nbsp; <img src='".get_image("comments")."' alt='".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."'  title='".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."' class='news-icons' /> <a href='".BASEDIR."news.php?readmore=".$info['news_id']."#comments'>".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a>\n"; }
              echo "<div class='float-right clearfix'>\n
	           <a href='".BASEDIR."print.php?type=N&amp;item_id=".$info['news_id']."'><img class='news-iconsb' src='".get_image("print")."' title='Print' alt='printer' /></a>\n";
	            if (iADMIN && checkrights("N")) {
                echo " <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$info['news_id']."'><img class='news-iconsb' src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' border='0' /></a>\n";
	            }
	           echo "</div>\n";
	          echo"</div>\n";
             echo "<div class='news-body floatfix'>".$info['cat_image'].$news."</div>\n";
            if ($info['news_ext'] == "y") {  echo" <div class='news-infob'>";
				echo "<a href='".BASEDIR."news.php?readmore=".$info['news_id']."' class='button'><span class='rightarrow icon'></span>".$locale['global_072']."</a>\n";
    echo"</div>\n";
   }
 
echo"<div class='share'><a class='fpst_bookmark' onclick=\"window.open('https://www.facebook.com/share.php?u=".$parameter."','','location=no,scrollbars=yes,width=550,height=400,left='+(screen.availWidth/2-200)+',top='+(screen.availHeight/2-200)+'');return false;\"><img src='".get_image("facebook")."' alt='Share on Facebook'  title='Share on Facebook' style='border:0px; vertical-align: middle;'/></a>\n
<a class='fpst_bookmark' onclick=\"window.open('https://twitter.com/share?url=".$parameter."&text=$title','','location=no,scrollbars=yes,width=550,height=400,left='+(screen.availWidth/2-200)+',top='+(screen.availHeight/2-200)+'');return false;\"><img src='".get_image("twitter")."' alt='Share on Twitter'  title='Share on Twitter' style='border:0px; vertical-align: middle;'/></a>\n
<a class='fpst_bookmark' onclick=\"window.open('https://plus.google.com/share?url=".$parameter."','','location=no,scrollbars=yes,width=550,height=400,left='+(screen.availWidth/2-200)+',top='+(screen.availHeight/2-200)+'');return false;\"><img src='".get_image("google+")."' alt='Share on Google+' title='Share on google+' style='border: 0px; vertical-align:middle;' /></a></div>\n";
 closetable();
}

function render_article($subject, $article, $info) {
    global $locale, $settings, $aidlink;
    opentable($subject);
    echo" <div class='news-info'><img src='".get_image("author")."' alt='".$locale['global_070']." ".$info['user_name']."'  title='".$locale['global_070'].$info['user_name']."' class='news-icons' /> ".profile_link($info['user_id'], $info['user_name'], $info['user_status']);
          echo " <img src='".get_image("date")."'alt='".$locale['global_049']."".$locale['global_071']." ".showdate("%d-%m-%Y %H:%M", $info['article_date'])."' title='".$locale['global_049']." ".$locale['global_071'].showdate("%d-%m-%Y %H:%M", $info['article_date'])."' class='news-icons' /> ".showdate("%d-%m-%Y %H:%M", $info['article_date'])."\n
		    <img src='".get_image("reads")."' alt='".$info['article_reads']." ".$locale['global_074']."' title='".$info['article_reads']." ".$locale['global_074']."' class='news-icons' /> ".$info['article_reads']." ".$locale['global_074']."";
             if ($info['article_allow_comments'] && $settings['comments_enabled'] == "1") { echo "&nbsp; <img src='".get_image("comments")."' alt='".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."'  title='".$info['article_comments'].($info['article_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."' class='news-icons' /> <a href='".BASEDIR."articles.php?article_id=".$info['article_id']."#comments'>".$info['article_comments'].($info['article_comments'] == 1 ? $locale[	'global_073b'] : $locale['global_073'])."</a>\n"; }
              echo "<div class='float-right clearfix'>\n
	               <a href='".BASEDIR."print.php?type=A&amp;item_id=".$info['article_id']."'><img class='news-iconsb' src='".get_image("print")."' title='Print' alt='printer' /></a>\n";
	            if (iADMIN && checkrights("A")) {
	           echo "<a href='".ADMIN."articles.php".$aidlink."&amp;action=edit&amp;article_id=".$info['article_id']."'><img class='news-iconsb' src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' border='0' /></a>\n";
               }
	           echo "</div>\n</div>\n
             <div class='news-body floatfix'>".($info['article_breaks'] == "y" ? nl2br($article) : $article)."</div>\n
           <div class='news-infob'>";
   echo"</div>\n";
    closetable();

}

function opentable($title, $collapse = false, $state = "on") {
global $panel_collapse, $p_data; $panel_collapse = $collapse; 
	echo "<div class='capmain-top'></div>";
		echo "<div class='cap-main'>
			<span class='title'>".$title."</span>\n";
			if ($collapse == true) {
				$boxname = str_replace(" ", "", $title);
				echo "<span class='switcherbutton flright'>".panelbutton($state, $boxname)."</span>\n";
			}
		echo "</div>";
		echo "<div class='main-border'>";
}

function closetable() {
global $panel_collapse, $p_data;
	echo "</div>";
}

function openside($title, $collapse = false, $state = "on") {
	global $panel_collapse, $p_data; $panel_collapse = $collapse;
	echo "<div class='scapmain-top'></div>";
	echo "<div class='scap-main'>
		<span class='title'>".$title."</span>\n";
				if ($collapse == true) {
					$boxname = str_replace(" ", "", $title);
					echo "<span class='switcherbutton flright'>".panelbutton($state, $boxname)."</span>\n";
				}
	echo "</div>";
	//echo "</div>";
	echo "<div class='side-body floatfix'>";
		if ($collapse == true) { echo panelstate($state, $boxname); }
		if ($p_data['panel_filename'] == "member_poll_panel"){echo "<div class='poll-panel'>";}
}

function closeside($collapse = false) {
	global $panel_collapse, $p_data;
	echo "</div>\n";
	if ($p_data['panel_filename'] == "member_poll_panel"){echo "</div>";}
	if ($panel_collapse == true) { echo "</div>\n"; }

}

?>