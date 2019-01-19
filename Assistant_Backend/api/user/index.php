<?php
/**
 * Get user openid according to login user code.
 */
include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$login_user_json = json_decode(file_get_contents('php://input'), true);
$user_token = $login_user_json['token'];
$user_info = $login_user_json['user_info'];

$api_obj = new API();
$result = $api_obj->save_login_user($user_token, $user_info);
echo $result;