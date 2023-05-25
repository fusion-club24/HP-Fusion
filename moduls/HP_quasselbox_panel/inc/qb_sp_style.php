<?php
/*-------------------------------------------------------+
| HP-Fusion based on Content Management System PHP Fusion
| Copyright (C) 2002 - 2011 Nick Jones
| http://www.php-fusion.co.uk/
| HP-Fusion Copyright by Harlekin
| https://harlekin-power.de
+--------------------------------------------------------+
| Filename: qb_sp_style.php
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

/*Side Panel*/
add_to_head("<style type='text/css'>
.quasselbox_msg {border: 1px solid #b52626;border-radius: 5px;background: #ffcbcb;color: #b52626;padding: 5px 10px 5px 10px;margin: 0 auto 10px;width: 85%;}
.quasselbox_msg_info {border: 1px solid #FFDC70;border-radius: 5px;background: #fff4cc;color: #b52626;padding: 5px 10px 5px 10px;margin: 0 auto 10px;width: 85%;}
.quasselboxname {font-size: 12px;font-weight: bold;color: #ffffff;background-color: #$set_qbname_color;text-shadow: 0.1em 0.1em 0.1em #000000;border-width: 1px 0 0 1px;border-color: #000000;border-style: solid;padding: 4px;-webkit-border-radius: 5px;-moz-border-radius: 5px;-webkit-background-clip: padding-box;border-radius: 5px;outline: none;margin-bottom: 2px;}
.quasselboxname a {color: #ffffff;}
.quasselbox {font-size: 12px;color: #000000;background-color: #ffffff;padding: 2px 12px 2px 12px;background-repeat: repeat-y;border-width: 1px;border-color: #000000;border-style: solid;border-bottom: 1px #838383 solid;-webkit-border-radius: 5px;-moz-border-radius: 5px;-webkit-background-clip: padding-box;border-radius: 5px;outline: none;}
.quasselbox img {max-width: 150px;}
.quasselbox iframe {max-width: 150px;max-height: 124px;}
.quasselbox a {text-decoration: underline;color: #b91313;}
.quasselbox a:hover {color: #000000;}
.quasselboxdate {text-align: right;font-size: 9px;background: url(".MODULS."HP_quasselbox_panel/images/arrow/$set_qbdate_color) no-repeat bottom left;}
</style>");
?>