<?php
class Chat_model extends CI_Model
{

    public function saveChat($table, $data)
    {
        $this->db->insert($table, $data);
    }
    public function saveChatDetail($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    // Simpan ke tabel class_probability
    public function saveClassProbability($table, $data)
    {
        return $this->db->insert($table, $data);
    }

    public function getChatHistory($table)
    {
        $this->db->order_by('timestamp', 'ASC');
        return $this->db->get($table)->result_array(); // Ambil chat history dari tabel yang sesuai
    }

    public function clearChatHistory($table, $userId)
    {
        return $this->db->where('user', $userId)->delete($table);
    }

    public function getChatHistoryByUser($table, $user_id)
    {
        $this->db->where('user', $user_id);
        $this->db->order_by('timestamp', 'ASC'); // jika ada kolom waktu
        return $this->db->get($table)->result_array(); // bukan ->result()

    }
    //========================================

    public function get_user_stats($user_id)
    {
        // Hitung total chats
        $this->db->select('COUNT(*) as total_chats');
        $this->db->from('chats');
        $this->db->where('user', $user_id);
        $total_chats = $this->db->get()->row()->total_chats;

        return array(
            'total_chats' => $total_chats
        );
    }

    public function get_user_chat_history($user_id)
    {
        $this->db->select('c.*, cd.intent, cd.confident_score, cd.energy, cd.ood');
        $this->db->from('chats c');
        $this->db->join('chat_detail cd', 'c.id = cd.chat_id', 'left');
        $this->db->where('c.user', $user_id);
        $this->db->order_by('c.timestamp', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }

    public function get_user_chat_details($user_id, $intent = null)
    {
        $this->db->select('c.id, c.user_message, c.bot_response, c.timestamp, cd.intent, cd.confident_score, cd.energy, cd.ood');
        $this->db->from('chats c');
        $this->db->join('chat_detail cd', 'c.id = cd.chat_id', 'inner');
        $this->db->where('c.user', $user_id);

        if ($intent && $intent != 'all') {
            $this->db->where('cd.intent', $intent);
        }

        $this->db->order_by('c.timestamp', 'DESC');
        $query = $this->db->get();

        return $query->result();
    }
    

    public function get_ood_stats($user_id)
    {
        // OOD = 1, Non-OOD = 0
        $this->db->select('cd.ood, COUNT(*) as count');
        $this->db->from('chat_detail cd');
        $this->db->join('chats c', 'cd.chat_id = c.id', 'inner');
        $this->db->where('c.user', $user_id);
        $this->db->group_by('cd.ood');
        $query = $this->db->get();

        $result = $query->result();
        $stats = array('ood' => 0, 'non_ood' => 0);

        foreach ($result as $row) {
            if ($row->ood == 1) {
                $stats['ood'] = $row->count;
            } else {
                $stats['non_ood'] = $row->count;
            }
        }

        return $stats;
    }

    public function get_user_intents($user_id)
    {
        $this->db->distinct();
        $this->db->select('cd.intent');
        $this->db->from('chat_detail cd');
        $this->db->join('chats c', 'cd.chat_id = c.id', 'inner');
        $this->db->where('c.user', $user_id);
        $this->db->where('cd.intent IS NOT NULL');
        $this->db->order_by('cd.intent', 'ASC');
        $query = $this->db->get();

        return $query->result();
    }


    //==============================================================//
    /*

    public function get_total_chats($period = null)
    {
        $this->db->select('COUNT(*) as total');

        if ($period) {
            $this->db->where('timestamp >=', date('Y-m-d H:i:s', strtotime("-{$period} days")));
        }

        $query = $this->db->get('chats');
        return $query->row()->total;
    }

    public function get_chat_volume_by_period($period = 7)
    {

        $sql = "SELECT 
                    DATE(timestamp) as date,
                    COUNT(*) as count
                FROM chats 
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
                GROUP BY DATE(timestamp)
                ORDER BY date ASC";

        $query = $this->db->query($sql, [$period]);
        $results = $query->result_array();

        $labels = [];
        $data = [];

        foreach ($results as $row) {
            $labels[] = date('M j', strtotime($row['date']));
            $data[] = (int)$row['count'];
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function get_peak_hours_data()
    {
        $sql = "SELECT 
                    DAYOFWEEK(timestamp) as day_of_week,
                    CASE 
                        WHEN HOUR(timestamp) BETWEEN 0 AND 5 THEN 0
                        WHEN HOUR(timestamp) BETWEEN 6 AND 11 THEN 1
                        WHEN HOUR(timestamp) BETWEEN 12 AND 17 THEN 2
                        ELSE 3
                    END as hour_range,
                    COUNT(*) as message_count
                FROM chats 
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY day_of_week, hour_range
                ORDER BY day_of_week, hour_range";

        $query = $this->db->query($sql);
        $results = $query->result_array();

        // Initialize 7x4 matrix (7 days, 4 time periods)
        $heatmap = array_fill(0, 7, array_fill(0, 4, 0));

        foreach ($results as $row) {
            $day = $row['day_of_week'] - 1; // Convert to 0-based index
            $hour = $row['hour_range'];
            $heatmap[$day][$hour] = (int)$row['message_count'];
        }

        return $heatmap;
    }

    public function get_recent_chats($limit = 10)
    {
        $this->db->select('c.*, u.nama as user_name, cd.intent, cd.confident_score');
        $this->db->from('chats c');
        $this->db->join('users u', 'c.user = u.id', 'left');
        $this->db->join('chat_detail cd', 'c.id = cd.chat_id', 'left');
        $this->db->order_by('c.timestamp', 'DESC');
        $this->db->limit($limit);

        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_chat_growth_rate($period = 30)
    {
        $current_period_sql = "SELECT COUNT(*) as count 
                              FROM chats 
                              WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)";

        $previous_period_sql = "SELECT COUNT(*) as count 
                               FROM chats 
                               WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY) 
                               AND timestamp < DATE_SUB(NOW(), INTERVAL ? DAY)";

        $current = $this->db->query($current_period_sql, [$period])->row()->count;
        $previous = $this->db->query($previous_period_sql, [$period * 2, $period])->row()->count;

        if ($previous == 0) return 0;

        return (($current - $previous) / $previous) * 100;
    }

    public function get_intent_distribution_by_user($user_id)
    {
        $this->db->select('intent, COUNT(*) as total');
        $this->db->from('chat_detail');
        $this->db->where('user_id', $user_id);
        $this->db->group_by('intent');
        $this->db->order_by('total', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_confidence_over_time_by_user($user_id)
    {
        $this->db->select('timestamp, confident_score');
        $this->db->from('chat_detail');
        $this->db->where('user_id', $user_id);
        $this->db->order_by('timestamp', 'ASC');
        return $this->db->get()->result_array();
    }
    public function get_chat_detail_by_user($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('timestamp', 'ASC');
        return $this->db->get('chat_detail')->result_array();
    }
    */
}
