<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright © 2002 - 2012 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Name: Bl-iteII Theme
| Filename: footer.php
| Author: Fangree Productions
| Version: v1.00
| Developers: Fangree_Craig
| Site: http://www.fangree.com
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at http://www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

echo"<div id='footer-main'>\n

<div style='padding-right: 20px; '><div id='templatemo_footer'>\n

 <div class='cleaner'></div>\n";
echo"<div style=' border-bottom-left-radius : 5px; border-bottom-right-radius :5px; -moz-border-radius-bottomleft : 5px; -moz-border-radius-bottomright : 5px; -webkit-border-bottom-left-radius : 5px; -webkit-border-bottom-right-radius : 5px;'>\n";
echo"<div style='margin: 5px; '>\n";
echo"<div class='copyright float-left'>".(!$license ? showcopyright()."" : "")."\n";

if ($settings['rendertime_enabled'] =='1' || $settings['rendertime_enabled'] =='2' || $settings['visitorcounter_enabled']) {
if ($settings['rendertime_enabled'] =='1' || $settings['rendertime_enabled'] =='2') {
echo "<br />".showrendertime()."\n";
}
}
echo "<br />".showcounter()."\n";
if ($settings['visitorcounter_enabled']) { echo"<br />"; }
echo"</div>";
echo"<div class='float-right' style='vertical-align: top;'>\n";
echo"<a href='http://www.mozilla.com/firefox/' target='_blank'><img src='".THEME."images/social-icons/firefox.png'  alt='Get Firefox' /></a>  <a href='https://www.google.com/chrome/' target='_blank'><img src='".THEME."images/social-icons/chrome.png' alt='Get Google Chrome' /></a>  <a href='http://www.opera.com/' target='_blank'><img src='".THEME."images/social-icons/opera.png' alt='Get Opera' /></a>   <a href='http://www.apple.com/safari/' target='_blank'><img src='".THEME."images/social-icons/safari.png'  alt='Get Safari' /></a>
</a> <a href='http://windows.microsoft.com' target='_blank'><img src='".THEME."images/social-icons/IE.png'  alt='Get IE' /></a>
</a><br />";
echo" <a class='white' href='http://www.hellasplus.com' target='_blank' title='hellasplus'>[ Desing by Dimi RedOnBlack theme ]</a>\n";
echo"</div>";
echo" <div class='margin_bottom_10'>\n</div>\n";
echo"</div>\n";

?>