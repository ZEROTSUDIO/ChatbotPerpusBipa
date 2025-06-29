<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class statistic_model extends CI_Model {

    public function get_user_by_id($user_id) {
        return $this->db->where('id', $user_id)->get('users')->row();
    }

    public function get_user_statistics($user_id) {
        // Total chats
        $total_chats = $this->db->where('user', $user_id)->count_all_results('chats');
        
        // Average confidence score
        $avg_confidence = $this->db->select('AVG(confident_score) as avg_conf')
                                 ->where('user_id', $user_id)
                                 ->get('chat_detail')
                                 ->row()->avg_conf;
        
        // Unique intents count
        $unique_intents = $this->db->select('COUNT(DISTINCT intent) as unique_count')
                                  ->where('user_id', $user_id)
                                  ->get('chat_detail')
                                  ->row()->unique_count;
        
        // Out of domain count
        $ood_count = $this->db->where('user_id', $user_id)
                             ->where('ood', 1)
                             ->count_all_results('chat_detail');
        
        return [
            'total_chats' => $total_chats ?: 0,
            'avg_confidence' => round($avg_confidence ?: 0, 1),
            'unique_intents' => $unique_intents ?: 0,
            'ood_count' => $ood_count ?: 0
        ];
    }

    public function get_daily_activity($user_id, $days = 30) {
        $sql = "SELECT DATE(timestamp) as date, COUNT(*) as count 
                FROM chats 
                WHERE user = ? AND timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(timestamp) 
                ORDER BY date ASC";
        
        $query = $this->db->query($sql, [$user_id, $days]);
        return $query->result();
    }

    public function get_top_intents($user_id, $limit = 5) {
        return $this->db->select('intent, COUNT(*) as count')
                       ->where('user_id', $user_id)
                       ->where('intent IS NOT NULL')
                       ->group_by('intent')
                       ->order_by('count', 'DESC')
                       ->limit($limit)
                       ->get('chat_detail')
                       ->result();
    }

    public function get_confidence_distribution($user_id) {
        $sql = "SELECT 
                    CASE 
                        WHEN confident_score <= 0.2 THEN '0-20%'
                        WHEN confident_score <= 0.4 THEN '21-40%'
                        WHEN confident_score <= 0.6 THEN '41-60%'
                        WHEN confident_score <= 0.8 THEN '61-80%'
                        ELSE '81-100%'
                    END as range_label,
                    COUNT(*) as count
                FROM chat_detail 
                WHERE user_id = ? AND confident_score IS NOT NULL
                GROUP BY range_label
                ORDER BY MIN(confident_score)";
        
        $query = $this->db->query($sql, [$user_id]);
        return $query->result();
    }

    public function get_energy_distribution($user_id) {
        $sql = "SELECT 
                    CASE 
                        WHEN energy <= 0.2 THEN 'Very Low'
                        WHEN energy <= 0.4 THEN 'Low'
                        WHEN energy <= 0.6 THEN 'Medium'
                        WHEN energy <= 0.8 THEN 'High'
                        ELSE 'Very High'
                    END as energy_level,
                    COUNT(*) as count
                FROM chat_detail 
                WHERE user_id = ? AND energy IS NOT NULL
                GROUP BY energy_level
                ORDER BY MIN(energy)";
        
        $query = $this->db->query($sql, [$user_id]);
        return $query->result();
    }

    public function get_ood_analysis($user_id) {
        return $this->db->select('ood, COUNT(*) as count')
                       ->where('user_id', $user_id)
                       ->group_by('ood')
                       ->get('chat_detail')
                       ->result();
    }

    public function get_hourly_pattern($user_id) {
        $sql = "SELECT HOUR(timestamp) as hour, COUNT(*) as count
                FROM chats 
                WHERE user = ?
                GROUP BY HOUR(timestamp)
                ORDER BY hour";
        
        $query = $this->db->query($sql, [$user_id]);
        return $query->result();
    }
}