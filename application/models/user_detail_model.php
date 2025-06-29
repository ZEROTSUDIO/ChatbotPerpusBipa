<?php
// ============================================
// MODEL: User_model.php (application/models/)
// ============================================
class User_detail_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Get user basic info
    public function get_user_by_id($user_id) {
        return $this->db->where('id', $user_id)->get('users')->row();
    }

    // Get total chats for user
    public function get_total_chats($user_id) {
        return $this->db->where('user', $user_id)->count_all_results('chats');
    }

    // Get average confidence score
    public function get_avg_confidence($user_id) {
        $result = $this->db->select('AVG(confident_score) as avg_confidence')
                          ->where('user_id', $user_id)
                          ->where('confident_score IS NOT NULL')
                          ->get('chat_detail')
                          ->row();
        return $result ? round($result->avg_confidence, 2) : 0;
    }

    // Get OOD count
    public function get_ood_count($user_id) {
        return $this->db->where(['user_id' => $user_id, 'ood' => 1])
                       ->count_all_results('chat_detail');
    }

    // Get today's chat count
    public function get_today_chats($user_id) {
        return $this->db->where('user', $user_id)
                       ->where('DATE(timestamp)', date('Y-m-d'))
                       ->count_all_results('chats');
    }

    // Get chat activity for last 7 days
    public function get_chat_activity($user_id, $days = 7) {
        return $this->db->select('DATE(timestamp) as date, COUNT(*) as count')
                       ->where('user', $user_id)
                       ->where('timestamp >=', date('Y-m-d H:i:s', strtotime("-{$days} days")))
                       ->group_by('DATE(timestamp)')
                       ->order_by('date', 'ASC')
                       ->get('chats')
                       ->result();
    }

    // Get intent distribution
    public function get_intent_distribution($user_id) {
        return $this->db->select('intent, COUNT(*) as count')
                       ->where('user_id', $user_id)
                       ->where('intent IS NOT NULL')
                       ->group_by('intent')
                       ->order_by('count', 'DESC')
                       ->get('chat_detail')
                       ->result();
    }

    // Get confidence trend
    public function get_confidence_trend($user_id, $days = 7) {
        return $this->db->select('DATE(cd.timestamp) as date, AVG(cd.confident_score) as avg_confidence')
                       ->from('chat_detail cd')
                       ->join('chats c', 'c.id = cd.chat_id')
                       ->where('cd.user_id', $user_id)
                       ->where('cd.confident_score IS NOT NULL')
                       ->where('cd.timestamp >=', date('Y-m-d H:i:s', strtotime("-{$days} days")))
                       ->group_by('DATE(cd.timestamp)')
                       ->order_by('date', 'ASC')
                       ->get()
                       ->result();
    }

    // Get recent chats with pagination
    public function get_recent_chats($user_id, $limit = 10, $offset = 0, $days = 7) {
        return $this->db->select('c.id, c.user_message, c.bot_response, c.timestamp, 
                                 cd.intent, cd.confident_score, cd.ood')
                       ->from('chats c')
                       ->join('chat_detail cd', 'c.id = cd.chat_id', 'left')
                       ->where('c.user', $user_id)
                       ->where('c.timestamp >=', date('Y-m-d H:i:s', strtotime("-{$days} days")))
                       ->order_by('c.timestamp', 'DESC')
                       ->limit($limit, $offset)
                       ->get()
                       ->result();
    }

    // Count recent chats for pagination
    public function count_recent_chats($user_id, $days = 7) {
        return $this->db->where('user', $user_id)
                       ->where('timestamp >=', date('Y-m-d H:i:s', strtotime("-{$days} days")))
                       ->count_all_results('chats');
    }
}