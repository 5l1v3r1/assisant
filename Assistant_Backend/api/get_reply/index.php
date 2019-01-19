<?php
/**
 * Get business case reply.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$case_reply_context = json_decode(file_get_contents('php://input'), true);

$api_obj = new API();
$result_set = $api_obj->get_case_reply($case_reply_context);
echo json_encode($result_set);
