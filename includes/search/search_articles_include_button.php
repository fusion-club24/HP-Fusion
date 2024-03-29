<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: search_articles_include_button.php
| Author: Robert Gaudyn (Wooya)
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) {
    die("Access Denied");
}
if (db_exists(DB_ARTICLES)) {
    include LOCALE.LOCALESET."search/articles.php";
    $form_elements['articles']['enabled'] = ["datelimit", "fields1", "fields2", "fields3", "sort", "order1", "order2", "chars"];
    $form_elements['articles']['disabled'] = [];
    $form_elements['articles']['display'] = [];
    $form_elements['articles']['nodisplay'] = [];
    $radio_button['articles'] = "<label><input type='radio' name='stype' value='articles'".($_REQUEST['stype'] == "articles" ? " checked='checked'" : "")." onclick=\"display(this.value)\" /> ".$locale['a400']."</label>";
}
