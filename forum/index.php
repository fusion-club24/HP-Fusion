<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: index.php
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
require_once "../maincore.php";
require_once DESIGNS."templates/header.php";
include LOCALE.LOCALESET."forum/main.php";

if (!isset($lastvisited) || !isnum($lastvisited)) { $lastvisited = time(); }
echo "<script type='text/javascript' language='JavaScript' src='".INCLUDES."js/forum_prev.js'></script>";
add_to_head("<style type='text/css'>
.preview_info {
	width: 460px;
}
</style>");

add_to_title($locale['global_200'].$locale['400']);

//Forum closed start
$set_forum_closed = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='forum_closed'"));
$set_fo_closed = $set_forum_closed['settings_value'];
$set_forum_closed_reason = dbarray(dbquery("SELECT settings_value FROM ".DB_SETTINGS." WHERE settings_name='forum_closed_reason'"));
$set_fo_closed_reason = $set_forum_closed_reason['settings_value'];

if ((!checkrights("S3")) && ($set_fo_closed == 1)) {
	require_once INCLUDES."bbcode_include.php";
	opentable($locale['focl001']);
	echo "<div align='center'>";
		echo "<br /><strong><span style='font-size:18pt;color:red;'>".$locale['focl002']."</span></strong><br /><br />";
		echo "<img src='".IMAGES."stop256.png' width='256' border='0' alt='Stop' /><br /><br />";
		echo "<strong><span style='font-size:18pt;'>".$locale['focl003']."</span></strong><br /><br />";
		echo "".parsesmileys(parseubb(nl2br($set_fo_closed_reason)))."<br /><br />";
	echo "</div>\n";
	closetable();
	require_once DESIGNS."templates/footer.php";
	exit;
}
if ((checkrights("S3")) && ($set_fo_closed == 1)) {
	echo "<div class='admin-message' align='center'><strong>".$locale['focl002']."</strong></div>";
}
//Forum closed stop

opentable($locale['400']);
echo "<!--pre_forum_idx--><table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_idx_table'>\n";

$forum_list = ""; $current_cat = "";
$result = dbquery("SELECT
   f.forum_id, f.forum_cat, f.forum_name, f.forum_description, f.forum_moderators, f.forum_lastpost, f.forum_postcount,
   f.forum_threadcount, f.forum_lastuser, f.forum_access, f2.forum_name AS forum_cat_name,
   t.thread_id, t.thread_lastpost, t.thread_lastpostid, t.thread_subject, t.thread_poll, t.thread_locked,
   u.user_id, u.user_name, u.user_status
   FROM ".DB_FORUMS." f
   LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat = f2.forum_id
   LEFT JOIN ".DB_THREADS." t ON f.forum_id = t.forum_id AND f.forum_lastpost=t.thread_lastpost
   LEFT JOIN ".DB_USERS." u ON f.forum_lastuser = u.user_id
   WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0'
   GROUP BY forum_id, thread_id ORDER BY f2.forum_order ASC, f.forum_order ASC, t.thread_lastpost DESC"
);
if (dbrows($result) != 0) {
	while ($data = dbarray($result)) {
	$originalpost = dbarray(dbquery("SELECT * FROM ".DB_POSTS." WHERE thread_id='".$data['thread_id']."' ORDER BY post_id ASC limit 1"));
    $post_messagefirst1 = $originalpost['post_smileys'] == 1 ? parsesmileys($originalpost['post_message']) : $originalpost['post_message'];
    $post_messagefirst1 = phpentities(nl2br(parseubb($post_messagefirst1)));
	$originalpost = dbarray(dbquery("SELECT * FROM ".DB_POSTS." WHERE post_id='".$data['thread_lastpostid']."' ORDER BY post_id ASC limit 1"));
    $post_message = $originalpost['post_smileys'] == 1 ? parsesmileys($originalpost['post_message']) : $originalpost['post_message'];
    $post_message = phpentities(nl2br(parseubb($post_message)));
		if ($data['forum_cat_name'] != $current_cat) {
			$current_cat = $data['forum_cat_name'];
			echo "<tr>\n<td colspan='2' class='forum-caption forum_cat_name'><!--forum_cat_name--><a id='".str_replace(array(" ",",",".","--"), "-", strtolower($data['forum_cat_name']))."' >".$data['forum_cat_name']."</a></td>\n";
			echo "<td align='center' width='1%' class='forum-caption' style='white-space:nowrap'>".$locale['402']."</td>\n";
			echo "<td align='center' width='1%' class='forum-caption' style='white-space:nowrap'>".$locale['403']."</td>\n";
			echo "<td width='1%' class='forum-caption' style='white-space:nowrap'>".$locale['404']."</td>\n";
			echo "</tr>\n";
		}
		$moderators = "";
		if ($data['forum_moderators']) {
			$mod_groups = explode(".", $data['forum_moderators']);
			foreach ($mod_groups as $mod_group) {
				if ($moderators) $moderators .= ", ";
				$moderators .= $mod_group<101 ? "<a href='".BASEDIR."profile.php?group_id=".$mod_group."'>".getgroupname($mod_group)."</a>" : getgroupname($mod_group);
			}
		}
		$forum_match = "\|".$data['forum_lastpost']."\|".$data['forum_id'];
		if ($data['forum_lastpost'] > $lastvisited) {
			if (iMEMBER && ($data['forum_lastuser'] == $userdata['user_id'] || preg_match("({$forum_match}\.|{$forum_match}$)", $userdata['user_threads']))) {
				$fim = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
			} else {
				$fim = "<img src='".get_image("foldernew")."' alt='".$locale['560']."' />";
			}
		} else {
			$fim = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
		}
		if ($data['thread_poll']) {
			$thread_poll = "<span class='small' style='font-weight:bold'>[".$locale['global_051']."]</span>";
		} else {
			$thread_poll = "";
		}
		echo "<tr>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$fim."</td>\n";
		echo "<td class='tbl1 forum_name'><!--forum_name--><a href='viewforum.php?forum_id=".$data['forum_id']."' title='".$locale['401']."'>".$data['forum_name']."</a><br /><span class='small'><strong>".$locale['412']."</strong> ".$thread_poll." <a title=\"header=[ ".str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 70)))."] body=[".str_replace("]", "]]", str_replace("[", "[[", trimlink($post_messagefirst1, 250)))."] cssbody=[tbl1 preview_info] cssheader=[tbl2 preview_info] delay=[0] fade=[on] offsetx=[15] offsety=[5]\"";
		echo " href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."'>".trimlink($data['thread_subject'], 70)."</a> <a title=\"header=[ ".str_replace("]", "]]", str_replace("[", "[[", trimlink($data['thread_subject'], 70)))."] body=[".str_replace("]", "]]", str_replace("[", "[[", trimlink($post_message, 250)))."] cssbody=[tbl1 preview_info] cssheader=[tbl2 preview_info] delay=[0] fade=[on] offsetx=[15] offsety=[5]\" href='".FORUM."viewthread.php?forum_id=".$data['forum_id']."&amp;thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."'><img src='".IMAGES."forum/latest_post.png' style='vertical-align:middle;' /></a><br />\n";
		if ($data['forum_description'] || $moderators) {
			echo "<span class='small'>".nl2br(parseubb($data['forum_description'])).($data['forum_description'] && $moderators ? "<br />\n" : "");
			echo ($moderators ? "<strong>".$locale['411']."</strong>".$moderators."</span>\n" : "</span>\n")."\n";
		}
		echo "</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$data['forum_threadcount']."</td>\n";
		echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$data['forum_postcount']."</td>\n";
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>";
		if ($data['forum_lastpost'] == 0) {
			echo $locale['405']."</td>\n</tr>\n";
		} else {
			echo showdate("forumdate", $data['forum_lastpost'])."<br />\n";
			echo "<span class='small'>".$locale['406'].profile_link($data['forum_lastuser'], $data['user_name'], $data['user_status'])."</span><a href='".FORUM."viewthread.php?thread_id=".$data['thread_id']."&amp;pid=".$data['thread_lastpostid']."#post_".$data['thread_lastpostid']."' title='".$locale['404']."'> <img src='".IMAGES."forum/latest_post.png' style='vertical-align:middle;' /></a></td>\n";
			echo "</tr>\n";
		}
	}
} else {
	echo "<tr>\n<td colspan='5' class='tbl1'>".$locale['407']."</td>\n</tr>\n";
}
echo "</table><!--sub_forum_idx_table-->\n<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='forum'><br />\n";
echo "<img src='".get_image("foldernew")."' alt='".$locale['560']."' style='vertical-align:middle;' /> - ".$locale['409']."<br />\n";
echo "<img src='".get_image("folder")."' alt='".$locale['561']."' style='vertical-align:middle;' /> - ".$locale['410']."\n";
echo "</td><td align='right' valign='bottom' class='forum'>\n";
echo "<form name='searchform' method='get' action='".BASEDIR."search.php?stype=forums'>\n";
echo "<input type='hidden' name='stype' value='forums' />\n";
echo "<input type='text' name='stext' class='textbox' style='width:150px' />\n";
echo "<input type='submit' name='search' value='".$locale['550']."' class='button' />\n";
echo "</form>\n</td>\n</tr>\n</table><!--sub_forum_idx-->\n";
closetable();

require_once DESIGNS."templates/footer.php";
?>
