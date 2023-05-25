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

if (!iADMIN || $userdata['user_rights'] == "" || !defined("iAUTH") || !isset($_GET['aid']) || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

require_once DESIGNS."templates/admin_header.php";

if (!isset($_GET['pagenum']) || !isnum($_GET['pagenum'])) $_GET['pagenum'] = 1;

$admin_images = true;

// Work out which tab is the active default (redirect if no tab available)
$default = false;
for ($i = 5; $i > 0; $i--) {
	if ($pages[$i]) { $default = $i; }
}
if (!$default) { redirect("../index.php"); }

// Ensure the admin is allowed to access the selected page
if (!$pages[$_GET['pagenum']]) { redirect("index.php".$aidlink."&pagenum=$default"); }

// Check HP-Fusion version
if (in_array('curl', get_loaded_extensions())) { //curl check
	#$url = "https://harlekin-power.de/versioncontrol/hp-fusion.txt";
	$url = "https://fusion-club24.de/version/hp-fusion.txt";
	function remote_file_exists($url) { //File check
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if( $httpCode == 200 ){return true;}
	}

	if (remote_file_exists($url)) { //Content check
		$ch=curl_init();
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		$content = curl_exec($ch);
		curl_close($ch);
		if (empty($content)){
			$hpf_version = "";
		} else {
			$hpf_version = $content;
		}
	} else {
		$hpf_version = "";
	}
} else {
	$hpf_version = "";
}

if ($hpf_version == "") {
	$ver_control = "<span style='vertical-align: middle;'><a href='https://harlekin-power.de' target='_blank'><img src='".IMAGES."version3.gif' alt='failed'></a></span>";
} elseif ($hpf_version == $settings['version']) {
	$ver_control = "<span style='vertical-align: middle;'><a href='https://harlekin-power.de' target='_blank'><img src='".IMAGES."version1.gif' alt='UpToDate'></a></span>";
} else {
	$ver_control = "<span style='vertical-align: middle;'><a href='https://harlekin-power.de' target='_blank'><img src='".IMAGES."version2.gif' alt='Old'></a></span>";
}

// Display admin panels & pages
opentable($locale['200']." - ".$settings['version']." ".$ver_control);
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
for ($i = 1; $i < 6; $i++) {
	$class = ($_GET['pagenum'] == $i ? "tbl1" : "tbl2");
	if ($pages[$i]) {
		echo "<td align='center' width='20%' class='$class'><span class='small'>\n";
		echo ($_GET['pagenum'] == $i ? "<strong>".$locale['ac0'.$i]."</strong>" : "<a href='index.php".$aidlink."&amp;pagenum=$i'>".$locale['ac0'.$i]."</a>")."</span></td>\n";
	} else {
		echo "<td align='center' width='20%' class='$class'><span class='small' style='text-decoration:line-through'>\n";
		echo $locale['ac0'.$i]."</span></td>\n";
	}
}
echo "</tr>\n<tr>\n<td colspan='5' class='tbl'>\n";
$result = dbquery("SELECT * FROM ".DB_ADMIN." WHERE admin_page='".$_GET['pagenum']."' ORDER BY admin_title");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4;
	$align = $admin_images ? "center" : "left";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if (checkrights($data['admin_rights']) && $data['admin_link'] != "reserved") {
			if ($counter != 0 && ($counter % $columns == 0)) { echo "</tr>\n<tr>\n"; }
			echo "<td align='$align' width='20%' class='tbl'>";
			if ($admin_images) {
				echo "<span class='small'><a href='".$data['admin_link'].$aidlink."'><img src='".get_image("ac_".$data['admin_title'])."' alt='".$data['admin_title']."' style='border:0px;' /></a><br />\n".$data['admin_title']."</span>";
			} else {
				echo "<span class='small'>".THEME_BULLET." <a href='".$data['admin_link'].$aidlink."'>".$data['admin_title']."</a></span>";
			}
			echo "</td>\n";
			$counter++;
		}
	}
	echo "</tr>\n</table>\n";
}
echo "</td>\n</tr>\n</table>\n";
closetable();

