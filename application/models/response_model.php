<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Response_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_responses()
    {
        $this->db->order_by('updated_at', 'DESC');
        return $this->db->get('responses')->result();
    }

    public function get_response_by_id($id)
    {
        return $this->db->get_where('responses', array('id' => $id))->row();
    }

    public function create_response($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        return $this->db->insert('responses', $data);
    }

    public function update_response($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        return $this->db->update('responses', $data);
    }

    public function delete_response($id)
    {
        return $this->db->delete('responses', array('id' => $id));
    }

    public function get_response_by_intent($intent)
    {
        $this->db->where('intent', $intent);
        $query = $this->db->get('responses');
        return $query->row(); // Return single row (object) or null
    }
}
