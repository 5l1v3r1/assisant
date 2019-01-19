<?php

/**
 * Mini program operation class.
 */

require(dirname(__FILE__) . '/db.php');
require_once(dirname(dirname(__FILE__)) . '/config.php');


class API
{
    // Database operation class instance object
    private $db_obj;

    public function __construct()
    {
        $this->db_obj = DB::get_instance();
    }

    // Get login session
    public function get_login_session($code)
    {
        $wechat_url = "https://api.weixin.qq.com/sns/jscode2session?" .
                        "appid=" . APP_ID . "&secret=" . SECRET . "&js_code=" . $code .
                        "&grant_type=authorization_code";
        $user_session = json_decode(file_get_contents($wechat_url));
        $openid = isset($user_session->openid) ? $user_session->openid : '';
        return crypt($openid, '$2a$10$VSqSBV9vWcvjf21DyHQvWH$');
    }

    // Save login user information.
    public function save_login_user($token, $user)
    {
        $add_user_sql = "INSERT INTO login_user(openid, nickname, avatar, gender, country, province, city, language) " .
            "VALUES(?, ?, ?, ?, ?, ?, ?, ?)";

        $execute_result = $this->db_obj->insert($add_user_sql,
            array("sssissss", $token, $user['nickName'], $user['avatarUrl'], $user['gender'],
                $user['country'], $user['province'], $user['city'], $user['language']));
        return $execute_result ? 1 : 0;
    }

    // Publish business case
    public function set_business_case($content)
    {
        $is_time = $this->check_publish_time();
        if (!$is_time) {
            return 0;
        }

        $token = $content['token'];
        date_default_timezone_set('PRC');
        $create_date = date('Y-m-d H:i:s');
        $context = $content['context'];
        $question = $content['question'];
        if (empty($context) or empty($question)) {
            return -1;
        }

        $add_case_sql = "INSERT INTO case_content(openid, create_date, context, question) VALUES(?, ?, ?, ?)";
        $execute_result = $this->db_obj->insert($add_case_sql, array("ssss", $token, $create_date, $context, $question));
        return $execute_result ? 1 : 0;
    }

    // Reply business case
    public function set_case_reply($content)
    {
        $is_owner = $this->check_case_owner($content);
        if ($is_owner) {
            return 0;
        }

        $token = $content['token'];
        date_default_timezone_set('PRC');
        $create_date = date('Y-m-d H:i:s');
        $case_id = $content['case_id'];
        $reply = $content['reply'];
        if (empty($reply) or empty($case_id)) {
            return -1;
        }

        $query_reply_sql = "SELECT 1 FROM case_reply WHERE case_id = ? AND openid = ?";
        $reply_exist = $this->db_obj->query($query_reply_sql, array("is", $case_id, $token));
        if(isset($reply_exist[0])) {
            $update_reply_sql = "UPDATE case_reply SET reply = ? WHERE case_id = ? AND openid = ?";
            $execute_result = $this->db_obj->update($update_reply_sql, array("sis", $reply, $case_id, $token));
        }else {
            $insert_reply_sql = "INSERT INTO case_reply(openid, create_date, case_id, reply) VALUES(?, ?, ?, ?)";
            $execute_result = $this->db_obj->insert($insert_reply_sql, array("ssis", $token, $create_date, $case_id, $reply));
        }

        return $execute_result ? 1 : 0;
    }

    // Query history cases.
    public function get_history_case()
    {
        $get_history_case_sql = "SELECT a.id, b.nickname, b.avatar, SUBSTRING_INDEX(a.create_date, ' ', 1)  create_date " .
                    "FROM case_content a, login_user b WHERE a.openid = b.openid ORDER BY a.id DESC";
        $old_case = $this->db_obj->query($get_history_case_sql);
        return $old_case;
    }

    // Filter history cases according to date.
    public function filter_case($filter_date)
    {
        $date = $filter_date['date'];
        $get_history_case_sql = "SELECT a.id, b.nickname, b.avatar, SUBSTRING_INDEX(a.create_date, ' ', 1)  create_date " .
            "FROM case_content a, login_user b WHERE a.openid = b.openid AND SUBSTRING_INDEX(a.create_date, ' ', 1) = ? " .
            "ORDER BY a.id DESC";
        $old_case = $this->db_obj->query($get_history_case_sql, array('s', $date));
        return $old_case;
    }

