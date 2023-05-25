<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: news.php
| Author: Nick Jones (Digitanium)
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
require_once "maincore.php";
require_once DESIGNS."templates/header.php";

// Predefined variables, do not edit these values
$i = 0;

// Number of news displayed
$items_per_page = $settings['newsperpage'];

add_to_title($locale['global_200'].$locale['global_077']);

//News closed start
$set_news_closed = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='news_closed'"));
$set_ne_closed = $set_news_closed['settings_value'];
$set_news_closed_reason = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='news_closed_reason'"));
$set_ne_closed_reason = $set_news_closed_reason['settings_value'];

if ((!checkrights("S8")) && ($set_ne_closed == 1)) {
	require_once INCLUDES."bbcode_include.php";
	opentable($locale['global_081']);
	echo "<div align='center'>";
		echo "<br /><strong><span style='font-size:18pt;color:red;'>".$locale['global_082']."</span></strong><br /><br />";
		echo "<img src='".IMAGES."stop256.png' width='256' border='0' alt='Stop' /><br /><br />";
		echo "<strong><span style='font-size:18pt;'>".$locale['global_083']."</span></strong><br /><br />";
		echo "".parsesmileys(parseubb(nl2br($set_ne_closed_reason)))."<br /><br />";
	echo "</div>\n";
	closetable();
	require_once DESIGNS."templates/footer.php";
	exit;
}
if ((checkrights("S8")) && ($set_ne_closed == 1)) {
	echo "<div class='admin-message' align='center'><strong>".$locale['global_082']."</strong></div>";
}
//News closed stop

