<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Design: HP Fusion Red
| Filename: HP_mc.php
| Author: Harlekin
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once '../../../maincore.php';
define('HP_mc_DIR',DESIGN.'HP_membercard/');

if (!iMEMBER && $settings['hide_userprofiles'] == 1) {
	exit;
}

if (file_exists(HP_mc_DIR.'locale/'.$settings['locale'].'.php')) {
	include HP_mc_DIR.'locale/'.$settings['locale'].'.php';
} else {
	include HP_mc_DIR.'locale/German.php';
}

require_once HP_mc_DIR.'HP_mc_conf.php';

if ($settings['forum_ranks']){
	require_once INCLUDES.'forum_include.php';}
	
if (!function_exists('add_to_head')){
	function add_to_head($t){
		return false;
	}
}
if (!function_exists('add_to_footer')){
	function add_to_footer($t){
		return false;
	}
}

//HP Membercard
header('Content-type: text/html; charset='.$locale['charset']);

function HP_mc_fullpath($data,$str = false){
	global $settings, $locale, $user_data, $profile_method;
	if(!$str){
		return str_replace(array('"../../../','\'../../../'),array('"'.$settings['siteurl'],'\''.$settings['siteurl']),$data);
	} else {
		return str_replace('../../../', $settings['siteurl'], $data);
	}
}

