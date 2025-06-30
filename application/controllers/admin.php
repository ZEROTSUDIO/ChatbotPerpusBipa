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
        $this->load->model('m_account');
        $this->load->model('response_model');
        $this->load->model('user_model');
        $this->load->model('chat_model');
        //$this->load->model('statistic_model');
        //$this->load->model('user_detail_model');
        $this->load->library('pagination');
        if (!$this->session->userdata('status') || $this->session->userdata('status') !== 'telah_login') {
            redirect('auth/login?alert=belum_login');
        }
        if ($this->session->userdata('level') !== '1') {
            redirect('chat?alert=forbidden');
        }
    }

    private function render($view, $data = [])
    {
        $user_id = $this->session->userdata('id');
        $data['user'] = $this->m_account->getUserById($user_id);
        $this->load->view('admin/header', $data);
        $this->load->view('admin/sidebar', $data);
        $this->load->view($view, $data);
        $this->load->view('admin/footer', $data);
    }

    public function index()
    {
        $this->render('admin/dashboard');
    }

    public function dashboard()
    {
        $data['the_stats'] = $this->chat_model->get_stats();

        $data['total_users'] = $this->chat_model->get_users();

        // Ambil history chats
        $data['chat_history'] = $this->chat_model->get_chat_history();

        // Ambil detail chats dengan confidence score
        $data['chat_details'] = $this->chat_model->get_chat_details();

        // Ambil data untuk chart OOD vs Non-OOD
        $data['ood_stats'] = $this->chat_model->get_the_ood_stats();

        // Ambil daftar intent untuk filter
        $data['intents'] = $this->chat_model->get_intents();                

        $data['title'] = 'Dashboard';
        $this->render('admin/dashboard', $data);
    }

    public function users()
    {
        $data['title'] = 'Users Management';

        // In real application, you would load users from database

        $data['users'] = $this->user_model->get_all_users();
        $this->render('admin/users', $data);
        //$this->render('admin/users', $data);     
    }

    public function chats()
    {
        $allSuggestions = [
            "halo selamat pagi",
            "Woigh kocak besok minggu tutup ga perpusnya?",
            "Permisi, perpusnya buka jam berapa ya?",
            "Apa aja aturanye?",
            "bagaimana cara menjadi anggota?",
            "Assalamualaikum, saya ingin pake!",
            "saya ingin pinjam buku",
            "Bisa carikan saya buku?",
            "Apa saja fasilitas disini?",
            "Bagaimana cara meminjam buku?"
        ];

        // Shuffle and pick 3
        shuffle($allSuggestions);
        $randomSuggestions = array_slice($allSuggestions, 0, 3);

        $user_id = $this->session->userdata('id');

        // Ambil data pengguna dari tabel users
        $user = $this->m_account->getUserById($user_id);
        $data = [
            'suggestions' => $randomSuggestions,
            'active_controller' => 'chat',
            'chats' => $this->chat_model->getChatHistoryByUser('chats', $user_id), // Gunakan tabel 'chats2',
            'user' => $user
        ];


        $this->render('admin/chats', $data);
    }

    public function intents()
    {
        $data['responses'] = $this->response_model->get_all_responses();
        $data['title'] = 'Intent Responses';

        // In real application, you would load intents from database
        // $this->load->model('Intent_model');
        // $data['intents'] = $this->Intent_model->get_all_intents();        
        $this->render('admin/intents', $data);
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

    public function get_user()
    {
        $id = $this->input->post('id');
        $user = $this->user_model->get_user_by_id($id);

        if ($user) {
            echo json_encode([
                'success' => true,
                'data' => $user
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Response not found'
            ]);
        }
    }

    public function create_user()
    {
        $name = $this->input->post('nama');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $level = $this->input->post('level');
        $confirm = $this->input->post('confirm');

        if ($password !== $confirm) {
            echo json_encode(['success' => false, 'message' => 'Password dan konfirmasi tidak cocok.']);
            return;
        }

        // Cek apakah email sudah digunakan
        if ($this->user_model->email_exists($email)) {
            echo json_encode(['success' => false, 'message' => 'Email sudah digunakan oleh pengguna lain.']);
            return;
        }

        $data = [
            'nama' => $name,
            'email' => $email,
            'password' => $password,
            'level' => $level,
            'date' => date('Y-m-d H:i:s')
        ];

        $this->user_model->create_user($data);
        echo json_encode(['success' => true, 'message' => 'User berhasil ditambahkan.']);
    }

    // Update existing user
    public function update_user()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('nama');
        $email = $this->input->post('email');
        $password = $this->input->post('password');
        $level = $this->input->post('level');
        $confirm = $this->input->post('confirm');

        // Cek apakah email berubah dan sudah dipakai user lain
        if ($this->user_model->email_exists($email, $id)) {
            echo json_encode(['success' => false, 'message' => 'Email sudah digunakan oleh pengguna lain.']);
            return;
        }

        $data = [
            'nama' => $name,
            'email' => $email,
            'level' => $level
        ];

        if (!empty($password)) {
            if ($password !== $confirm) {
                echo json_encode(['success' => false, 'message' => 'Password dan konfirmasi tidak cocok.']);
                return;
            }
            $data['password'] = $password;
        }

        $this->user_model->update_user($id, $data);
        echo json_encode(['success' => true, 'message' => 'User berhasil diperbarui.']);
    }

    // Delete user
    public function delete_user()
    {
        $id = $this->input->post('id');
        $this->user_model->delete_user($id);
        echo json_encode(['success' => true, 'message' => 'User berhasil dihapus.']);
    }

    public function user_detail($user_id)
    {
        // Validasi user_id
        if (!is_numeric($user_id)) {
            show_404();
        }

        // Ambil data user
        $data['user'] = $this->user_model->get_user_by_id($user_id);
        if (!$data['user']) {
            show_404();
        }

        // Ambil data statistik user
        $data['user_stats'] = $this->chat_model->get_user_stats($user_id);

        // Ambil history chats
        $data['chat_history'] = $this->chat_model->get_user_chat_history($user_id);

        // Ambil detail chats dengan confidence score
        $data['chat_details'] = $this->chat_model->get_user_chat_details($user_id);

        // Ambil data untuk chart OOD vs Non-OOD
        $data['ood_stats'] = $this->chat_model->get_ood_stats($user_id);

        // Ambil daftar intent untuk filter
        $data['intents'] = $this->chat_model->get_user_intents($user_id);

        $data['user_id'] = $user_id;
        $data['title'] = 'Detail Pengguna - ' . $data['user']->nama;

        $this->render('admin/user_detail', $data);
    }

    // AJAX endpoint untuk filter berdasarkan intent
    public function get_chat_details_by_intent()
    {
        $user_id = $this->input->post('user_id');
        $intent = $this->input->post('intent');

        $chat_details = $this->chat_model->get_user_chat_details($user_id, $intent);

        echo json_encode($chat_details);
        log_message('error', 'POST user_id: ' . $this->input->post('user_id'));
        log_message('error', 'POST intent: ' . $this->input->post('intent'));
    }
    public function get_chat_details_by_intent2()
    {
        
        $intent = $this->input->post('intent');

        $chat_details = $this->chat_model->get_chat_details($intent);

        echo json_encode($chat_details);        
        log_message('error', 'POST intent: ' . $this->input->post('intent'));
    }
}
