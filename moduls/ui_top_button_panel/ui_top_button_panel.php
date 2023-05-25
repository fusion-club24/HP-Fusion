<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| File Name: ui_top_button_panel.php
| Author: Mehmet
| Version: 1.2
| Ported for HP-Fusion by Harlekin
| Based on ui top jqery plugin
| http://www.mattvarone.com/web-design/uitotop-jquery-plugin/
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

add_to_head("
	<link rel='stylesheet' href='".MODULS."ui_top_button_panel/ui.totop.css' type='text/css' media='screen' />
	<script type='text/javascript' src='".MODULS."ui_top_button_panel/js/easing.js'></script>
	<script type='text/javascript' src='".MODULS."ui_top_button_panel/js/jquery.ui.totop.js'></script>
	<script type='text/javascript'>
	   $(document).ready(function() {
		  $().UItoTop({ easingType: 'easeOutQuart' });
	   });
	</script>
");
?>