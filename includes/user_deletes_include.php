<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: user_deletes_include.php
| Author: Rolly8-HL
| Co-Author: Harlekin
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
include LOCALE.LOCALESET."admin/userdellog.php";
//Admin PN
$result = dbquery("INSERT INTO ".DB_MESSAGES." VALUES('', '1,', '1', '".$locale['300']." ".$data['user_name']." ".$locale['301']."', '".$locale['302']." ".$data['user_name']." ".$locale['303']."', '0', '0', '".time()."', '0')");
//Userdelete Log
$result = dbquery("INSERT INTO ".DB_USER_DELLOG." (userdellog_id, userdellog_user_id, userdellog_user_name, userdellog_user_email, userdellog_user_ip, userdellog_timestamp) VALUES ('', '".$data['user_id']."', '".$data['user_name']."', '".$data['user_email']."', '".$data['user_ip']."', '".time()."')");
//Blacklist
if (!iADMIN) {
	$result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_ip, blacklist_ip_type, blacklist_user_id, blacklist_email, blacklist_username, blacklist_reason, blacklist_datestamp) VALUES ('', '', '1', '',  '".$data['user_name']."', '".$locale['310']."', '".time()."')");
	$result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_ip, blacklist_ip_type, blacklist_user_id, blacklist_email, blacklist_username, blacklist_reason, blacklist_datestamp) VALUES ('', '', '1', '".$data['user_email']."',  '', '".$locale['311']."', '".time()."')");
} else {
	$result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_ip, blacklist_ip_type, blacklist_user_id, blacklist_email, blacklist_username, blacklist_reason, blacklist_datestamp) VALUES ('', '', '".$userdata['user_id']."', '',  '".$data['user_name']."', '".$locale['310']."', '".time()."')");
	$result = dbquery("INSERT INTO ".DB_BLACKLIST." (blacklist_ip, blacklist_ip_type, blacklist_user_id, blacklist_email, blacklist_username, blacklist_reason, blacklist_datestamp) VALUES ('', '', '".$userdata['user_id']."', '".$data['user_email']."',  '', '".$locale['311']."', '".time()."')");
}

// DELETE CONTENT ALWAYS
if ($data['user_avatar'] != "" && file_exists(IMAGES."avatars/".$data['user_avatar'])) {
	@unlink(IMAGES."avatars/".$data['user_avatar']);
}

$result2 = dbquery("SELECT photo_id, album_id, photo_filename, photo_thumb1, photo_thumb2  FROM ".DB_PHOTOS." WHERE photo_user='".$user_id."'");
if (dbrows($result2)) {
	while ($data2 = dbarray($result2)) {
		dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_item_id='".$data2['photo_id']."' and comment_type='P'");
		dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_item_id='".$data2['photo_id']."' and rating_type='P'");
		dbquery("DELETE FROM ".DB_PHOTOS." WHERE photo_user='".$user_id."'");
		$photoDir = PHOTOS."album_".$data2['album_id']."/";
		@unlink($photoDir.$data2['photo_filename']);
		@unlink($photoDir.$data2['photo_thumb1']);
		if ($data2['photo_thumb2']) { @unlink($photoDir.$data2['photo_thumb2']); }
	}
}

$result_d = dbquery("SELECT download_id, download_user, download_file, download_image, download_image_thumb  FROM ".DB_DOWNLOADS." WHERE download_user='".$user_id."'");
if (dbrows($result_d)) {
	while ($data_d = dbarray($result_d)) {
		dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_item_id='".$data_d['download_id']."' and comment_type='D'");
		dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_item_id='".$data_d['download_id']."' and rating_type='D'");
		dbquery("DELETE FROM ".DB_DOWNLOADS." WHERE download_user='".$user_id."'");
		if (!empty($data_d['download_file']) && file_exists(DOWNLOADS.$data_d['download_file'])) {
			@unlink(DOWNLOADS.$data_d['download_file']);
		}
		if (!empty($data_d['download_image']) && file_exists(DOWNLOADS."images/".$data_d['download_image'])) {
			@unlink(DOWNLOADS."images/".$data_d['download_image']);
		}
		if (!empty($data_d['download_image_thumb']) && file_exists(DOWNLOADS."images/".$data_d['download_image_thumb'])) {
			@unlink(DOWNLOADS."images/".$data_d['download_image_thumb']);
		}
	}
}

