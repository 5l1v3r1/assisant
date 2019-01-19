<?php
/**
 * Get user openid according to login user code.
 */
include(dirname(dirname(dirname(__FILE__))) . '/include/json.php');
include(dirname(dirname(dirname(__FILE__))) . '/include/api.php');

$login_code_json = json_decode(file_get_contents('php://input'), true);
$login_code = $login_code_json['code'];

$api_obj = new API();
$token = $api_obj->get_login_session($login_code);
echo $token;