if (isset($_GET['lookup']) && isnum($_GET['lookup'])) {
	$user_status = " AND (user_status='0' OR user_status='3' OR user_status='7')";
	if (iADMIN) {
		$user_status = '';
	}

	$result = dbquery("SELECT u.*, s.suspend_reason FROM ".DB_USERS." u
		LEFT JOIN ".DB_SUSPENDS." s ON u.user_id=s.suspended_user
		WHERE user_id='".$_GET['lookup']."'".$user_status."
		ORDER BY suspend_date DESC LIMIT 1");

	if (dbrows($result)) {
		$user_data = dbarray($result);
	} else {
		exit($locale['hpmc_001']);
	}

	if (empty($user_data['user_avatar']) || !file_exists(IMAGES.'avatars/'.$user_data['user_avatar'])) {
		$user_data['user_avatar'] = 'noavatar100.png';
	}

	//groups
	$html_groups = '';
	if(!empty($user_data['user_groups'])){
		$user_groups = strpos($user_data['user_groups'], '.') == 0 ? substr($user_data['user_groups'], 1) : $user_data['user_groups'];
		$user_groups = explode('.', $user_groups);
		if($user_groups && count($user_groups)>0){
			$html_groups .=  '<hr />'.$locale['hpmc_002'].' ';
			foreach ($user_groups as $key => &$user_group) {
				$user_groups[$key] = '<a href="'.HP_mc_fullpath(BASEDIR,true).'profile.php?group_id='.$user_group.'" title="'.getgroupname($user_group, true).'">'.getgroupname($user_group).'</a>';
			}
			$html_groups .=  implode(', ',$user_groups).'<hr />';	
		}
	}

	//userfields
	class isNothing {
		public $userData = '';
		public $HP_mc_fields = '';
		private $method;

		function __call($name,$args) {
			if(iSUPERADMIN) {
				echo '<!-- Called: '.$name.' -> ';
				var_dump($args);
				echo '-->';
			} else {
				echo '<!-- method -> called -->';
			}
		} 
		
		public function displayOutput() {
			global $settings,$locale,$user_data,$profile_method;
			$this->method = 'display';
			$profile_method = 'display';
			$user_data = $this->userData;
		
			if(count($this->HP_mc_fields)>0){
				ob_start();
				foreach ($this->HP_mc_fields as $field) {
					if(isset($user_data[$field])){
						if (file_exists(LOCALE.LOCALESET.'user_fields/'.$field.'.php')) {
							include LOCALE.LOCALESET.'user_fields/'.$field.'.php';
						}
						if (file_exists(INCLUDES.'user_fields/'.$field.'_include.php')) {
							include INCLUDES.'user_fields/'.$field.'_include.php';
						}
					}
				}
				$html_fields = ob_get_contents();
				ob_end_clean();	
				return $html_fields;
			} else return '';
			
		}
	}
	$something = new isNothing();
	$something->userData = $user_data;
	$something->HP_mc_fields = (isset($HP_mc_fields)?$HP_mc_fields:'');
	$html_fields = HP_mc_fullpath($something->displayOutput());

	//profile links
	$HP_mc_plinks = array();
	$HP_mc_plinks[] = '<a href="'.HP_mc_fullpath(BASEDIR,true).'profile.php?lookup='.$_GET['lookup'].'">'.$locale['hpmc_003'].'</a>';
	if ((iMEMBER && $userdata['user_id'] != $user_data['user_id']) && ($user_data['user_id'] != 2)) {
		$HP_mc_plinks[] = '<a href="'.HP_mc_fullpath(BASEDIR,true).'messages.php?msg_send='.$user_data['user_id'].'">'.$locale['hpmc_004'].'</a>';
	}
	if(iADMIN && checkrights('M') && $user_data['user_level'] != '103' && $user_data['user_id'] != '1'){
		$HP_mc_plinks[] = '<a href="'.HP_mc_fullpath(ADMIN,true).'members.php'.$aidlink.'&amp;step=log&amp;user_id='.$user_data['user_id'].'">'.$locale['hpmc_005'].'</a>';
	}

	//infoblock
	$HP_mc_pinfo = array();
	if(isset($HP_mc_infofields) && count($HP_mc_infofields)>0){
		foreach ($HP_mc_infofields as $field => &$title) {
			if(isset($user_data[$field]) && !empty($user_data[$field])){
				$HP_mc_pinfo[] = $title.'<strong>'.$user_data[$field].'</strong>';
			}
		}
	}

	//Age
	if ($user_data['user_birthdate'] != "1901-01-01") {
		$user_age = explode("-", $user_data['user_birthdate']);
		$user_birthyear = $user_age[0];
		$current_year = date("Y", time());
		$user_age_years = $current_year - $user_birthyear;
		if (mktime(0,0,0,$user_age[1], $user_age[2], $current_year) > time()) {
			$user_age_years--;
		}
		$user_age_years = "".$user_age_years." ".$locale['hpmc_006']."";
	} else {
		$user_age_years = "".$locale['hpmc_007']."";
	}
				
	#$HP_mc_pinfo[] = $locale['hpmc_008'].' <a href="'.$user_data['user_web'].'"><strong>'.$user_data['user_web'].'</a></strong>';
	$HP_mc_pinfo[] = ($locale['hpmc_009'].' <strong>'.(isset($user_data['user_lastvisit']) ? showdate("longdate", $user_data['user_lastvisit']):$locale['hpmc_010'])).'</strong>';
	$HP_mc_pinfo[] = $locale['hpmc_014'].' <strong>'.showdate("longdate",$user_data['user_joined']).'</strong>';

	echo '<table cellpadding="0" cellspacing="1" class="tbl-border center" style="margin:10px;">
		<tr>
			<td class="tbl2" style="width:5%; vertical-align:top;"><img class="HP_mc_avatar" src="'.HP_mc_fullpath(IMAGES,true).'avatars/'.$user_data['user_avatar'].'" class="avatar" alt="'.$user_data['user_name'].'" /><br /><center>'.$locale['hpmc_011'].' '.$user_age_years.'</center></td>
			<td class="tbl2" style="width:95%;">
				<center>';
				echo '<span class="HP_mc_center"><a href="'.HP_mc_fullpath(BASEDIR,true).'profile.php?lookup='.$_GET['lookup'].'">'.$user_data['user_name'].'</a></span><br />';
				if ($settings['forum_ranks']) {
					echo ''.$locale['hpmc_012'].'<br /><strong>';
					echo HP_mc_fullpath(show_forum_rank($user_data['user_posts'],$user_data['user_level'],$user_data['user_groups'])).'</strong>';
					echo ''.$locale['hpmc_013'].'<br /><strong>'.getuserlevel($user_data['user_level']).'</strong>';
				} else {
					echo ''.$locale['hpmc_013'].'<br /><strong>'.getuserlevel($user_data['user_level']).'</strong>';
				}	
				echo '</center><br />
			</td>
		</tr><tr>
			<td class="tbl1 tbl-border HP_mc_center" colspan="2">'.(count($HP_mc_plinks)>0?implode(' &middot; ',$HP_mc_plinks):'').'</td>
		</tr><tr>';
			#echo '<td class="tbl2" colspan="2">'.$html_groups.'<p>'.(count($HP_mc_pinfo)>0?implode('<br />',$HP_mc_pinfo):'').'</p>'.'</td>';
			echo '<td class="tbl2" colspan="2"><small><p>'.(count($HP_mc_pinfo)>0?implode('<br />',$HP_mc_pinfo):'').'</p>'.''.$html_groups.'</small></td>';
		echo '</tr>

		'.$html_fields.
		(iADMIN && checkrights('M')?'
		<tr>
			<td class="tbl1">'.$locale['hpmc_015'].'</td><td class="tbl1" style="text-align:right;"><a href="mailto:'.$user_data['user_email'].'">'.$user_data['user_email'].'</a></td>
		</tr><tr>
			<td class="tbl1">'.$locale['hpmc_016'].'</td><td class="tbl1" style="text-align:right;"><a href="https://geoiptool.com/de/?ip='.$user_data['user_ip'].'" target="_blank" title="Einwahlpunkt anzeigen">'.$user_data['user_ip'].'</a></td>
		</tr>':'').'
	</table>';
}
?>