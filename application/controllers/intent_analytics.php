<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Intent_analytics extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Analytics_model');
        $this->load->library('session');
    }

    public function index()
    {
        $data['title'] = 'Intent Analytics Dashboard';
        $this->load->view('new/header', $data);
        $this->load->view('new/dashboard');
        $this->load->view('new/footer');
    }

    // API untuk Intent Performance Dashboard
    public function get_intent_performance()
    {
        $performance_data = $this->Analytics_model->get_intent_performance();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($performance_data));
    }

    // API untuk Chat Detail dengan pagination
    public function get_chat_details()
    {
        $page = $this->input->get('page') ? (int)$this->input->get('page') : 1;
        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : 10;
        $intent_filter = $this->input->get('intent');

        $offset = ($page - 1) * $limit;

        $data = $this->Analytics_model->get_chat_details($limit, $offset, $intent_filter);
        $total = $this->Analytics_model->count_chat_details($intent_filter);

        $response = [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => ceil($total / $limit),
                'total_records' => $total,
                'per_page' => $limit
            ]
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }

    // API untuk Class Probabilities berdasarkan chat_id
    public function get_class_probabilities($chat_id = null)
    {
        if (!$chat_id) {
            show_error("Chat ID required", 400);
        }

        $probabilities = $this->Analytics_model->get_class_probabilities($chat_id);
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($probabilities));
    }


    // API untuk Prediction Analysis
    public function get_prediction_analysis()
    {
        $analysis = $this->Analytics_model->get_prediction_analysis();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($analysis));
    }

    // API untuk Confusion Matrix
    public function get_confusion_matrix()
    {
        $matrix = $this->Analytics_model->get_confusion_matrix();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($matrix));
    }

    // API untuk Model Accuracy Metrics
    public function get_accuracy_metrics()
    {
        $metrics = $this->Analytics_model->get_accuracy_metrics();

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($metrics));
    }

    // Export data ke CSV
    public function export_intent_performance()
    {
        $performance_data = $this->Analytics_model->get_intent_performance();

        $filename = 'intent_performance_' . date('Y-m-d_H-i-s') . '.csv';

        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Header CSV
        fputcsv($output, [
            'Intent',
            'Total Occurrences',
            'Avg Confidence Score',
            'Avg Energy',
            'OOD Percentage',
            'Success Rate'
        ]);

        // Data CSV
        foreach ($performance_data as $row) {
            fputcsv($output, [
                $row['intent'],
                $row['total_occurrences'],
                $row['avg_confidence_score'],
                $row['avg_energy'],
                $row['ood_percentage'],
                $row['success_rate']
            ]);
        }

        fclose($output);
    }
}