dbquery("DELETE FROM ".DB_USERS." WHERE user_id='".$user_id."'");
dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_to='".$user_id."' OR message_from='".$user_id."'"); // Delete Messages
dbquery("DELETE FROM ".DB_MESSAGES_OPTIONS." WHERE user_id='".$user_id."'"); // Delete messages options
dbquery("UPDATE ".DB_POLL_VOTES." SET vote_user='2' WHERE vote_user='".$user_id."'"); // Anomyze pollvotes
dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_user='".$user_id."'");
dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE notify_user='".$user_id."'"); // Delete thread noification
dbquery("DELETE FROM ".DB_SUSPENDS." WHERE suspended_user='".$user_id."'"); // Delete suspend user
dbquery("DELETE FROM ".DB_USERS_BLOCKS." WHERE user_id='".$user_id."'"); // Delete user has blocked
dbquery("DELETE FROM ".DB_USERS_BLOCKS." WHERE blocked_user_id='".$user_id."'"); // Delete user that was blocked

// Delete score system
$score_install = dbquery("SELECT * FROM ".DB_INFUSIONS." WHERE  inf_folder='scoresystem_panel'");
$data_score = dbarray($score_install);
if (isset($data_score['inf_folder']) && $data_score['inf_folder'] == 'scoresystem_panel'){
	dbquery("DELETE FROM ".DB_PREFIX."score_account WHERE acc_user_id='".$user_id."'"); // Delete score_account user
	dbquery("DELETE FROM ".DB_PREFIX."score_ban WHERE ban_user_id='".$user_id."'"); // Delete score_ban user
	dbquery("DELETE FROM ".DB_PREFIX."score_transfer WHERE tra_user_id='".$user_id."'"); // Delete score_transfer user
}

$result_p = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_user='".$user_id."' AND submit_type='p'");
while ($data_p = dbarray($result_p)) {			
	$submit_criteria = unserialize($data_p['submit_criteria']);
	@unlink(PHOTOS."submissions/".$submit_criteria['photo_file']);
}

$result_d = dbquery("SELECT * FROM ".DB_SUBMISSIONS." WHERE submit_user='".$user_id."' AND submit_type='d' ");
while ($data_d = dbarray($result_d)) {
	$submit_criteria = unserialize($data_d['submit_criteria']);
	if ($submit_criteria['download_file']) @unlink(DOWNLOADS."submissions/".$submit_criteria['download_file']);
	if (is_file(DOWNLOADS."submissions/images/".$submit_criteria['download_image'])) {
		@unlink(DOWNLOADS."submissions/images/".$submit_criteria['download_image']);
	}
	if (is_file(DOWNLOADS."submissions/images/".$submit_criteria['download_image_thumb'])) {
		@unlink(DOWNLOADS."submissions/images/".$submit_criteria['download_image_thumb']);
	}
}
dbquery("DELETE FROM ".DB_SUBMISSIONS." WHERE submit_user='".$user_id."'");

$HP_quasselbox = dbarray(dbquery("SELECT * FROM ".DB_INFUSIONS." WHERE inf_folder='HP_quasselbox_panel'"));
if (isset($HP_quasselbox['inf_folder']) && $HP_quasselbox['inf_folder'] == 'HP_quasselbox_panel'){
	dbquery("DELETE FROM ".DB_PREFIX."hp_quasselbox WHERE quassel_name='".$user_id."'"); // HP Quasselbox
	dbquery("DELETE FROM ".DB_PREFIX."hp_quasselbox_likes WHERE user_id='".$user_id."'"); // HP Quasselbox voten
}

