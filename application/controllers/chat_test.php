<?php
defined('BASEPATH') or exit('No direct script access allowed');
class chat_test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('responses');
        $this->load->model('Chat_model', 'chatModel'); // Memuat model Chat_model
        $this->load->model('m_account');
        if (!$this->session->userdata('status') || $this->session->userdata('status') !== 'telah_login') {
            redirect('auth/login?alert=belum_login');
        }
    }

    public function index()
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
            'active_controller' => 'chat_test',
            'chats' => $this->chatModel->getChatHistoryByUser('chats', $user_id), // Gunakan tabel 'chats2',
            'user' => $user
        ];
        $this->load->view('chatForm', $data);
    }

    public function send()
    {
        if (!$this->input->is_ajax_request()) {
            echo json_encode(['response' => 'Invalid request']);
            return;
        }

        $user_id = $this->session->userdata('id');
        log_message('error', 'AJAX TEST - Session user_id: ' . print_r($user_id, true));

        $data = json_decode(file_get_contents('php://input'), true);
        $message = trim($data['message'] ?? '');

        $result = [
            'response' => '',
            'next_action' => null
        ];

        // Only accept message format like /greeting or /fasilitas
        if (!preg_match('/^\/([a-zA-Z_]+)$/', $message, $matches)) {
            $result['response'] = 'Perintah tidak dikenali. Gunakan format seperti /greeting atau /fasilitas.';
            echo json_encode($result);
            return;
        }

        $intent = strtolower($matches[1]); // extract "greeting" from "/greeting"
        log_message('error', "Test Mode: Simulating intent = $intent");

        // Call your existing rule-based function
        $bot_output = get_bot_response($intent, $data);
        $response_text = $bot_output['response'];
        $next_action = $bot_output['next_action'] ?? null;

        $result['response'] = $response_text;
        if ($next_action) {
            $result['next_action'] = $next_action;
        }

        // Save to main chat table
        $chatData = [
            'user'         => $user_id,
            'user_message' => $message,
            'bot_response' => $response_text
        ];
        $this->db->db_debug = TRUE;
        $this->chatModel->saveChat('chats', $chatData);

        // Note: You intentionally skip saving into chat_detail and class_probabilities

        echo json_encode($result);
    }


    public function sendbook()
    {
        if (!$this->input->is_ajax_request()) {
            show_error('Direct access not allowed', 403);
            return;
        }

        $user_id = $this->session->userdata('id');
        $json_data = file_get_contents('php://input');
        $post_data = json_decode($json_data, true);
        $message = isset($post_data['message']) ? trim($post_data['message']) : '';

        if (empty($message)) {
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'No message provided']));
            return;
        }

        $api_data = [
            'query' => $message,
            'top_n' => 10,
            'threshold' => 0.4
        ];

        $ch = curl_init('http://localhost:5000/api/recommend'); // perhatikan endpoint-nya
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($api_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            log_message('error', 'cURL Error: ' . $curl_error);
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(['error' => 'Failed to connect to recommendation service', 'details' => $curl_error]));
            return;
        }

        $api_response = json_decode($response, true);

        if ($response === false || $status_code >= 500) {
            // Jika tidak bisa konek sama sekali atau error server
            $error = isset($api_response['error']) ? $api_response['error'] : 'Unknown error';
            log_message('error', 'API Error: ' . $error . ' (Status: ' . $status_code . ')');
            $this->output
                ->set_content_type('application/json')
                ->set_status_header(500)
                ->set_output(json_encode(['error' => 'Recommendation service error', 'details' => $error]));
            return;
        }

        // Kalau sukses walaupun recommendations kosong, tetap lanjut
        $high = $api_response['high_recommendations'] ?? [];
        $low = $api_response['low_recommendations'] ?? [];

        $formatted_response = $this->format_recommendations($high, $low);

        $data = [
            'user' => $user_id,
            'user_message' => $message,
            'bot_response' => $formatted_response
        ];

        $this->chatModel->saveChat('chats', $data);

        $this->output->set_content_type('application/json')->set_output(json_encode(['response' => $formatted_response]));
    }

    /**
     * Format book recommendations into HTML for display
     */
    private function format_recommendations($high, $low)
    {
        $output = "<strong>Buku yang paling relevan untuk Anda:</strong><br><br>";

        foreach ($high as $index => $book) {
            $relevance_percentage = $book['relevance_score'] * 100;
            $year = $book['year'] ? $book['year'] : 'Tahun tidak diketahui';

            $output .= "<div class='book-recommendation'>";
            $output .= "<strong>" . ($index + 1) . ". " . htmlspecialchars($book['title']) . "</strong><br>";
            $output .= "Penulis: " . htmlspecialchars($book['author']) . "<br>";
            $output .= "Kategori: " . htmlspecialchars($book['category']) . "<br>";
            $output .= "Tahun: " . htmlspecialchars($year) . "<br>";
            $output .= "<p><em>Deskripsi:</em> " . nl2br(htmlspecialchars($book['description'])) . "</p>";
            $output .= "Relevansi: " . number_format($relevance_percentage, 0) . "%<br>";
            $output .= "</div><br>";
        }

        if (!empty($low)) {
            $output .= "<details><summary>📚 Lihat rekomendasi tambahan</summary><br>";

            foreach ($low as $index => $book) {
                $relevance_percentage = $book['relevance_score'] * 100;
                $year = $book['year'] ? $book['year'] : 'Tahun tidak diketahui';

                $output .= "<div class='book-recommendation'>";
                $output .= "<strong>" . ($index + 1 + count($high)) . ". " . htmlspecialchars($book['title']) . "</strong><br>";
                $output .= "Penulis: " . htmlspecialchars($book['author']) . "<br>";
                $output .= "Kategori: " . htmlspecialchars($book['category']) . "<br>";
                $output .= "Tahun: " . htmlspecialchars($year) . "<br>";
                $output .= "<p><em>Deskripsi:</em> " . nl2br(htmlspecialchars($book['description'])) . "</p>";
                $output .= "Relevansi: " . number_format($relevance_percentage, 0) . "%<br>";
                $output .= "</div><br>";
            }

            $output .= "</details>";
        }

        $output .= "<p>Apakah Anda ingin rekomendasi buku lainnya? Silakan ketik pertanyaan atau topik yang Anda minati.</p>";

        return $output;
    }


    public function clear()
    {
        $userId = $this->session->userdata('id'); // or adjust if your session key is different
        $this->chatModel->clearChatHistory('chats', $userId);
        redirect('chat');
    }
}
