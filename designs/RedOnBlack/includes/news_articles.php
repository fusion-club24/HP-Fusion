<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
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
	 

 function render_news($subject, $news, $info) {
    global $locale, $settings, $aidlink;
	$parameter= $settings['siteurl']."news.php?readmore=".$info['news_id'];

$title= $settings['sitename'].$locale['global_200'].$locale['global_077'].$locale['global_201'].$info['news_subject']."".$locale['global_200'];

	$breaking_news = 56600; //Breaking News Time (1 Hour/60 Mins/3600 Seconds)
      opentable($subject.(time()-$info['news_date'] < $breaking_news ? " <img class='breaking-news' src='".get_image("new")."' title='' alt=''/>" : 
	($info['news_sticky'] == 1 ? "<img class='sticky-news' src='".get_image("sticky")."' title='' alt=''/>" : "")));
    echo" <div class='news-info'><img src='".get_image("author")."' alt='".$locale['global_070']." ".$info['user_name']."'  title='".$locale['global_070'].$info['user_name']."' class='news-icons' /> ".profile_link($info['user_id'], $info['user_name'], $info['user_status']);
          
          echo " <img src='".get_image("date")."' alt='".$locale['global_049']."".$locale['global_071']." ".showdate("%d-%m-%Y %H:%M", $info['news_date'])."' title='".$locale['global_049']." ".$locale['global_071'].showdate("%d-%m-%Y %H:%M", $info['news_date'])."' class='news-icons' /> ".showdate("%d-%m-%Y %H:%M", $info['news_date'])."\n
		  <img src='".get_image("reads")."' alt='".$info['news_reads']." ".$locale['global_074']."' title='".$info['news_reads']." ".$locale['global_074']."' class='news-icons' /> ".$info['news_reads']." ".$locale['global_074']."";
             if ($info['news_allow_comments'] && $settings['comments_enabled'] == "1") { echo "&nbsp; <img src='".get_image("comments")."' alt='".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."'  title='".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."' class='news-icons' /><a href='".BASEDIR."news.php?readmore=".$info['news_id']."#comments'>".$info['news_comments'].($info['news_comments'] == 1 ? $locale['global_073b'] : $locale['global_073'])."</a>\n"; }
  echo"<img src='".get_image("category")."' alt='".$info['cat_name']."' title='".$info['cat_name']."' class='news-icons' /> <a href='".BASEDIR."news_cats.php?cat_id=".$info['cat_id']."'>".$info['cat_name']."</a>";

              
              echo "<div class='float-right clearfix'>\n
	           <a href='".BASEDIR."print.php?type=N&amp;item_id=".$info['news_id']."'><img class='news-iconsb' src='".get_image("print")."' title='Print' alt='printer' /></a>\n";
	         if (iADMIN && checkrights("N")) {
          echo " <a href='".ADMIN."news.php".$aidlink."&amp;action=edit&amp;news_id=".$info['news_id']."'><img class='news-iconsb' src='".get_image("edit")."' alt='".$locale['global_076']."' title='".$locale['global_076']."' border='0' /></a>\n";
	            }
	      echo "</div>\n";
	     echo"</div>\n";
             
      echo "<div class='news-body floatfix'>".$info['cat_image'].$news."</div>\n";
     if ($info['news_ext'] == "y") {  echo" <div class='news-infob'>";
		echo" <a class='news-button' href='".BASEDIR."news.php?readmore=".$info['news_id']."'>".$locale['global_072']." » </a>";	
    echo"</div>\n";
   }
 
echo"<div class='share'><a class='fpst_bookmark' onclick=\"window.open('http://www.facebook.com/share.php?u=".$parameter."','','location=no,scrollbars=yes,width=550,height=400,left='+(screen.availWidth/2-200)+',top='+(screen.availHeight/2-200)+'');return false;\"><img src='".get_image("facebook")."' alt='Share on Facebook'  title='Share on Facebook' style='border:0px; vertical-align: middle;'/></a>\n
<a class='fpst_bookmark' onclick=\"window.open('http://twitter.com/share?url=".$parameter."&text=$title','','location=no,scrollbars=yes,width=550,height=400,left='+(screen.availWidth/2-200)+',top='+(screen.availHeight/2-200)+'');return false;\"><img src='".get_image("twitter")."' alt='Share on Twitter'  title='Share on Twitter' style='border:0px; vertical-align: middle;'/></a>\n
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


 ?>