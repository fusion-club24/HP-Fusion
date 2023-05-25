<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: fonts_icon_viewer.php
| Author: R8HL Germany
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
require_once "../../maincore.php";
require_once DESIGNS."templates/header.php";

if (file_exists(MODULS."fonts_icon_viewer/locale/".$settings['locale'].".php")) {
	include MODULS."fonts_icon_viewer/locale/".$settings['locale'].".php";
} else {
	include MODULS."fonts_icon_viewer/locale/German.php";
}

add_to_title($locale['global_200'].$locale['fiv_001']);

add_to_head("<link rel='stylesheet' href='".MODULS."fonts_icon_viewer/css/fonts_viewer.css' type='text/css' media='screen' />\n");
add_to_footer("<script type='text/javascript' src='".MODULS."fonts_icon_viewer/jscolor/jscolor.js'></script>");

if ((isset($settings['font_awe']) && $settings['font_awe'] == '0') && (isset($settings['font_et']) && $settings['font_et'] == '0')) {
	opentable("Keine Icon Schrift aktiviert");
		echo "<span style='font-size: 22px;'><strong>Es ist keine Icon Schrift aktiviert!</strong></span><br />",
	closetable();
	require_once DESIGNS."templates/footer.php";
	exit;
}

opentable($locale['fiv_001']);
	echo "<form name='' method='post'  action='".FUSION_SELF."'>";
		if (isset($_POST['fiv_icon'])) {
			$font_icon = $_POST['fiv_icon'];
			$font_icon_color = $_POST['fiv_icon_color'];
		} else {
			$font_icon = '';
			$font_icon_color = '';
		}
		
		$search = array("fa-stretch", "fa-spin");
		$replace = array("", "");
		$clean_icon = str_replace($search, $replace, $font_icon);
		strpos($font_icon,"fa-stretch")!==false ? $pos = "fa-stretch" : $pos = "";
		strpos($font_icon,"fa-spin")!==false ? $pos1 = "fa-spin" : $pos1 = "";
		$pos_all = $pos.$pos1;
		strpos($font_icon,"fas ")!==false ? $pos_1 = "display: block !important;" : $pos_1 = "";
		strpos($font_icon,"fab ")!==false ? $pos_2 = "display: block !important;" : $pos_2 = "";
		$pos_all_1 = $pos_1.$pos_2;
		
		isset($_POST['pos_set']) ? $pos_set_fiv = $_POST['pos_set']:$pos_set_fiv = $pos_all;
		$pos_all_fiv = $pos_set_fiv.$clean_icon;
		$icon_color = (!empty($font_icon_color) ? $fiv_icon_color = $font_icon_color : $fiv_icon_color = '8A8A8A');
		
		echo "<div class='tbl2 center' style='width: 99%;float: left;'>";
			if (isset($_POST['fiv_icon']) && !empty($_POST['fiv_icon'])) {
				echo "<div style='padding: 5px;'>
					<strong>".$locale['fiv_014']."</strong><span style=' padding: 5px;border: 1px solid #666;' >#".$_POST['fiv_icon_color']."</span>&nbsp;&nbsp;";
					if (isset($_POST['pos_set']) && $_POST['pos_set'] !='') {
						$fiv_animation = "<strong>".$locale['fiv_002']."</strong><span style=' padding: 5px;border: 1px solid #666;' >".$_POST['pos_set']."</span>&nbsp;&nbsp;";
						$icon_set = $_POST['pos_set'];
					} else {
						$fiv_animation = '';
						$icon_set ="";
					}
					echo $fiv_animation."<strong>".$locale['fiv_006']."</strong><span style='padding: 5px;border: 1px solid #666;' >".$_POST['fiv_icon']."</span>&nbsp;&nbsp;<strong>".$locale['fiv_007']."</strong><i class='".$icon_set." ".$_POST['fiv_icon']."' style='padding: 5px;font-size: 20px;color: #".$_POST['fiv_icon_color'].";' ></i>&nbsp;&nbsp;<br />";
	
					echo "<div align='center' ><textarea name=\"ouput\" rows=\"1\" class=\"textbox\" style=\"width: 90%;text-align: center; margin-top: 5px; \" align=\"center\" readonly=\"readonly\">&lt;i class='".$icon_set.$_POST['fiv_icon']."' style='color: #".$_POST['fiv_icon_color'].";'&gt;&lt;/i&gt;</textarea></div>";

					if (isset($settings['font_awe']) && $settings['font_awe'] == '1') {
					echo "<div style='display: none; ".$pos_all_1."'>
						<hr />
						&nbsp;fa-lg&nbsp;<span class='fa-lg ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-xs&nbsp;<span class='fa-xs ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-sm&nbsp;<span class='fa-sm ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-1x&nbsp;<span class='fa-1x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-2x&nbsp;<span class='fa-2x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-3x&nbsp;<span class='fa-3x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-4x&nbsp;<span class='fa-4x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-5x&nbsp;<span class='fa-5x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-6x&nbsp;<span class='fa-6x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-7x&nbsp;<span class='fa-7x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-8x&nbsp;<span class='fa-8x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-9x&nbsp;<span class='fa-9x ".$_POST['fiv_icon']."'></span>
						&nbsp;fa-10x&nbsp;<span class='fa-10x ".$_POST['fiv_icon']."'></span>
					</div>
					
					<hr />
					&nbsp;fa-fw&nbsp;<span class='fa-fw ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-ul&nbsp;<span class='fa-ul ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-border&nbsp;<span style='font-size: 22px; padding: 5px;' class='fa-border ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-spin&nbsp;<span style='font-size: 22px; padding: 5px;' class='fa-spin ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-pulse&nbsp;<span style='font-size: 22px; padding: 5px;' class='fa-pulse ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-stretch&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-stretch ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-rotate-90&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-rotate-90 ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-rotate-180&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-rotate-180 ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-rotate-270&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-rotate-270 ".$_POST['fiv_icon']."'></span><hr />
					&nbsp;fa-flip-horizontal&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-flip-horizontal ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-flip-vertical&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-flip-vertical ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-flip-horizontal fa-flip-vertical&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-flip-horizontal fa-flip-vertical ".$_POST['fiv_icon']."'></span>
					&nbsp;fa-stack&nbsp;<span style='font-size: 16px; padding: 5px;' class='fa-stack ".$_POST['fiv_icon']."'></span>";
					}
					echo "<hr />
				</div>";
			} elseif (isset($_POST['fiv_icon']) && $_POST['fiv_icon'] == '') {
				echo "<center><span class='admin-message'><span style='font-size: 22px;'><strong>".$locale['fiv_008']."</strong></span></span></center><br /><br />";
			} else {
				echo "<span style='font-size: 22px;'><strong>".$locale['fiv_008']."</strong></span><br /><br />";
			}
			
			if (isset($settings['font_awe']) && $settings['font_awe'] == '1') {
				$awesome0 = file_get_contents(MODULS.'fonts_icon_viewer/css/font_viewer_fa1.css');
				$awesome0 = preg_match_all("#\.(.*?)\:before#si", $awesome0, $matches0);
				$awesome1 = file_get_contents(MODULS.'fonts_icon_viewer/css/font_viewer_fa2.css');
				$awesome1 = preg_match_all("#\.(.*?)\:before#si", $awesome1, $matches1);
				$awesome2 = file_get_contents(MODULS.'fonts_icon_viewer/css/font_viewer_fa3.css');
				$awesome2 = preg_match_all("#\.(.*?)\:before#si", $awesome2, $matches2);
			}
			if (isset($settings['font_et']) && $settings['font_et'] == '1') {
				$entypo1 = file_get_contents(MODULS.'fonts_icon_viewer/css/font_viewer_et1.css');
				$entypo1 = preg_match_all("#\.(.*?)\:before#si", $entypo1, $matches3);
			}
			
			if ((isset($settings['font_awe']) && $settings['font_awe'] == '1') && (isset($settings['font_et']) && $settings['font_et'] == '1')) {
				$awesome_all = $awesome0+$awesome1+$awesome2+$entypo1;
			} elseif ((isset($settings['font_awe']) && $settings['font_awe'] == '1') && (isset($settings['font_et']) && $settings['font_et'] == '0')) {
				$awesome_all = $awesome0+$awesome1+$awesome2;
			} elseif ((isset($settings['font_awe']) && $settings['font_awe'] == '0') && (isset($settings['font_et']) && $settings['font_et'] == '1')) {
				$awesome_all = $entypo1;
			} elseif ((isset($settings['font_awe']) && $settings['font_awe'] == '0') && (isset($settings['font_et']) && $settings['font_et'] == '0')) {
				$awesome_all = "";
			}
			
			echo "<strong>".$locale['fiv_012']."".$awesome_all."".$locale['fiv_013']."</strong><br /><br />
			<strong>".$locale['fiv_009']."</strong>&nbsp;<input type='text' name='fiv_icon_color' value='".$icon_color."' class='textbox jscolor' />&nbsp;<br />";
			if (isset($settings['font_awe']) && $settings['font_awe'] == '1') {
			echo"<strong>".$locale['fiv_002']."</strong><label><input type='radio' name='pos_set' value='' ".($pos_set_fiv == "" ? "checked='checked'" : "")." /> <strong>".$locale['fiv_003']."</strong></label>&nbsp;&nbsp;&nbsp;<label><input type='radio' name='pos_set' value='fa-stretch' ".($pos_set_fiv == "fa-stretch" ? "checked='checked'" : "")." /> <strong>".$locale['fiv_004']."</strong></label>&nbsp;&nbsp;&nbsp;<label><input type='radio' name='pos_set' value='fa-spin' ".($pos_set_fiv == "fa-spin" ? "checked='checked'" : "")." /> <strong>".$locale['fiv_005']."</strong></label>&nbsp;&nbsp;&nbsp;&nbsp;<button  type='submit' class='button' name='fiv_icon' title='".$locale['fiv_010']."' ><strong>".$locale['fiv_015']."</strong></button>";
			} else {
				echo "<button  type='submit' class='button' name='fiv_icon' title='".$locale['fiv_010']."' ><strong>".$locale['fiv_015']."</strong></button>";
			}
			echo"<hr />
		</div>";
		if (isset($settings['font_awe']) && $settings['font_awe'] == '1') {
		echo "<div class='tbl1 center' style='width: 99%;float: left;' >
			<hr /><center><i style='padding-right: 25px; padding-left: 25px; font-size: 14px; margin-top: 2px;' class='main-body spacer'><strong>Font Awesome fas-solid ( ".$awesome0." )</strong></i></center><hr />";
			
			for($i=0; $i<count($matches0[0]); $i++) {
				echo "<div id='icon_img_fiv' class='fiv_icon' >";  
					echo "<input type='radio'  id='fas ".$matches0[1][$i]."' name='fiv_icon' value=' fas ".$matches0[1][$i]."' ".($clean_icon == " fas ".$matches0[1][$i] ? "checked='checked'" : "")." onchange='this.form.submit();' >";
					echo "<label class='fiv_icon_img' for='fas ".$matches0[1][$i]."'><i style='color: #00F;font-size: 18px;' class='fas ".$matches0[1][$i]."' title='".$matches0[1][$i]."' ></i></label>
				</div>";
			}
		echo "</div>
		<div class='tbl1 center' style='width: 99%;float: left;' >
			<hr /><center><i style='padding-right: 25px; padding-left: 25px; font-size: 14px; margin-top: 2px;' class='main-body spacer'><strong>Font Awesome fab-brands ( ".$awesome1." )</strong></i></center><hr />";
			
			for($i=0; $i<count($matches1[0]); $i++) {
				echo "<div id='icon_img_fiv' class='fiv_icon' >";  
					echo "<input type='radio'  id='fab ".$matches1[1][$i]."' name='fiv_icon' value=' fab ".$matches1[1][$i]."' ".($clean_icon == " fab ".$matches1[1][$i] ? "checked='checked'" : "")." onchange='this.form.submit();' >";
					echo "<label class='fiv_icon_img' for='fab ".$matches1[1][$i]."'><i style='color: #903;font-size: 18px;' class='fab ".$matches1[1][$i]."' title='".$matches1[1][$i]."' ></i></label>
				</div>";
			}
		echo "</div>
		<div class='tbl1 center' style='width: 99%;float: left;' >
			<hr /><center><i style='padding-right: 25px; padding-left: 25px; font-size: 14px; margin-top: 2px;' class='main-body spacer'><strong>Font Awesome fa ( ".$awesome2." )</strong></i></center><hr />";

			for($i=0; $i<count($matches2[0]); $i++) {
				echo "<div id='icon_img_fiv' class='fiv_icon' >";  
					echo "<input type='radio' id='fa ".$matches2[1][$i]."' name='fiv_icon' value=' fa ".$matches2[1][$i]."' ".($clean_icon == " fa ".$matches2[1][$i] ? "checked='checked'" : "")." onchange='this.form.submit();' >";
					echo "<label class='fiv_icon_img' for='fa ".$matches2[1][$i]."'><i style='color: #00F;font-size: 18px;' class='fa ".$matches2[1][$i]."' title='".$matches2[1][$i]."' ></i></label>
				</div>";
			}
		echo "</div>";
		}
		if (isset($settings['font_et']) && $settings['font_et'] == '1') {
		echo "<div class='tbl1 center' style='width: 99%;float: left;' >
			<hr /><center><i style='padding-right: 25px; padding-left: 25px; font-size: 14px; margin-top: 2px;' class='main-body spacer'><strong>Font Entypo entypo ( ".$entypo1." )</strong></i></center><hr />";
			for($i=0; $i<count($matches3[0]); $i++) {
				echo "<div id='icon_img_fiv' class='fiv_icon' >";  
					echo "<input type='radio' id='entypo ".$matches3[1][$i]."' name='fiv_icon' value=' entypo ".$matches3[1][$i]."' ".($clean_icon == " entypo ".$matches3[1][$i] ? "checked='checked'" : "")." onchange='this.form.submit();' >";
					echo "<label class='fiv_icon_img' for='entypo ".$matches3[1][$i]."'><i style='color: #903;font-size: 18px;' class='entypo ".$matches3[1][$i]."' title='".$matches3[1][$i]."' ></i></label>
				</div>";
			} 
		echo "</div>";
		}
	echo "</form>
	<div align='right'>
		<a href='https://rolly8-hl.de' target='_blank' title='".$locale['fiv_001']." by Rolly8-HL'>&copy;</a>
	</div>";
closetable();

require_once DESIGNS."templates/footer.php";
?>