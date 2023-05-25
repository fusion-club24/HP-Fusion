<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: viewforum.php
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

if (!isset($_GET['forum_id']) || !isnum($_GET['forum_id'])) { redirect("index.php"); }

if (!isset($_GET['rowstart']) || !isnum($_GET['rowstart'])) { $_GET['rowstart'] = 0; }

echo "<script type='text/javascript' language='JavaScript' src='".INCLUDES."js/forum_prev.js'></script>";
add_to_head("<style type='text/css'>
.preview_info {
	width: 460px;
}
</style>");

$threads_per_page = 20;

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

$result = dbquery(
	"SELECT f.*, f2.forum_name AS forum_cat_name FROM ".DB_FORUMS." f
	LEFT JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE f.forum_id='".$_GET['forum_id']."'"
);
if (dbrows($result)) {
	$fdata = dbarray($result);
	if (!checkgroup($fdata['forum_access']) || !$fdata['forum_cat']) { redirect("index.php"); }
} else {
	redirect("index.php");
}

if ($fdata['forum_post']) {
	$can_post = checkgroup($fdata['forum_post']);
} else {
	$can_post = false;
}

//locale dependent forum buttons
if (is_array($fusion_images)) {
	if ($settings['locale'] != "English") {
		$newpath = "";
		$oldpath = explode("/", $fusion_images['newthread']);
		for ($i = 0; $i < count($oldpath) - 1; $i++) {
			$newpath .= $oldpath[$i]."/";
		}
		if (is_dir($newpath.$settings['locale'])) {
			redirect_img_dir($newpath, $newpath.$settings['locale']."/");
		}
	}
}
//locale dependent forum buttons

if (iSUPERADMIN) { define("iMOD", true); }

if (!defined("iMOD") && iMEMBER && $fdata['forum_moderators']) {
	$mod_groups = explode(".", $fdata['forum_moderators']);
	foreach ($mod_groups as $mod_group) {
		if (!defined("iMOD") && checkgroup($mod_group)) { define("iMOD", true); }
	}
}

if (!defined("iMOD")) { define("iMOD", false); }

$caption = "<a href='".FORUM."index.php#".str_replace(array(" ",",",".","--"), "-", strtolower($fdata['forum_cat_name']))."'>".$fdata['forum_cat_name']."</a> &raquo; <a href='viewforum.php?forum_id=".$fdata['forum_id']."' title='".$fdata['forum_name']."'>".$fdata['forum_name']."</a>";
add_to_title($locale['global_201'].$fdata['forum_name']);

if (isset($_POST['delete_threads']) && iMOD) {
	$thread_ids = "";
	if (isset($_POST['check_mark']) && is_array($_POST['check_mark'])) {
		foreach ($_POST['check_mark'] as $thisnum) {
			if (isnum($thisnum)) { $thread_ids .= ($thread_ids ? "," : "").$thisnum; }
		}
	}
	if ($thread_ids) {
		$result = dbquery("SELECT post_author, COUNT(post_id) as num_posts FROM ".DB_POSTS." WHERE thread_id IN (".$thread_ids.") GROUP BY post_author");
		if (dbrows($result)) {
			while ($pdata = dbarray($result)) {
				$result2 = dbquery("UPDATE ".DB_USERS." SET user_posts=user_posts-".$pdata['num_posts']." WHERE user_id='".$pdata['post_author']."'");
			}
		}
		$result = dbquery("SELECT attach_name FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id IN (".$thread_ids.")");
		if (dbrows($result)) {
			while ($data = dbarray($result)) {
				if (file_exists(FORUM."attachments/".$data['attach_name'])) {
					unlink(FORUM."attachments/".$data['attach_name']);
				}
			}
		}
		$result = dbquery_exec("DELETE FROM ".DB_POSTS." WHERE thread_id IN (".$thread_ids.") AND forum_id='".$_GET['forum_id']."'");
		$deleted_posts = $result;
		$result = dbquery_exec("DELETE FROM ".DB_THREADS." WHERE thread_id IN (".$thread_ids.") AND forum_id='".$_GET['forum_id']."'");
		$deleted_threads = $result;
		$result = dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLL_VOTERS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("DELETE FROM ".DB_FORUM_POLLS." WHERE thread_id IN (".$thread_ids.")");
		$result = dbquery("SELECT post_datestamp, post_author FROM ".DB_POSTS." WHERE forum_id='".$_GET['forum_id']."' ORDER BY post_datestamp DESC LIMIT 1");
		if (dbrows($result)) {
			$ldata = dbarray($result);
			$forum_lastpost = "forum_lastpost='".$ldata['post_datestamp']."', forum_lastuser='".$ldata['post_author']."'";
		} else {
			$forum_lastpost = "forum_lastpost='0', forum_lastuser='0'";
		}
		$result = dbquery("UPDATE ".DB_FORUMS." SET ".$forum_lastpost.", forum_postcount=forum_postcount-".$deleted_posts.", forum_threadcount=forum_threadcount-".$deleted_threads." WHERE forum_id='".$_GET['forum_id']."'");
	}
	$rows_left = dbcount("(thread_id)", DB_THREADS, "forum_id='".$_GET['forum_id']."'") - 3;
	if ($rows_left <= $_GET['rowstart'] && $_GET['rowstart'] > 0) {
		$_GET['rowstart'] = ((ceil($rows_left / $threads_per_page)-1) * $threads_per_page);
	}
	redirect(FUSION_SELF."?forum_id=".$_GET['forum_id']."&rowstart=".$_GET['rowstart']);
}

opentable($locale['450']);
echo "<!--pre_forum--><div class='tbl2 forum_breadcrumbs'><a href='index.php'>".$settings['sitename']."</a> &raquo; ".$caption."</div>\n";

$rows = dbcount("(thread_id)", DB_THREADS, "forum_id='".$_GET['forum_id']."' AND thread_hidden='0'");

$post_info = "";
if ($rows > $threads_per_page || (iMEMBER && $can_post)) {
	$post_info .= "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n";
	if ($rows > $threads_per_page) {
		$post_info .= "<td style='padding:4px 0px 4px 0px'>";
		$post_info .= makepagenav($_GET['rowstart'],$threads_per_page,$rows,3,FUSION_SELF."?forum_id=".$_GET['forum_id']."&amp;");
		$post_info .= "</td>\n";
	}
	if (iMEMBER && $can_post) {
		$post_info .= "<td align='right' style='padding:4px 0px 4px 0px'>";
		$post_info .= "<a href='post.php?action=newthread&amp;forum_id=".$_GET['forum_id']."'>";
		$post_info .= "<img src='".get_image("newthread")."' alt='".$locale['566']."' title='".$locale['566']." 'style='border:0px' /></a></td>\n";
	}
	$post_info .= "</tr>\n</table>\n";
}

echo $post_info;

if (iMOD) { echo "<form name='mod_form' method='post' action='".FUSION_SELF."?forum_id=".$_GET['forum_id']."&amp;rowstart=".$_GET['rowstart']."'>\n"; }
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border forum_table'>\n<tr>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap'>&nbsp;</td>\n";
echo "<td class='tbl2 forum-caption'>".$locale['451']."</td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap'>".$locale['452']."</td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap' align='center' ><img src='".IMAGES."forum/views.png' alt='".$locale['453']."'  title='".$locale['453']."' style='vertical-align:middle;' /></td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap' align='center'><img src='".IMAGES."forum/reads.png' alt='".$locale['454']."' title='".$locale['454']."' style='vertical-align:middle;' /></td>\n";
echo "<td class='tbl2 forum-caption' width='1%' style='white-space:nowrap'>".$locale['404']."</td>\n</tr>\n";

if ($rows) {
	$result = dbquery(
		"SELECT t.*, tu1.user_name AS user_author, tu1.user_status AS status_author,
		tu2.user_name AS user_lastuser, tu2.user_status AS status_lastuser
		FROM ".DB_THREADS." t
		LEFT JOIN ".DB_USERS." tu1 ON t.thread_author = tu1.user_id
		LEFT JOIN ".DB_USERS." tu2 ON t.thread_lastuser = tu2.user_id
		WHERE t.forum_id='".$_GET['forum_id']."' AND thread_hidden='0'
		ORDER BY thread_sticky DESC, thread_lastpost DESC LIMIT ".$_GET['rowstart'].",$threads_per_page"
	);
	$numrows = dbrows($result);
	while ($tdata = dbarray($result)) {
	//
	$originalpost = dbarray(dbquery("SELECT * FROM ".DB_POSTS." WHERE thread_id='".$tdata['thread_id']."' ORDER BY post_id ASC limit 1"));
    $post_messagefirst1 = $originalpost['post_smileys'] == 1 ? parsesmileys($originalpost['post_message']) : $originalpost['post_message'];
    $post_messagefirst1 = phpentities(nl2br(parseubb($post_messagefirst1)));
	$originalpost = dbarray(dbquery("SELECT * FROM ".DB_POSTS." WHERE post_id='".$tdata['thread_lastpostid']."' ORDER BY post_id ASC limit 1"));
	$post_message = $originalpost['post_smileys'] == 1 ? parsesmileys($originalpost['post_message']) : $originalpost['post_message'];
    $post_message = phpentities(nl2br(parseubb($post_message)));
	//
		$thread_match = $tdata['thread_id']."\|".$tdata['thread_lastpost']."\|".$fdata['forum_id'];
		echo "<tr>\n";
		if ($tdata['thread_locked']) {
			echo "<td align='center' width='25' class='tbl2'><img src='".get_image("folderlock")."' alt='".$locale['564']."' /></td>";
		} else  {
			if ($tdata['thread_lastpost'] > $lastvisited) {
				if (iMEMBER && ($tdata['thread_lastuser'] == $userdata['user_id'] || preg_match("(^\.{$thread_match}$|\.{$thread_match}\.|\.{$thread_match}$)", $userdata['user_threads']))) {
					$folder = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
				} else {
					$folder = "<img src='".get_image("foldernew")."' alt='".$locale['560']."' />";
				}
			} else {
				$folder = "<img src='".get_image("folder")."' alt='".$locale['561']."' />";
			}
			echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>$folder</td>";
		}
		if ($tdata['thread_poll']) {
			$thread_poll = "<span class='small' style='font-weight:bold'>[".$locale['global_051']."]</span>";
		} else {
			$thread_poll = "";
		}
		$reps = ceil($tdata['thread_postcount'] / $threads_per_page);
		$threadsubject = "".$thread_poll." <a title=\"header=[ ".str_replace("]", "]]", str_replace("[", "[[", trimlink($tdata['thread_subject'], 70)))."] body=[".str_replace("]", "]]", str_replace("[", "[[", trimlink($post_messagefirst1, 250)))."] cssbody=[tbl1 preview_info] cssheader=[tbl2 preview_info] delay=[0] fade=[on] offsetx=[15] offsety=[5]\" href='viewthread.php?thread_id=".$tdata['thread_id']."'>".$tdata['thread_subject']."</a> <a title=\"header=[ ".str_replace("]", "]]", str_replace("[", "[[", trimlink($tdata['thread_subject'], 70)))."] body=[".str_replace("]", "]]", str_replace("[", "[[", trimlink($post_message, 250)))."] cssbody=[tbl1 preview_info] cssheader=[tbl2 preview_info] delay=[0] fade=[on]\" href='".FORUM."viewthread.php?forum_id=".$tdata['forum_id']."&amp;thread_id=".$tdata['thread_id']."&amp;pid=".$tdata['thread_lastpostid']."#post_".$tdata['thread_lastpostid']."'><img src='".IMAGES."forum/latest_post.png' style='vertical-align:middle;' /></a>";
		if ($reps > 1) {
			$ctr = 0; $ctr2 = 1; $pages = ""; $middle = false;
			while ($ctr2 <= $reps) {
				if ($reps < 5 || ($reps > 4 && ($ctr2 == 1 || $ctr2 > ($reps-3)))) {
					$pnum = "<a href='viewthread.php?thread_id=".$tdata['thread_id']."&amp;rowstart=$ctr'>$ctr2</a> ";
				} else {
					if ($middle == false) {
						$middle = true; $pnum = "... ";
					} else {
						$pnum = "";
					}
				}
				$pages .= $pnum; $ctr = $ctr + $threads_per_page; $ctr2++;
			}
			$threadsubject .= "<br />(".$locale['455'].trim($pages).")";
		}
		echo "<td width='100%' class='tbl1'>";
		if (iMOD) { echo "<input type='checkbox' name='check_mark[]' value='".$tdata['thread_id']."' />\n"; }
		if ($tdata['thread_sticky'] == 1) {
			echo "<img src='".get_image("stickythread")."' alt='".$locale['474']."' style='vertical-align:middle;' />\n";
		}
		echo $threadsubject."</td>\n";
		echo "<td width='1%' class='tbl2' style='white-space:nowrap'>".profile_link($tdata['thread_author'], $tdata['user_author'], $tdata['status_author'])."</td>\n";
		echo "<td align='center' width='1%' class='tbl1' style='white-space:nowrap'>".$tdata['thread_views']."</td>\n";
		echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".($tdata['thread_postcount']-1)."</td>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".showdate("forumdate", $tdata['thread_lastpost'])."<br />\n";
		echo "<span class='small'>".$locale['406'].profile_link($tdata['thread_lastuser'], $tdata['user_lastuser'], $tdata['status_lastuser'])."</span> <a href='".FORUM."viewthread.php?thread_id=".$tdata['thread_id']."&amp;pid=".$tdata['thread_lastpostid']."#post_".$tdata['thread_lastpostid']."' title='".$locale['404']."'> <img src='".IMAGES."forum/latest_post.png' style='vertical-align:middle;' /></a></td>\n";
		echo "</tr>\n";
	}
	echo "</table><!--sub_forum_table-->\n";
} else {
	if (!$rows) {
		echo "<tr>\n<td colspan='6' class='tbl1' style='text-align:center'>".$locale['456']."</td>\n</tr>\n</table><!--sub_forum_table-->\n";
	} else {
		echo "</table><!--sub_forum_table-->\n";
	}
}

if (iMOD) {
	if ($rows) {
		echo "<table cellspacing='0' cellpadding='0' width='100%'>\n<tr>\n<td style='padding-top:5px'>";
		echo "<a href='#' onclick=\"javascript:setChecked('mod_form','check_mark[]',1);return false;\">".$locale['460']."</a> ::\n";
		echo "<a href='#' onclick=\"javascript:setChecked('mod_form','check_mark[]',0);return false;\">".$locale['461']."</a></td>\n";
		echo "<td align='right' style='padding-top:5px'><input type='submit' name='delete_threads' value='".$locale['462']."' class='button' onclick=\"return confirm('".$locale['463']."');\" /></td>\n";
		echo "</tr>\n</table>\n";
	}
	echo "</form>\n";
	if ($rows) {
		echo "<script type='text/javascript'>\n";
		echo "/* <![CDATA[ */\n";
		echo "function setChecked(frmName,chkName,val) {\n";
		echo "dml=document.forms[frmName];\n"."len=dml.elements.length;\n"."for(i=0;i < len;i++) {\n";
		echo "if(dml.elements[i].name == chkName) {\n"."dml.elements[i].checked = val;\n}\n}\n}\n";
		echo "/* ]]>*/\n";
		echo "</script>\n";
	}
}

echo $post_info;

$forum_list = ""; $current_cat = "";
$result = dbquery(
	"SELECT f.forum_id, f.forum_name, f2.forum_name AS forum_cat_name
	FROM ".DB_FORUMS." f
	INNER JOIN ".DB_FORUMS." f2 ON f.forum_cat=f2.forum_id
	WHERE ".groupaccess('f.forum_access')." AND f.forum_cat!='0' ORDER BY f2.forum_order ASC, f.forum_order ASC"
);
while ($data2 = dbarray($result)) {
	if ($data2['forum_cat_name'] != $current_cat) {
		if ($current_cat != "") { $forum_list .= "</optgroup>\n"; }
		$current_cat = $data2['forum_cat_name'];
		$forum_list .= "<optgroup label='".$data2['forum_cat_name']."'>\n";
	}
	$sel = ($data2['forum_id'] == $fdata['forum_id'] ? " selected='selected'" : "");
	$forum_list .= "<option value='".$data2['forum_id']."'$sel>".$data2['forum_name']."</option>\n";
}
$forum_list .= "</optgroup>\n";
echo "<div style='padding-top:5px'>\n".$locale['540']."<br />\n";
echo "<select name='jump_id' class='textbox' onchange=\"jumpforum(this.options[this.selectedIndex].value);\">";
echo $forum_list."</select>\n</div>\n";

echo "<div><hr />\n";
echo "<img src='".get_image("foldernew")."' alt='".$locale['560']."' style='vertical-align:middle;' /> - ".$locale['470']."<br />\n";
echo "<img src='".get_image("folder")."' alt='".$locale['561']."' style='vertical-align:middle;' /> - ".$locale['472']."<br />\n";
echo "<img src='".get_image("folderlock")."' alt='".$locale['564']."' style='vertical-align:middle;' /> - ".$locale['473']."<br />\n";
echo "<img src='".get_image("stickythread")."' alt='".$locale['563']."' style='vertical-align:middle;' /> - ".$locale['474']."\n";
echo "</div><!--sub_forum-->\n";
closetable();

echo "<script type='text/javascript'>\n"."function jumpforum(forumid) {\n";
echo "document.location.href='".FORUM."viewforum.php?forum_id='+forumid;\n}\n";
echo "</script>\n";

list($threadcount, $postcount) = dbarraynum(dbquery("SELECT COUNT(thread_id), SUM(thread_postcount) FROM ".DB_THREADS." WHERE forum_id='".$_GET['forum_id']."' AND thread_hidden='0'"));
if(isnum($threadcount) && isnum($postcount)){
	dbquery("UPDATE ".DB_FORUMS." SET forum_postcount='$postcount', forum_threadcount='$threadcount' WHERE forum_id='".$_GET['forum_id']."'");
}

require_once DESIGNS."templates/footer.php";
?>