<?php
defined('BASEPATH') or exit('No direct script access allowed');

class user_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }
    /*
    public function get_all_users()
    {
        $this->db->order_by('date', 'DESC');
        return $this->db->get('users')->result();
    }
*/
    public function get_all_users()
    {
        $this->db->order_by('date', 'DESC');
        $this->db->select('users.id, users.nama, users.email, users.date, COUNT(chats.id) as chats_sum');
        $this->db->from('users');
        $this->db->join('chats', 'chats.user = users.id', 'left'); // Left join to include users without posts
        $this->db->group_by('users.id');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_user_by_id($id)
    {
        return $this->db->get_where('users', ['id' => $id])->row();
    }

    public function create_user($data)
    {
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data)
    {
        return $this->db->where('id', $id)->update('users', $data);
    }

    public function delete_user($id)
    {
        return $this->db->delete('users', ['id' => $id]);
    }


    public function email_exists($email, $exclude_user_id = null)
    {
        $this->db->where('email', $email);
        if ($exclude_user_id !== null) {
            $this->db->where('id !=', $exclude_user_id);
        }
        return $this->db->get('users')->num_rows() > 0;
    }

    /*
    public function get_user_by_intent($intent)
    {
        $this->db->where('intent', $intent);
        $query = $this->db->get('users');
        return $query->row(); // Return single row (object) or null
    

    public function get_active_users_count($period = 30)
    {
        $sql = "SELECT COUNT(DISTINCT c.user) as count
                FROM chats c
                WHERE c.timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
                AND c.user IS NOT NULL";

        $query = $this->db->query($sql, [$period]);
        return $query->row()->count;
    }

    public function get_user_growth_rate($period = 30)
    {
        $current_period_sql = "SELECT COUNT(DISTINCT user) as count 
                              FROM chats 
                              WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
                              AND user IS NOT NULL";

        $previous_period_sql = "SELECT COUNT(DISTINCT user) as count 
                               FROM chats 
                               WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY) 
                               AND timestamp < DATE_SUB(NOW(), INTERVAL ? DAY)
                               AND user IS NOT NULL";

        $current = $this->db->query($current_period_sql, [$period])->row()->count;
        $previous = $this->db->query($previous_period_sql, [$period * 2, $period])->row()->count;

        if ($previous == 0) return 0;

        return (($current - $previous) / $previous) * 100;
    }

    public function get_user_activity_stats()
    {
        $sql = "SELECT 
                    u.id,
                    u.nama,
                    COUNT(c.id) as total_chats,
                    MAX(c.timestamp) as last_activity,
                    AVG(cd.confident_score) as avg_confidence
                FROM users u
                LEFT JOIN chats c ON u.id = c.user
                LEFT JOIN chat_detail cd ON c.id = cd.chat_id
                WHERE c.timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                GROUP BY u.id, u.nama
                ORDER BY total_chats DESC
                LIMIT 10";

        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function get_total_registered_users()
    {
        return $this->db->count_all('users');
    }*/
}
