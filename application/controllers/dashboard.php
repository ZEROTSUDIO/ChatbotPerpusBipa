
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Statistics_model');
        // Add authentication check here if needed
        // $this->check_auth();
    }

    public function index() {
        $data['title'] = 'Dashboard';
        $this->load->view('dashboard/index', $data);
    }

    public function statistics() {
        $data['title'] = 'Chat Statistics';
        $data['page_title'] = 'Statistik Chatbot';
        
        // Load initial data for server-side rendering (optional)
        $data['initial_stats'] = $this->Statistics_model->get_dashboard_overview();
        
        $this->load->view('dashboard/statistics', $data);
    }

    public function export_stats() {
        $format = $this->input->get('format') ?: 'csv';
        $period = $this->input->get('period') ?: 30;
        
        try {
            $data = $this->Statistics_model->get_export_data($period);
            
            if ($format === 'csv') {
                $this->export_csv($data);
            } elseif ($format === 'excel') {
                $this->export_excel($data);
            }
        } catch (Exception $e) {
            show_error('Error exporting data: ' . $e->getMessage());
        }
    }

    private function export_csv($data) {
        $filename = 'chat_statistics_' . date('Y-m-d') . '.csv';
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // CSV headers
        fputcsv($output, ['Date', 'Total Chats', 'Active Users', 'Avg Confidence', 'OOD Rate']);
        
        foreach ($data as $row) {
            fputcsv($output, $row);
        }
        
        fclose($output);
    }

    private function export_excel($data) {
        // Implementation for Excel export
        // You might want to use a library like PhpSpreadsheet
        show_error('Excel export not implemented yet');
    }
}