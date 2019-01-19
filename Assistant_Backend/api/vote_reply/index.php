<?php
/**
 * Vote reply of current business case.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$reply_context = json_decode(file_get_contents('php://input'), true);

$api_obj = new API();
$execute_result = $api_obj->vote_reply($reply_context);
echo json_encode($execute_result);
