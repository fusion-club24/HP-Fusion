<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: css_navigation_panel.php
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
if (!defined("IN_FUSION")) { die("Access Denied"); }
include LOCALE.LOCALESET."forum/main.php";

global $lastvisited;

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
echo "<script type='text/javascript' language='JavaScript' src='".INCLUDES."js/forum_prev.js'></script>";
add_to_head("<style type='text/css'>
.preview_info {
	width: 460px;
}
</style>");

$data = dbarray(dbquery(
	"SELECT tt.thread_lastpost
	FROM ".DB_FORUMS." tf
	INNER JOIN ".DB_THREADS." tt ON tf.forum_id = tt.forum_id
	WHERE ".groupaccess('tf.forum_access')." AND thread_hidden='0'
	ORDER BY tt.thread_lastpost DESC LIMIT ".($settings['numofthreads']-1).", ".$settings['numofthreads']
));

$timeframe = empty($data['thread_lastpost']) ? 0 : $data['thread_lastpost'];

$result = dbquery(
	"SELECT tt.*, tf.forum_id, tf.forum_name, tf.forum_access, tu.user_id, tu.user_name,
	tu.user_status, tau.user_name AS author, tau.user_status AS author_status
	FROM ".DB_THREADS." tt
	INNER JOIN ".DB_FORUMS." tf ON tt.forum_id=tf.forum_id
	INNER JOIN ".DB_POSTS." tp USING(thread_id)
	INNER JOIN ".DB_USERS." tu ON tt.thread_lastuser=tu.user_id
	INNER JOIN ".DB_USERS." tau ON tt.thread_author=tau.user_id 
	WHERE ".groupaccess('tf.forum_access')." AND tt.thread_lastpostid = tp.post_id AND tt.thread_lastpost >= ".$timeframe." AND tt.thread_hidden='0'
	ORDER BY tt.thread_lastpost DESC LIMIT 0,".$settings['numofthreads']
);

if (dbrows($result)) {
	$i = 0;
	opentable($locale['global_040']);
	//Forum closed start
	$set_forum_closed = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='forum_closed'"));
	$set_fo_closed = $set_forum_closed['settings_value'];
	if ($set_fo_closed == 1) {
		echo "<div class='admin-message' align='center'><strong>".$locale['focl002']."</strong></div>";
	}
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
	echo "<td class='tbl2 forum-caption'>&nbsp;</td>\n";
	echo "<td width='100%' class='tbl2 forum-caption'><strong>".$locale['global_044']."</strong></td>\n";
	echo "<td width='1%' class='tbl2 forum-caption' style='text-align:center;white-space:nowrap'>".$locale['global_050']."</td>\n";
	echo "<td width='1%' class='tbl2 forum-caption' style='text-align:center;white-space:nowrap'><img src='".IMAGES."forum/views.png' alt='".$locale['453']."'  title='".$locale['453']."' style='vertical-align:middle;' /></td>\n";
	echo "<td width='1%' class='tbl2 forum-caption' style='text-align:center;white-space:nowrap'><img src='".IMAGES."forum/reads.png' alt='".$locale['454']."' title='".$locale['454']."' style='vertical-align:middle;' /></td>\n";
	echo "<td width='1%' class='tbl2 forum-caption' style='text-align:center;white-space:nowrap'><strong>".$locale['global_047']."</strong></td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
	//
	$originalpost = dbarray(dbquery("SELECT * FROM ".DB_POSTS." WHERE thread_id='".$data['thread_id']."' ORDER BY post_id ASC limit 1"));
    $post_messagefirst1 = $originalpost['post_smileys'] == 1 ? parsesmileys($originalpost['post_message']) : $originalpost['post_message'];
    $post_messagefirst1 = phpentities(nl2br(parseubb($post_messagefirst1)));
	$originalpost = dbarray(dbquery("SELECT * FROM ".DB_POSTS." WHERE post_id='".$data['thread_lastpostid']."' ORDER BY post_id ASC limit 1"));
    $post_message = $originalpost['post_smileys'] == 1 ? parsesmileys($originalpost['post_message']) : $originalpost['post_message'];
    $post_message = phpentities(nl2br(parseubb($post_message)));
	//
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
		echo "<tr>\n<td class='".$row_color."'>";
		if ($data['thread_locked']) {
		echo "<img src='".get_image("folderlock")."' alt='' />";
		} else {
		if ($data['thread_lastpost'] > $lastvisited) {
			$thread_match = $data['thread_id']."\|".$data['thread_lastpost']."\|".$data['forum_id'];
			if (iMEMBER && ($data['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
				echo "<img src='".get_image("folder")."' alt='' />";
			} else {
				echo "<img src='".get_image("foldernew")."' alt='' />";
			}
		} else {
			echo "<img src='".get_image("folder")."' alt='' />";
		}
		
		}
		echo "</td>\n";
		if ($data['thread_poll']) {
			$thread_poll = "<span class='small' style='font-weight:bold'>[".$locale['global_051']."]</span>";
		} else {
			$thread_poll = "";
		}
		echo "<td width='100%' class='".$row_color."'><a href='".FORUM."viewforum.php?forum_id=".$data['forum_id']."' title='".$locale['401']."'>".$data['forum_name']."</a><br /><span class='small'><strong>".$locale['412']."</strong> ".$thread_poll." <a title=\"header=[ ".str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 70)))."] body=[".str_replace("]", "]]", str_replace("[", "[[", trimlink($post_messagefirst1, 250)))."] cssbody=[tbl1 preview_info] cssheader=[tbl2 preview_info] delay=[0] fade=[on] offsetx=[15] offsety=[5]\" href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."'>".trimlink($data['thread_subject'], 70)."</a> <a title=\"header=[ ".str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 70)))."] body=[".str_replace("]", "]]", str_replace("[", "[[", trimlink($post_message, 250)))."] cssbody=[tbl1 preview_info] cssheader=[tbl2 preview_info] delay=[0] fade=[on] offsetx=[15] offsety=[5]\" href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."'><img src='".IMAGES."forum/latest_post.png' style='vertical-align:middle;' /></a></span></td>\n";
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".profile_link($data['thread_author'], $data['author'], $data['author_status'])."</td>\n";
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".$data['thread_views']."</td>\n";
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".($data['thread_postcount']-1)."</td>\n";
		echo "<td width='1%' class='".$row_color."' style='text-align:center;white-space:nowrap'>".showdate("forumdate", $data['thread_lastpost'])."<br />\n<span class='small'>".$locale['406'].profile_link($data['thread_lastuser'], $data['user_name'], $data['user_status'])."</span> <a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."' title='".$locale['404']."'> <img src='".IMAGES."forum/latest_post.png' style='vertical-align:middle;' /></a></td>\n";
		echo "</tr>\n";
		$i++;
	}
	echo "</table>\n";
	if (iMEMBER) {
		echo "<div class='tbl1' style='text-align:center'><a href='".MODULS."forum_threads_list_panel/my_threads.php'>".$locale['global_041']."</a> ::\n";
		echo "<a href='".MODULS."forum_threads_list_panel/my_posts.php'>".$locale['global_042']."</a> ::\n";
		echo "<a href='".MODULS."forum_threads_list_panel/new_posts.php'>".$locale['global_043']."</a>";
		if($settings['thread_notify']) {
			echo " ::\n<a href='".MODULS."forum_threads_list_panel/my_tracked_threads.php'>".$locale['global_056']."</a>";
		}
		echo "</div>\n";
	}
	closetable();
}
?>
