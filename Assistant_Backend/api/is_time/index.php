<?php
/**
 * Check if is time to publish business case.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$api_obj = new API();
$result_set = $api_obj->check_publish_time();
echo $result_set;
