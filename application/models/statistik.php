<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Statistics_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Chat_model');
        $this->load->model('User_model');
        $this->load->model('Chat_detail_model');
    }

    public function get_dashboard_overview()
    {
        $totalChats = $this->Chat_model->get_total_chats();
        $activeUsers = $this->User_model->get_active_users_count();
        $avgConfidence = $this->Chat_detail_model->get_average_confidence();
        $oodRate = $this->Chat_detail_model->get_ood_rate();

        // Get growth rates
        $chatGrowth = $this->Chat_model->get_chat_growth_rate();
        $userGrowth = $this->User_model->get_user_growth_rate();
        $confidenceChange = $this->get_confidence_change();
        $oodChange = $this->get_ood_change();

        return [
            'totalChats' => (int)$totalChats,
            'activeUsers' => (int)$activeUsers,
            'avgConfidence' => (float)$avgConfidence,
            'oodRate' => (float)$oodRate,
            'chatGrowth' => (float)$chatGrowth,
            'userGrowth' => (float)$userGrowth,
            'confidenceChange' => (float)$confidenceChange,
            'oodChange' => (float)$oodChange
        ];
    }

    public function get_growth_metrics()
    {
        return [
            'chat_growth' => $this->Chat_model->get_chat_growth_rate(),
            'user_growth' => $this->User_model->get_user_growth_rate(),
            'confidence_change' => $this->get_confidence_change(),
            'ood_change' => $this->get_ood_change()
        ];
    }

    public function get_performance_metrics()
    {
        $sql = "SELECT 
                    AVG(cd.confident_score) as avg_confidence,
                    (SUM(cd.ood) / COUNT(*)) as ood_rate,
                    COUNT(DISTINCT cd.intent) as total_intents,
                    COUNT(*) as total_interactions
                FROM chat_detail cd
                WHERE cd.timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)";

        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function get_activity_timeline($limit = 10)
    {
        $sql = "SELECT 
                    'chat' as type,
                    CONCAT('New chat from ', COALESCE(u.nama, 'Guest'), ' with intent: ', COALESCE(cd.intent, 'Unknown')) as message,
                    DATE_FORMAT(c.timestamp, '%M %d, %Y at %h:%i %p') as time,
                    c.timestamp
                FROM chats c
                LEFT JOIN users u ON c.user = u.id
                LEFT JOIN chat_detail cd ON c.id = cd.chat_id
                WHERE c.timestamp >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                
                UNION ALL
                
                SELECT 
                    'user' as type,
                    CONCAT('New user registered: ', nama) as message,
                    DATE_FORMAT(date, '%M %d, %Y') as time,
                    CAST(date as DATETIME) as timestamp
                FROM users
                WHERE date >= DATE_SUB(NOW(), INTERVAL 7 DAY)
                
                ORDER BY timestamp DESC
                LIMIT ?";

        $query = $this->db->query($sql, [$limit]);
        return $query->result_array();
    }

    public function get_export_data($period = 30)
    {
        $sql = "SELECT 
                    DATE(c.timestamp) as date,
                    COUNT(*) as total_chats,
                    COUNT(DISTINCT c.user) as active_users,
                    AVG(cd.confident_score * 100) as avg_confidence,
                    (SUM(cd.ood) / COUNT(*)) * 100 as ood_rate
                FROM chats c
                LEFT JOIN chat_detail cd ON c.id = cd.chat_id
                WHERE c.timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(c.timestamp)
                ORDER BY date DESC";

        $query = $this->db->query($sql, [$period]);
        return $query->result_array();
    }

    private function get_confidence_change()
    {
        $current = $this->Chat_detail_model->get_average_confidence(30);
        $previous = $this->Chat_detail_model->get_average_confidence(60) - $current;

        if ($previous == 0) return 0;

        return (($current - $previous) / $previous) * 100;
    }

    private function get_ood_change()
    {
        $current = $this->Chat_detail_model->get_ood_rate(30);
        $previous = $this->Chat_detail_model->get_ood_rate(60) - $current;

        if ($previous == 0) return 0;

        return (($current - $previous) / $previous) * 100;
    }
}
