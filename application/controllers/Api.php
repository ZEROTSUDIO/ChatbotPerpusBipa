<?php
// ===============================================
// CONTROLLERS
// ===============================================

// File: application/controllers/Api.php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Chat_model');
        $this->load->model('User_model');
        $this->load->model('Chat_detail_model');
        $this->load->model('Statistics_model');
        $this->output->set_content_type('application/json');
    }

    public function stats_overview() {
        try {
            $data = $this->Statistics_model->get_dashboard_overview();
            $this->output->set_output(json_encode([
                'success' => true,
                'data' => $data
            ]));
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Error fetching overview data'
            ]));
        }
    }

    public function stats_charts() {
        try {
            $volume_period = $this->input->get('volume_period') ?: 7;
            
            $data = [
                'volume' => $this->Chat_model->get_chat_volume_by_period($volume_period),
                'intent' => $this->Chat_detail_model->get_intent_distribution(),
                'confidence' => $this->Chat_detail_model->get_confidence_distribution(),
                'energyConfidence' => $this->Chat_detail_model->get_energy_confidence_correlation(),
                'peakHours' => $this->Chat_model->get_peak_hours_data()
            ];
            
            $this->output->set_output(json_encode([
                'success' => true,
                'data' => $data
            ]));
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Error fetching charts data'
            ]));
        }
    }

    public function stats_intent_performance() {
        try {
            $data = $this->Chat_detail_model->get_intent_performance_stats();
            $this->output->set_output(json_encode([
                'success' => true,
                'data' => $data
            ]));
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Error fetching intent performance data'
            ]));
        }
    }

    public function stats_recent_activity() {
        try {
            $limit = $this->input->get('limit') ?: 10;
            $data = $this->Statistics_model->get_activity_timeline($limit);
            $this->output->set_output(json_encode([
                'success' => true,
                'data' => $data
            ]));
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Error fetching recent activity'
            ]));
        }
    }

    public function stats_volume() {
        try {
            $period = $this->input->get('period') ?: 7;
            $data = $this->Chat_model->get_chat_volume_by_period($period);
            $this->output->set_output(json_encode([
                'success' => true,
                'data' => $data
            ]));
        } catch (Exception $e) {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode([
                'success' => false,
                'message' => 'Error fetching volume data'
            ]));
        }
    }
}




