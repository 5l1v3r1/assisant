<?php
/**
 * Filter business case according to publish date.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$filter_date = json_decode(file_get_contents('php://input'), true);

$api_obj = new API();
$result_set = $api_obj->filter_case($filter_date);
echo json_encode($result_set);
