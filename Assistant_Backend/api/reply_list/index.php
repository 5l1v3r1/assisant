<?php
/**
 * Get business case reply list.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$case_id = addslashes(isset($_GET["id"]) ? trim($_GET["id"]) : '');

$api_obj = new API();
$result_set = $api_obj->get_case_reply_list($case_id);
echo json_encode($result_set);