if (!isset($_GET['readmore']) || !isnum($_GET['readmore'])) {
	$rows = dbcount(
		"(news_id)",
		DB_NEWS,
		groupaccess('news_visibility')." 
		AND (news_start='0'||news_start<=".time().")
		AND (news_end='0'||news_end>=".time().")
		AND news_draft='0'"
	);
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	if ($rows) {
		$result = dbquery(
			"SELECT tn.*, tc.*, tu.user_id, tu.user_name, tu.user_status
			FROM ".DB_NEWS." tn
			LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
			LEFT JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
			WHERE ".groupaccess('news_visibility')." AND (news_start='0'||news_start<=".time().")
				AND (news_end='0'||news_end>=".time().") AND news_draft='0'
			GROUP BY news_id
			ORDER BY news_sticky DESC, news_datestamp DESC LIMIT ".$_GET['rowstart'].",".$items_per_page
		);
		$numrows = dbrows($result);
		while ($data = dbarray($result)) {
			$i++;
			$comments = dbcount("(comment_id)", DB_COMMENTS." WHERE comment_type='N' AND comment_hidden='0' AND comment_item_id='".$data['news_id']."'");
			$news_cat_image = "";
			$news_subject = "<a name='news_".$data['news_id']."' id='news_".$data['news_id']."'></a>".stripslashes($data['news_subject']);
			$news_cat_image = "<a href='".($settings['news_image_link'] == 0 ? "news_cats.php?cat_id=".$data['news_cat']
																				: FUSION_SELF."?readmore=".$data['news_id'] )."'>";
			if ($data['news_image_t2'] && $settings['news_image_frontpage'] == 0) {
				$news_cat_image .= "<img src='".IMAGES_N_T.$data['news_image_t2']."' alt='".$data['news_subject']."' class='news-category' /></a>";
			} elseif ($data['news_cat_image']) {
				$news_cat_image .= "<img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' class='news-category' /></a>";
			} else {
				$news_cat_image = "";
			}
			$news_news = preg_replace("/<!?--\s*pagebreak\s*-->/i", "", ($data['news_breaks'] == "y" ? stripslashes(nl2br(parsesmileys(parseubb($data['news_news'])))) : stripslashes(parsesmileys(parseubb($data['news_news'])))));
			$news_info = array(
				"news_id" => $data['news_id'],
				"user_id" => $data['user_id'],
				"user_name" => $data['user_name'],
				"user_status" => $data['user_status'],
				"news_date" => $data['news_datestamp'],
				"cat_id" => $data['news_cat'],
				"cat_name" => $data['news_cat_name'],
				"cat_image" => $news_cat_image,
				"news_subject" => $data['news_subject'],
				"news_ext" => $data['news_extended'] ? "y" : "n",
				"news_reads" => $data['news_reads'],
				"news_comments" => $comments,
				"news_allow_comments" => $data['news_allow_comments'],
				"news_sticky" => $data['news_sticky']
			);

			echo "<!--news_prepost_".$i."-->\n";
			render_news($news_subject, $news_news, $news_info);
		}
		echo "<!--sub_news_idx-->\n";
		if ($rows > $items_per_page) echo "<div align='center' style=';margin-top:5px;'>\n".makepagenav($_GET['rowstart'],$items_per_page,$rows,3)."\n</div>\n";
	} else {
		opentable($locale['global_077']);
		echo "<div style='text-align:center'><br />\n".$locale['global_078']."<br /><br />\n</div>\n";
		closetable();
	}
} else {
	if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }
	$result = dbquery(
		"SELECT tn.*, tc.*, tu.user_id, tu.user_name, tu.user_status FROM ".DB_NEWS." tn
		LEFT JOIN ".DB_USERS." tu ON tn.news_name=tu.user_id
		LEFT JOIN ".DB_NEWS_CATS." tc ON tn.news_cat=tc.news_cat_id
		WHERE ".groupaccess('news_visibility')." AND news_id='".$_GET['readmore']."' AND news_draft='0'
		LIMIT 1"
	);
	if (dbrows($result)) {
		include INCLUDES."comments_include.php";
		include INCLUDES."ratings_include.php";
		$data = dbarray($result);
		if (!isset($_POST['post_comment']) && !isset($_POST['post_rating'])) {
			$result2 = dbquery("UPDATE ".DB_NEWS." SET news_reads=news_reads+1 WHERE news_id='".$_GET['readmore']."'");
			$data['news_reads']++;
		}
		$news_cat_image = "";
		$news_subject = $data['news_subject'];
		if ($data['news_image_t1'] && $settings['news_image_readmore'] == "0") {
			$img_size = @getimagesize(IMAGES_N.$data['news_image']);
			$news_cat_image = "<a href=\"javascript:;\" onclick=\"window.open('".IMAGES_N.$data['news_image']."','','scrollbars=yes,toolbar=no,status=no,resizable=yes,width=".($img_size[0]+20).",height=".($img_size[1]+20)."')\"><img src='".IMAGES_N_T.$data['news_image_t1']."' alt='".$data['news_subject']."' class='news-category' /></a>";
		} elseif ($data['news_cat_image']) {
			$news_cat_image = "<a href='news_cats.php?cat_id=".$data['news_cat']."'><img src='".get_image("nc_".$data['news_cat_name'])."' alt='".$data['news_cat_name']."' class='news-category' /></a>";
		}
		$news_news = preg_split("/<!?--\s*pagebreak\s*-->/i", $data['news_breaks'] == "y" ? stripslashes(nl2br(parsesmileys(parseubb($data['news_extended'] ? $data['news_extended'] : $data['news_news'])))) : stripslashes(parsesmileys(parseubb($data['news_extended'] ? $data['news_extended'] : $data['news_news']))));    
		$pagecount = count($news_news);
		$news_info = array(
			"news_id" => $data['news_id'],
			"user_id" => $data['user_id'],
			"user_name" => $data['user_name'],
			"user_status" => $data['user_status'],
			"news_date" => $data['news_datestamp'],
			"cat_id" => $data['news_cat'],
			"cat_name" => $data['news_cat_name'],
			"cat_image" => $news_cat_image,
			"news_subject" => $data['news_subject'],
			"news_ext" => "n",
			"news_reads" => $data['news_reads'],
			"news_comments" => dbcount("(comment_id)", DB_COMMENTS, "comment_type='N' AND comment_item_id='".$data['news_id']."' AND comment_hidden='0'"),
			"news_allow_comments" => $data['news_allow_comments'],
			"news_sticky" => $data['news_sticky']
		);
		add_to_title($locale['global_201'].$news_subject);
		echo "<!--news_pre_readmore-->";
		render_news($news_subject, $news_news[$_GET['rowstart']], $news_info);
		echo "<!--news_sub_readmore-->";
		if ($pagecount > 1) {
			echo "<div align='center' style='margin-top:5px;'>\n".makepagenav($_GET['rowstart'], 1, $pagecount, 3, FUSION_SELF."?readmore=".$_GET['readmore']."&amp;")."\n</div>\n";
		}
		if ($data['news_allow_comments']) { showcomments("N", DB_NEWS, "news_id", $_GET['readmore'], FUSION_SELF."?readmore=".$_GET['readmore']); }
		if ($data['news_allow_ratings']) { showratings("N", $_GET['readmore'], FUSION_SELF."?readmore=".$_GET['readmore']); }
	} else {
		redirect(FUSION_SELF);
	}
}

require_once DESIGNS."templates/footer.php";
?>