    // Get case content by case id.
    public function get_case_content($id)
    {
        $get_case_sql = "SELECT b.nickname, b.avatar, a.create_date, a.context, a.question " .
                    "FROM case_content a, login_user b WHERE a.openid = b.openid AND a.id = ?";
        $case_content = $this->db_obj->query($get_case_sql, array("i", $id));
        return $case_content;
    }

    // Get case reply by case id.
    public function get_case_reply($case_reply_context)
    {
        $openid = $case_reply_context['token'];
        $case_id = $case_reply_context['case_id'];
        $get_reply_sql = "SELECT reply FROM case_reply WHERE case_id = ? AND openid = ?";
        $case_reply = $this->db_obj->query($get_reply_sql, array("is", $case_id, $openid));
        return $case_reply;
    }

    // Get case reply list by case id.
    public function get_case_reply_list($case_id)
    {
        $get_reply_list_sql = "SELECT a.rid, b.nickname, b.avatar, a.create_date, a.reply, a.is_best , COUNT(c.openid) vote " .
                        "FROM case_reply a " .
                        "JOIN login_user b " .
                        "ON a.openid = b.openid AND a.case_id = ? " .
                        "LEFT JOIN reply_vote c " .
                        "ON a.rid = c.rid " .
                        "GROUP BY a.rid";
        $reply_list = $this->db_obj->query($get_reply_list_sql, array("i", $case_id));
        return $reply_list;
    }

    // Get case reply list by filter conditions.
    public function get_filter_reply($filter_reply)
    {
        $case_id = $filter_reply['case_id'];
        // index=0: all reply
        // index=1: best reply
        $filter_index = $filter_reply['index'];
        if ($filter_index == 0) {
            $filter_cond = "";
        }elseif ($filter_index == 1) {
            $filter_cond = "AND a.is_best = 1 ";
        }else {
            $filter_cond = "";
        }
        $get_reply_list_sql = "SELECT a.rid, b.nickname, b.avatar, a.create_date, a.reply, a.is_best , COUNT(c.openid) vote " .
                        "FROM case_reply a " .
                        "JOIN login_user b " .
                        "ON a.openid = b.openid AND a.case_id = ? " . $filter_cond .
                        "LEFT JOIN reply_vote c " .
                        "ON a.rid = c.rid " .
                        "GROUP BY a.rid";
        $reply_list = $this->db_obj->query($get_reply_list_sql, array("i", $case_id));
        return $reply_list;
    }

    // Get current week and check if is time to publish.
    public function check_publish_time()
    {
        date_default_timezone_set('PRC');
        $week = date('w');
        return $week == 2 ? 1 : 0;
    }

    // Check if is owner of current business case.
    public function check_case_owner($case_context)
    {
        $case_id = $case_context['case_id'];
        $openid = $case_context['token'];

        $get_owner_sql = "SELECT openid FROM case_content WHERE id = ?";
        $case_owner = $this->db_obj->query($get_owner_sql, array("i", $case_id));
        if(isset($case_owner[0])) {
            return $openid == $case_owner[0]['openid'] ? 1 : 0;
        }else {
            return 0;
        }
    }

    // Set best reply of current business case.
    public function set_best_reply($reply_context)
    {
        $openid = $reply_context['token'];
        $rid = $reply_context['reply_id'];
        $case_id = $reply_context['case_id'];

        $set_best_sql = "UPDATE case_reply a SET a.is_best = 1 " .
                    "WHERE a.rid = ? AND a.case_id = ? AND a.case_id IN (SELECT b.id FROM case_content b WHERE b.openid = ?)";
        $this->db_obj->update($set_best_sql, array("iis", $rid, $case_id, $openid));
        return $this->get_case_reply_list($case_id);
    }

    // Vote reply of current business case.
    public function vote_reply($reply_context)
    {
        $openid = $reply_context['token'];
        $rid = $reply_context['reply_id'];
        $case_id = $reply_context['case_id'];

        $vote_reply_sql = "INSERT reply_vote(rid, openid) VALUES(?, ?)";
        $vote_result = $this->db_obj->insert($vote_reply_sql, array("is", $rid, $openid));
        if (!$vote_result) {
            $del_vote_sql = "DELETE FROM reply_vote WHERE rid = ? AND openid = ?";
            $this->db_obj->insert($del_vote_sql, array("is", $rid, $openid));
        }
        return $this->get_case_reply_list($case_id);
    }
}