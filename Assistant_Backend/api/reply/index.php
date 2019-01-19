<?php
/**
 * Reply business case.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$reply_content = json_decode(file_get_contents('php://input'), true);

$api_obj = new API();
$execute_result = $api_obj->set_case_reply($reply_content);
echo $execute_result;
