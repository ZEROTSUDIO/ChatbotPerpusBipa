<?php
defined('BASEPATH') or exit('No direct script access allowed');

class user_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_users()
    {
        $this->db->order_by('date', 'DESC');
        return $this->db->get('users')->result();
    }

    public function get_user_by_id($id)
    {
        return $this->db->get_where('users', array('id' => $id))->row();
    }

    public function create_user($data)
    {
        $data['date'] = date('Y-m-d H:i:s');
        return $this->db->insert('users', $data);
    }

    public function update_user($id, $data)
    {
        $data['date'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('users', $data);
    }

    public function delete_user($id)
    {
        return $this->db->delete('users', array('id' => $id));
    }
    /*
    public function get_user_by_intent($intent)
    {
        $this->db->where('intent', $intent);
        $query = $this->db->get('users');
        return $query->row(); // Return single row (object) or null
    */
}
