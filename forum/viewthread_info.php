<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: viewthread_info.php
| Author: Harlekin
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

include LOCALE.LOCALESET."forum/viewthread_info.php";

get_image("folderlock",  DESIGN."forum/folderlock.png");
get_image("stickythread",  DESIGN."forum/stickythread.png");
get_image("folder",  DESIGN."forum/folder.png");


if (isset($_GET['thread_id']) && isnum($_GET['thread_id'])) {
	$lthread = dbarray(dbquery("SELECT tt.thread_id, tt.thread_author, tt.thread_subject, tt.thread_locked, tt.thread_sticky, tt.thread_postcount, tt.thread_views, u.user_id, u.user_name, u.user_status FROM ".DB_PREFIX."threads tt LEFT JOIN ".DB_PREFIX."users u ON tt.thread_author = u.user_id WHERE thread_id='".$_GET['thread_id']."' LIMIT 1"));
	$atid = $lthread['thread_id'];
	$f_attach = dbarray(dbquery("SELECT attach_id, attach_ext FROM ".DB_PREFIX."forum_attachments WHERE thread_id = '".$atid."' AND (attach_ext !='')"));
//}
		    
	if (!$lthread['thread_sticky'] && !$lthread['thread_locked']) {
		add_to_head("<style type='text/css'>
			.forum-info-outer{padding: 5px 0px 10px 0px;}
			.forum-info {border: 1px solid;padding: 5px 0px 5px 50px;background-repeat: no-repeat;background-position: 7px center;color: #9F6000;background-color: #FEEFB3;background-image: url(".get_image("folder").");border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;}
			.forum-info-message {color: #333;}
			.forum-info-thread-id {color: #333;float: right;text-align: right;padding: 0px 15px 15px 0px;}
		</style>");
		 
	} elseif ($lthread['thread_locked'] && !$lthread['thread_sticky']) {
		add_to_head("<style type='text/css'>
			.thread-locked-outer{padding: 5px 0px 10px 0px;}
			.thread-locked {border: 1px solid;padding: 5px 0px 5px 50px;background-repeat: no-repeat;background-position: 7px center;color: #B52626;background-color: #FFCBCB;background-image: url(".get_image("folderlock").");border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;}
			.locked-message {color: #333;}
			.locked-thread-id {color: #333;float: right;text-align: right;padding: 0px 15px 15px 0px;}
		</style>");
	
	} elseif ($lthread['thread_sticky'] && !$lthread['thread_locked']) {
		add_to_head("<style type='text/css'>
			.thread-sticky-outer{padding: 5px 0px 10px 0px;}
			.thread-sticky {border: 1px solid;padding: 5px 0px 5px 50px;background-repeat: no-repeat;background-position: 7px center;color: #00529B;background-color: #BDE5F8;background-image: url(".get_image("stickythread").");border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;}
			.sticky-message {color: #333;}
			.sticky-thread-id {color: #333;float: right;text-align: right;padding: 0px 15px 15px 0px;}
		</style>");
		
	} elseif ($lthread['thread_locked'] && $lthread['thread_sticky']) {
		add_to_head("<style type='text/css'>
			.thread-locked-sticky-outer{padding: 5px 0px 10px 0px;}
			.thread-locked-sticky {border: 1px solid;padding: 5px 0px 5px 50px;background-repeat: no-repeat;background-position: 7px center;color: #B52626;background-color: #FFCBCB;background-image: url(".get_image("folderlock").");border-radius: 5px;-moz-border-radius: 5px;-webkit-border-radius: 5px;}
			.locked-sticky-message {color: #333;}
			.locked-sticky-thread-id {color: #333;float: right;text-align: right;padding: 0px 15px 15px 0px;}
		</style>");
	}

	if (!$lthread['thread_locked'] && !$lthread['thread_sticky']) {
		$thread_info = "";		
		$thread_info .= "<div class='forum-info-outer center'>\n";
		$thread_info .= "<div class='forum-info center'>\n";
		$thread_info .= "<div class='forum-info-thread-id'>\n";
		$thread_info .= $locale['tl013'];
		$thread_info .= profile_link($lthread['user_id'], $lthread['user_name'], $lthread['user_status'])."<br />";
		$thread_info .= $locale['tl007']." <a href='".$settings['siteurl']."forum/viewthread.php?thread_id=".$lthread['thread_id']."' >".$lthread['thread_id']."</a><br />\n";
		$thread_info .= "</div>\n";
		$thread_info .= "<strong>".$locale['tl008']."</strong>\n";
		$thread_info .= "<div class='forum-info-message'>\n";
		$thread_info .=  $locale['tl009'].$lthread['thread_postcount'].$locale['tl010']."\n";
		$thread_info .= $locale['tl011'].$lthread['thread_views'].$locale['tl012'];
		if (!$f_attach['attach_ext']) {
			$thread_info .= ".\n";
		} else {
			$thread_info .= ".&nbsp;\n";
		}
	    if ($f_attach['attach_ext']) {
			$thread_info .= "<br />".$locale['tl014'];
		}
		$thread_info .= "</div>\n";
		$thread_info .= "</div>\n";
		$thread_info .= "</div>\n";

		echo $thread_info;

	} elseif ($lthread['thread_locked'] && !$lthread['thread_sticky']) {
		$locked_thread = "";		
		$locked_thread .= "<div class='thread-locked-outer center'>\n";
		$locked_thread .= "<div class='thread-locked center'>\n";
		$locked_thread .= "<div class='locked-thread-id'>\n";
		$locked_thread .= $locale['tl013'];
		$locked_thread .= profile_link($lthread['user_id'], $lthread['user_name'], $lthread['user_status'])."<br />";
		$locked_thread .= $locale['tl007']." <a href='".$settings['siteurl']."forum/viewthread.php?thread_id=".$lthread['thread_id']."' >".$lthread['thread_id']."</a><br />\n";
		$locked_thread .= "</div>\n";
		$locked_thread .= "<strong>".$locale['tl001']."</strong>\n";
		$locked_thread .= "<div class='locked-message'>\n";
		$locked_thread .= $locale['tl002']."<br />".$locale['tl009'].$lthread['thread_postcount'].$locale['tl010']."\n";
		$locked_thread .= $locale['tl011'].$lthread['thread_views'].$locale['tl012'];
		if (!$f_attach['attach_ext']) {
			$locked_thread .= ".\n";
		}
		if ($f_attach['attach_ext']) {
			$locked_thread .= "<br />".$locale['tl014'];
		}
		$locked_thread .= "\n</div>\n";
		$locked_thread .= "</div>\n";
		$locked_thread .= "</div>\n";

		echo $locked_thread;

	} elseif ($lthread['thread_sticky'] && !$lthread['thread_locked']) {
		$sticky_thread = "";		
		$sticky_thread .= "<div class='thread-sticky-outer center'>\n";
		$sticky_thread .= "<div class='thread-sticky center'>\n";
		$sticky_thread .= "<div class='sticky-thread-id'>\n";
		$sticky_thread .= $locale['tl013'];
		$sticky_thread .= profile_link($lthread['user_id'], $lthread['user_name'], $lthread['user_status'])."<br />";
		$sticky_thread .= $locale['tl007']." <a href='".$settings['siteurl']."forum/viewthread.php?thread_id=".$lthread['thread_id']."' >".$lthread['thread_id']."</a><br />\n";
		$sticky_thread .= "</div>\n";
		$sticky_thread .= "<strong>".$locale['tl003']."</strong>\n";
		$sticky_thread .= "<div class='sticky-message'>\n";
		$sticky_thread .= $locale['tl009'].$lthread['thread_postcount'].$locale['tl010']."\n";
		$sticky_thread .= $locale['tl011'].$lthread['thread_views'].$locale['tl012'];
		if (!$f_attach['attach_ext']) {
			$sticky_thread .= ".\n";
		} else {
			$sticky_thread .= ".&nbsp;\n";
		}
		if ($f_attach['attach_ext']) {
			$sticky_thread .= "<br />".$locale['tl014'];
		}
		$sticky_thread .= "\n</div>\n";
		$sticky_thread .= "</div>\n";
		$sticky_thread .= "</div>\n";

		echo $sticky_thread;

	} elseif ($lthread['thread_sticky'] && $lthread['thread_locked']) {
		$locked_sticky_thread = "";		
		$locked_sticky_thread .= "<div class='thread-locked-sticky-outer center'>\n";
		$locked_sticky_thread .= "<div class='thread-locked-sticky center'>\n";
		$locked_sticky_thread .= "<div class='locked-sticky-thread-id'>\n";
		$locked_sticky_thread .= $locale['tl013'];
		$locked_sticky_thread .= profile_link($lthread['user_id'], $lthread['user_name'], $lthread['user_status'])."<br />";
		$locked_sticky_thread .= $locale['tl007']." <a href='".$settings['siteurl']."forum/viewthread.php?thread_id=".$lthread['thread_id']."' >".$lthread['thread_id']."</a><br />\n";
		$locked_sticky_thread .= "</div>\n";
		$locked_sticky_thread .= "<strong>".$locale['tl005']."</strong>\n";
		$locked_sticky_thread .= "<div class='locked-sticky-message'>\n";
		$locked_sticky_thread .= $locale['tl002']."<br />".$locale['tl009'].$lthread['thread_postcount'].$locale['tl010']."\n";
		$locked_sticky_thread .= $locale['tl011'].$lthread['thread_views'].$locale['tl012'];
		if ($f_attach['attach_ext']) {
			$locked_sticky_thread .= ".&nbsp;\n";
		} else {
			$locked_sticky_thread .= ".\n";
		}
		if ($f_attach['attach_ext']) {
			$locked_sticky_thread .= "<br />".$locale['tl014'];
		}
		$locked_sticky_thread .= "\n</div>\n";
		$locked_sticky_thread .= "</div>\n";
		$locked_sticky_thread .= "</div>\n";

		echo $locked_sticky_thread;
		
	}
}
?>