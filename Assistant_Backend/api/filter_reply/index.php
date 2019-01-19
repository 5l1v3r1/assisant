<?php
/**
 * Get business case reply list.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$filter_reply = json_decode(file_get_contents('php://input'), true);

$api_obj = new API();
$result_set = $api_obj->get_filter_reply($filter_reply);
echo json_encode($result_set);