$members_registered = dbcount("(user_id)", DB_USERS, "user_status<='1' OR user_status='3' OR user_status='5'");
$members_unactivated = dbcount("(user_id)", DB_USERS, "user_status='2'");
$members_ban = dbcount("(user_id)", DB_USERS, "user_status='1'");
$members_temp_ban = dbcount("(user_id)", DB_USERS, "user_status='3'");
$members_security_ban = dbcount("(user_id)", DB_USERS, "user_status='4'");
$members_canceled = dbcount("(user_id)", DB_USERS, "user_status='5'");

opentable($locale['250']);
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n<td valign='top' width='33%' class='small'>\n";
	if (checkrights("M")) {
		echo "<a href='".ADMIN."members.php".$aidlink."'>".$locale['251']."</a> ".$members_registered."<br />\n";
		echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=2'>".$locale['252']."</a> ".$members_unactivated."<br />\n";
		echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=1'>".$locale['253a']."</a> ".$members_ban."<br />\n";
		echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=3'>".$locale['253b']."</a> ".$members_temp_ban."<br />\n";
		echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=4'>".$locale['253']."</a> ".$members_security_ban."<br />\n";
		echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=5'>".$locale['263']."</a> ".$members_canceled."<br />\n";
		if ($settings['enable_deactivation'] == "1") {
			$time_overdue = time() - (86400 * $settings['deactivation_period']);
			$members_inactive = dbcount("(user_id)", DB_USERS, "user_lastvisit<'".$time_overdue."' AND user_actiontime='0' AND user_joined<'".$time_overdue."' AND user_status='0'  AND user_level<'103'");
			echo "<a href='".ADMIN."members.php".$aidlink."&amp;status=8'>".$locale['264']."</a> ".$members_inactive."<br />\n";
		}
	} else {
		echo $locale['251']." ".$members_registered."<br />\n";
		echo $locale['252']." ".$members_unactivated."<br />\n";
		echo $locale['253a']." ".$members_ban."<br />\n";
		echo $locale['253b']." ".$members_temp_ban."<br />\n";
		echo $locale['253']." ".$members_security_ban."<br />\n";
		echo $locale['263']." ".$members_canceled."<br />\n";
	}
	echo "</td>\n<td valign='top' width='33%' class='small'>
	".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#news_submissions'>".$locale['254']."</a>" : $locale['254'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='n'")."<br />
	".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#article_submissions'>".$locale['255']."</a>" : $locale['255'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='a'")."<br />
	".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#link_submissions'>".$locale['256']."</a>" : $locale['256'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='l'")."<br />
	".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#photo_submissions'>".$locale['260']."</a>" : $locale['260'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='p'")."<br />
	".(checkrights("SU") ? "<a href='".ADMIN."submissions.php".$aidlink."#download_submissions'>".$locale['265']."</a>" : $locale['265'])." ".dbcount("(submit_id)", DB_SUBMISSIONS, "submit_type='d'")."
	</td>\n<td valign='top' width='33%' class='small'>
	<a href='".BASEDIR."comments_show.php'>".$locale['257']."</a> ".dbcount("(comment_id)", DB_COMMENTS)."<br />
	".$locale['259']." ".dbcount("(post_id)", DB_POSTS)."<br />
	".$locale['261']." ".dbcount("(photo_id)", DB_PHOTOS)."
	</td>\n</tr>\n</table>\n";
	echo "<hr>\n";

	//Memberstats part 2
	$z=0;
	$z1=0;
	$result = dbquery("SELECT user_lastvisit FROM ".DB_USERS);
	while ($data = dbarray($result)) {
		if (time() - $data['user_lastvisit'] < '86400') $z++;
		if (time() - $data['user_lastvisit'] < '604800') $z1++;
	}
	echo "<div align='center'><b>".$locale['268']."</b>".$z."";
	echo "<br /><b>".$locale['269']."</b>".$z1."</div>";

	//1. Date
	$u1_data = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='1'"));
	$month1 = date('m', $u1_data['user_joined']);
	$year1 = date('Y', $u1_data['user_joined']);
	$year = intval( isset($_POST['year']) ? $_POST['year'] : date("Y",time()) );
	$y = 0;
	$options="";
	$options .= "<option value='0' ".($y==$year ? "selected":"").">".$locale['270']."</option>";
	for ($y = $year1; $y <= date("Y",time()); $y++) {
		$options .= "<option value='".$y."' ".($y==$year ? "selected":"").">".$y."</option>";
	}

	echo "<br /><div align='center'><form method='POST' action='".FUSION_SELF.$aidlink."'>&nbsp;&nbsp;
		<select name='year' class='textbox'>".$options."</select>
		<input type='submit' name='send' value='".$locale['271']."' class='button'/>
	</form></div>";
	echo "<br>
	<table align='center' width='100%' cellpadding='3' cellspacing='1' class='tbl-border'>
		<tr class='tbl2 forum-caption'>
			<td width='33%' align='center'><b>".$locale['272']."</b></td>
			<td width='33%' align='center'><b>".$locale['273']."</b></td>
			<td width='33%' align='center'><b>".$locale['274']."</b></td>
		</tr>";

		$display_t = 0;

		if ( $year==0 ) {
			for ($i=0; ;$i++) {
				if( date("U", mktime (0, 0, 0, ($month1+$i), 1, $year1)) > date("U") ) break;
				$sql = "SELECT * FROM ".DB_USERS." WHERE user_joined >= '".date("U", mktime (0, 0, 0, ($month1+$i), 1, $year1))."' AND user_joined < '".date("U", mktime (0, 0, 0, ($month1+($i+1)), 1, $year1))."'";
				$res = dbquery($sql);
				$display = dbrows($res);
				$display_t = $display_t+$display;
				echo "<tr ".($i%2 ? "class='tbl1'":"class='tbl2'").">
					<td align='center'>".date("m / Y", mktime (0, 0, 0, ($month1+$i), 1, $year1))."</td>
					<td align='center'>".$display." ".($display==1 ? $locale['275'] : $locale['276'] )."</td>
					<td align='center'>".$display_t." ".($display_t==1 ? $locale['275'] : $locale['276'] )."</td>
				</tr>";
			}
		} else {
			$sql = "SELECT MIN(user_joined) min_uj, MAX(user_joined) max_uj FROM ".DB_USERS." WHERE FROM_UNIXTIME(user_joined,'%Y')=".$year." ";
			$jdata = dbarray(dbquery($sql));
			$m1 = date("m", $jdata['min_uj']);
			$m2 = date('m', $jdata['max_uj']);

			for ($i=$m1; $i<=$m2 ;$i++) {
				$sql = "SELECT * FROM ".DB_USERS." WHERE user_joined >= '".date("U", mktime (0, 0, 0, $i, 1, $year))."' AND user_joined <= '".date("U", mktime (0, 0, 0, ($i+1), 1, $year))."'";
				$res = dbquery($sql);
				$display = dbrows($res);
				$display_t = $display_t+$display;
				echo "<tr ".($i%2 ? "class='tbl1'":"class='tbl2'").">
					<td align='center'>".date("m / Y", mktime (0, 0, 0, ($i), 1, $year))."</td>
					<td align='center'>".$display." ".($display==1 ? $locale['275'] : $locale['276'] )."</td>
					<td align='center'>".$display_t." ".($display_t==1 ? $locale['275'] : $locale['276'] )."</td>
				</tr>";
			}
		}
	echo "</table>";
closetable();

require_once DESIGNS."templates/footer.php";
?>
