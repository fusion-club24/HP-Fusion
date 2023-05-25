<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: latest_comments_panel.php
| Author: gh0st2k
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

$displayComments = 10;

openside($locale['global_025']);

$result = dbquery("	SELECT comment_id, comment_item_id, comment_type, comment_message
					FROM ".DB_COMMENTS."
					WHERE comment_hidden='0'
					ORDER BY comment_datestamp DESC
					");
if (dbrows($result)) {
	$output = "";
	$i = 0;
	while($data = dbarray($result)) {
		if ($i == $displayComments) { break; }
		switch ($data['comment_type']) {
			case "N":
				$access = dbcount(	"(news_id)", DB_NEWS,
									"news_id='".$data['comment_item_id']."' AND
									".groupaccess('news_visibility')." AND
									(news_start='0'||news_start<=".time().") AND
									(news_end='0'||news_end>=".time().") AND
									news_draft='0'
									");
				if ($access > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='N' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET." <a href='".BASEDIR."news.php?readmore=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$comment."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "A":
				$access = dbquery("	SELECT article_id FROM ".DB_ARTICLES." a, ".DB_ARTICLE_CATS." c WHERE
									a.article_id='".$data['comment_item_id']."' AND
									a.article_cat=c.article_cat_id AND
									".groupaccess('c.article_cat_access')." AND
									a.article_draft='0'
									");
				if (dbrows($access) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='A' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET." <a href='".BASEDIR."articles.php?article_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$comment."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "P":
				$access = dbquery("	SELECT photo_id FROM ".DB_PHOTOS." p, ".DB_PHOTO_ALBUMS." a WHERE
									p.photo_id='".$data['comment_item_id']."' AND
									p.album_id=a.album_id AND
									".groupaccess('a.album_access')
									);
				if (dbrows($access) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='P' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET." <a href='".BASEDIR."photogallery.php?photo_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$comment."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "C":
				$access = dbcount("(page_id)", DB_CUSTOM_PAGES, "page_id='".$data['comment_item_id']."' AND ".groupaccess('page_access'));
				if ($access > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='C' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET." <a href='".BASEDIR."viewpage.php?page_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$comment."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "D":
				$access = dbquery("	SELECT download_id FROM ".DB_DOWNLOADS." d, ".DB_DOWNLOAD_CATS." c WHERE
									d.download_id='".$data['comment_item_id']."' AND
									d.download_cat=c.download_cat_id AND
									".groupaccess('c.download_cat_access')
									);
				if (dbrows($access) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='D' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET." <a href='".BASEDIR."downloads.php?download_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$comment."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "T":
				$resultt = dbquery("SELECT tutorial_cat FROM ".DB_PREFIX."tutorials WHERE tutorial_id='".$data['comment_item_id']."'");
		$datats = dbarray($resultt);
				if (dbrows($resultt) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='T' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET. "<a href='".MODULS."tutorials_system/tutorials.php?cat_id=".$datats['tutorial_cat']."&amp;tutorial_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$data['comment_message']."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "TP":
				$resulttp = dbquery("SELECT tutorial_cat FROM ".DB_PREFIX."hp_tutorials WHERE tutorial_id='".$data['comment_item_id']."'");
		$datats = dbarray($resulttp);
				if (dbrows($resulttp) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='TP' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET. "<a href='".BASEDIR."HP_tutorials.php?cat_id=".$datats['tutorial_cat']."&amp;tutorial_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$data['comment_message']."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "FS":
				$resultfs = dbquery("SELECT faq_cat FROM ".DB_PREFIX."hp_faqs WHERE faq_id='".$data['comment_item_id']."'");
		$datafs = dbarray($resultfs);
				if (dbrows($resultfs) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='FS' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET. "<a href='".MODULS."HP_faq_system/faqs.php?cat_id=".$datafs['faq_cat']."&amp;faq_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$data['comment_message']."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
			case "DV":
		$resultdv = dbquery("SELECT dev_id FROM ".DB_PREFIX."dev WHERE dev_id='".$data['comment_item_id']."'");
				
				if (dbrows($resultdv) > 0) {
					$comment = trimlink($data['comment_message'], 23);
					$commentStart = dbcount("(comment_id)", DB_COMMENTS, "comment_item_id='".$data['comment_item_id']."' AND comment_type='DV' AND comment_id<=".$data['comment_id']);
					if ($commentStart > $settings['comments_per_page']) {
						$commentStart = "&amp;c_start=".floor($commentStart / $settings['comments_per_page']) * $settings['comments_per_page'];
					} else {
						$commentStart = "";
					}
					$output .= THEME_BULLET. "<a href='".MODULS."df_development_infusion/development.php?dev_id=".$data['comment_item_id'].$commentStart."#c".$data['comment_id']."' title='".$data['comment_message']."' class='side'>".$comment."</a><br />\n";
					$i++;
				}
				break;
		}
	}
	echo $output;
} else {
	echo "<div style='text-align:center'>".$locale['global_026']."</div>\n";
}
closeside();
?>