<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Add authentication check here if needed
        // $this->load->library('session');
        // if (!$this->session->userdata('admin_logged_in')) {
        //     redirect('admin/login');
        // }
        $this->load->model('response_model');
    }

    public function index()
    {
        $this->dashboard();
    }

    public function dashboard()
    {
        $data['title'] = 'Dashboard';

        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/dashboard');
        $this->load->view('admin/footer');
    }

    public function users()
    {
        $data['title'] = 'Users Management';

        // In real application, you would load users from database
        // $this->load->model('User_model');
        // $data['users'] = $this->User_model->get_all_users();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/users');
        $this->load->view('admin/footer');
    }

    public function chats()
    {
        $data['title'] = 'Chat Reports';

        // In real application, you would load chat data from database
        // $this->load->model('Chat_model');
        // $data['chats'] = $this->Chat_model->get_all_chats();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/chats');
        $this->load->view('admin/footer');
    }

    public function intents()
    {
        $data['responses'] = $this->response_model->get_all_responses();
        $data['title'] = 'Intent Responses';

        // In real application, you would load intents from database
        // $this->load->model('Intent_model');
        // $data['intents'] = $this->Intent_model->get_all_intents();

        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar');
        $this->load->view('admin/intents', $data);
        $this->load->view('admin/footer');
    }

    // AJAX endpoint for updating intent responses
    public function update_intent_response()
    {
        if ($this->input->method() === 'post') {
            $intent_name = $this->input->post('intent_name');
            $response_content = $this->input->post('response_content');

            // In real application, you would update database
            // $this->load->model('Intent_model');
            // $result = $this->Intent_model->update_response($intent_name, $response_content);

            // For now, just return success
            echo json_encode([
                'status' => 'success',
                'message' => 'Intent response updated successfully',
                'timestamp' => date('Y-m-d H:i:s')
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }
    }

    // AJAX endpoint for adding new intent
    public function add_intent()
    {
        if ($this->input->method() === 'post') {
            $intent_name = $this->input->post('intent_name');
            $response_content = $this->input->post('response_content');

            // In real application, you would insert to database
            // $this->load->model('Intent_model');
            // $result = $this->Intent_model->add_intent($intent_name, $response_content);

            echo json_encode([
                'status' => 'success',
                'message' => 'New intent added successfully'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid request method'
            ]);
        }
    }

    // Example method for handling user actions
    public function delete_user($user_id)
    {
        // In real application, you would delete from database
        // $this->load->model('User_model');
        // $result = $this->User_model->delete_user($user_id);

        $this->session->set_flashdata('success', 'User deleted successfully');
        redirect('admin/users');
    }


    //intents
    public function get_response()
    {
        $id = $this->input->post('id');
        $response = $this->response_model->get_response_by_id($id);

        if ($response) {
            echo json_encode([
                'success' => true,
                'data' => $response
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Response not found'
            ]);
        }
    }

    public function create()
    {
        $data = array(
            'intent' => $this->input->post('intent'),
            'response' => $this->input->post('response')
        );

        if ($this->response_model->create_response($data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Response created successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to create response'
            ]);
        }
    }

    public function update()
    {
        $id = $this->input->post('id');
        $data = array(
            'intent' => $this->input->post('intent'),
            'response' => $this->input->post('response')
        );

        if ($this->response_model->update_response($id, $data)) {
            echo json_encode([
                'success' => true,
                'message' => 'Response updated successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to update response'
            ]);
        }
    }

    public function delete()
    {
        $id = $this->input->post('id');

        if ($this->response_model->delete_response($id)) {
            echo json_encode([
                'success' => true,
                'message' => 'Response deleted successfully'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Failed to delete response'
            ]);
        }
    }
}