//DELETE CONTENT IF SETTINGS USER DELETE = DELETE
if ($del_user_action == '1') {
	$result_a = dbquery("SELECT * FROM ".DB_ARTICLES." WHERE article_name='".$user_id."'");
	if (dbrows($result_a)) {
		while ($data_a = dbarray($result_a)) {
			dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_item_id='".$data_a['article_id']."' and comment_type='A'");
			dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_item_id='".$data_a['article_id']."' and rating_type='A'");
		}
	}

	$result_n = dbquery("SELECT * FROM ".DB_NEWS." WHERE news_name='".$user_id."'");
	if (dbrows($result_n)) {
		while ($data_n = dbarray($result_n)) {
			dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_item_id='".$data_n['news_id']."' and comment_type='N'");
			dbquery("DELETE FROM ".DB_RATINGS." WHERE rating_item_id='".$data_n['news_id']."' and rating_type='N'");
		}
	}
	
	dbquery("DELETE FROM ".DB_ARTICLES." WHERE article_name='".$user_id."'"); // Delete articles
	dbquery("DELETE FROM ".DB_NEWS." WHERE news_name='".$user_id."'"); // Delete News
	dbquery("DELETE FROM ".DB_COMMENTS." WHERE comment_name='".$user_id."'"); // Delete comments

	$_ALWAYS_ATTACHMENTS = dbquery("SELECT ta.*, tac.* FROM ".DB_THREADS." ta
		LEFT JOIN ".DB_FORUM_ATTACHMENTS." tac ON ta.thread_id=tac.thread_id
		WHERE  thread_author='".$user_id."'");
		while ($data_fa = dbarray($_ALWAYS_ATTACHMENTS)) {
			if (!empty($data_fa['attach_name']) && file_exists(FORUM."attachments/".$data_fa['attach_name'])) {
				@unlink(FORUM."attachments/".$data_fa['attach_name']);
			}
		}
		
	$_ALWAYS_ATTACHMENTS2 = dbquery("SELECT ta.*, tac.* FROM ".DB_POSTS." ta
		LEFT JOIN ".DB_FORUM_ATTACHMENTS." tac ON ta.post_id=tac.post_id
		WHERE  post_author='".$user_id."'");
		while ($data_fa2 = dbarray($_ALWAYS_ATTACHMENTS2)) {
			if (!empty($data_fa2['attach_name']) && file_exists(FORUM."attachments/".$data_fa2['attach_name'])) {
				@unlink(FORUM."attachments/".$data_fa2['attach_name']);
			dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS."  WHERE post_id='".$data_fa2['post_id']."'");
			}
		}
	
	$_ALWAYS_DELETE = dbquery("SELECT * FROM ".DB_THREADS." WHERE thread_author='".$user_id."'");
	while ($data_A_D = dbarray($_ALWAYS_DELETE)) {
		dbquery("DELETE FROM ".DB_FORUM_ATTACHMENTS." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete forum_attachments
		dbquery("DELETE FROM ".DB_POSTS." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete thread_id
		dbquery("DELETE FROM ".DB_THREADS." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete thread_id
		dbquery("DELETE FROM ".DB_THREAD_NOTIFY." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete thread_id
		dbquery("DELETE FROM ".DB_FORUM_POLLS." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete thread_id
		dbquery("DELETE FROM ".DB_FORUM_POLL_OPTIONS." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete thread_id
		dbquery("DELETE FROM ".DB_FORUM_POLL_VOTERS." WHERE thread_id='".$data_A_D['thread_id']."'"); // Delete thread_id
	}

	dbquery("DELETE FROM ".DB_FORUM_POLL_VOTERS." WHERE forum_vote_user_id='".$user_id."'"); // Delete thread_id
	dbquery("DELETE FROM ".DB_POSTS." WHERE post_author='".$user_id."'");  // Delete post author

	$result_posts = dbquery("SELECT * FROM ".DB_POSTS);
	while ($data_posts = dbarray($result_posts)) {
		$posts_count = dbcount("(post_id)", DB_POSTS, "  forum_id='".$data_posts['forum_id']."'");
		$count_thread = dbcount("(post_id)", DB_POSTS, "thread_id='".$data_posts['thread_id']."'");
		$thread_count = dbcount("(thread_id)", DB_THREADS, "forum_id='".$data_posts['forum_id']."'");

		dbquery("UPDATE ".DB_FORUMS." SET  
		forum_lastpost='".$data_posts['post_datestamp']."'
		, forum_postcount='".$posts_count."'
		, forum_threadcount='".$thread_count."'
		, forum_lastuser='".$data_posts['post_author']."'
		WHERE forum_cat!='0' AND forum_id='".$data_posts['forum_id']."'");

		dbquery("UPDATE ".DB_THREADS." SET  
		thread_lastpost='".$data_posts['post_datestamp']."'
		, thread_postcount='".$count_thread."'
		, thread_lastpostid='".$data_posts['post_id']."'
		, thread_lastuser='".$data_posts['post_author']."'
		WHERE thread_id='".$data_posts['thread_id']."'");
	}

	$result_forum = dbquery("SELECT * FROM ".DB_FORUMS." WHERE forum_cat!='0'");
	while ($data_forum = dbarray($result_forum)) {
		$thread_count = dbcount("(thread_id)", DB_THREADS, "forum_id='".$data_forum['forum_id']."'");
		if ($thread_count == 0 ) {
			dbquery("UPDATE ".DB_FORUMS." SET  
			forum_lastpost='0'
			, forum_postcount='0'
			, forum_threadcount='0'
			, forum_lastuser='0'
			WHERE   forum_id='".$data_forum['forum_id']."'");
		} 	
	}

	$count_posts = dbquery("SELECT user_posts, user_id FROM ".DB_USERS);
		while ($data_posts = dbarray($count_posts)) {
			$posts_count = dbcount("(post_id)", DB_POSTS, "post_author='".$data_posts['user_id']."'");
			dbquery("UPDATE ".DB_USERS." SET user_posts='".$posts_count."' WHERE user_id='".$data_posts['user_id']."'");
		}
	redirect(FUSION_SELF);
} else {
	//ANONYMIZE CONTENT IF SETTINGS USER DELETE = ANONYMIZE
	dbquery("UPDATE ".DB_ARTICLES." SET article_name = '2' WHERE article_name='".$user_id."'"); // Anomyze articles
	dbquery("UPDATE ".DB_NEWS." SET news_name='2' WHERE news_name='".$user_id."'"); // Anomyze news
	dbquery("UPDATE ".DB_COMMENTS." SET comment_name = '2' WHERE comment_name='".$user_id."'"); // Anomyze comments
	dbquery("UPDATE ".DB_FORUMS." SET forum_lastuser='2' WHERE forum_lastuser='".$user_id."'"); // Anomyze forum last user
	dbquery("UPDATE ".DB_THREADS." SET thread_author='2' WHERE thread_author='".$user_id."'"); // Anomyze thread author
	dbquery("UPDATE ".DB_THREADS." SET thread_lastuser='2' WHERE thread_lastuser='".$user_id."'"); // Anomyze thread last user
	dbquery("UPDATE ".DB_POSTS." SET post_author='2' WHERE post_author='".$user_id."'"); // Anomyze post author
	dbquery("UPDATE ".DB_FORUM_POLL_VOTERS." SET forum_vote_user_id='2' WHERE forum_vote_user_id='".$user_id."'"); // Anomyze votes on forum threads

	//posts counter by ANONYMIZE
	$posts_count = dbcount("(post_id)", DB_POSTS, "post_author='2'");
	dbquery("UPDATE ".DB_USERS." SET user_posts='".$posts_count."' WHERE user_id='2'");
                    redirect(FUSION_SELF);
	//Erweiterung ANONYMIZE CONTENT by Alphabet
}
?>