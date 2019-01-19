<?php
/**
 * Get history business cases.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$id = addslashes(isset($_GET["id"]) ? trim($_GET["id"]) : '');

$api_obj = new API();
$result_set = $api_obj->get_case_content($id);
echo json_encode($result_set);
