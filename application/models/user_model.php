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
    */
}
