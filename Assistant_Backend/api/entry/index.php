<?php
/**
 * Get history business cases.
 */

include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

// Get searching account result set.
$api_obj = new API();
$result_set = $api_obj->get_history_case();
echo json_encode($result_set);
