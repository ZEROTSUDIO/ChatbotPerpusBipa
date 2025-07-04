<?php
//test
defined('BASEPATH') or exit('No direct script access allowed');
function get_bot_response($intent, $data = null)
{
    $CI = &get_instance();
    $CI->load->model('response_model');

    $response_entry = $CI->response_model->get_response_by_intent($intent);

    if (!$response_entry) {
        $response_entry = $CI->response_model->get_response_by_intent('unknown');
    }

    // ===== 👇 PLACEHOLDER REPLACEMENT (like {{nama}}) =========
    $user_id = $CI->session->userdata('id');
    $user = $CI->m_account->getUserById($user_id); // Use your own model
    $username = $user ? $user->nama : 'Pengunjung';

    // You can expand this if you want {{email}}, {{nim}}, etc.
    $placeholders = [
        '{{nama}}' => $username,
        '{{today}}' => date('d F Y'),
    ];

    // Replace placeholders in response
    $response_text = strtr($response_entry->response, $placeholders);

    $result = ['response' => $response_text];

    // ===== ✅ HANDLE NEXT ACTIONS if intent requires it
    $intent_next_action_map = [
        'cari_buku' => 'wait_book_recommendation',
        'confirm' => 'confirmation'
    ];

    if (isset($intent_next_action_map[$intent])) {
        if ($intent === 'confirm') {
            $waitConfirmation = $data['wait_confirmation'] ?? false;
            if ($waitConfirmation) {
				$result = ['response' => '<p><strong>?? Pencarian Buku:</strong><br>' .
                            'Silakan masukkan kembali judul atau deskripsi buku Anda.</p>'];
                $result['next_action'] = $intent_next_action_map[$intent];
            }
        } else {					
            $result['next_action'] = $intent_next_action_map[$intent];
        }
    }

    return $result;
}
