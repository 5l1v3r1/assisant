<?php
/**
 * Check if is owner of current business case.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$case_context = json_decode(file_get_contents('php://input'), true);

$api_obj = new API();
$execute_result = $api_obj->check_case_owner($case_context);
echo $execute_result;
