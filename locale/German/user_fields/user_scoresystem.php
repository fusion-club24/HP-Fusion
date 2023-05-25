<?php
$locale['uf_score'] = "ScoreSystem";
$locale['uf_score_desc'] = "Anzeige des Scorestands eines Users";
if (isset($locale['pfss_units'])) {
	$locale['uf_score_1'] = "Aktuelle ".$locale['pfss_units']."";
} else {
	$locale['uf_score_1'] = "Aktuelle Score";
}
?>