<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Analytics_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Intent Performance Dashboard
    public function get_intent_performance()
    {
        $query = $this->db->query("
            SELECT 
                cd.intent,
                COUNT(*) as total_occurrences,
                ROUND(AVG(cd.confident_score), 3) as avg_confidence_score,
                ROUND(AVG(cd.energy), 3) as avg_energy,
                ROUND((SUM(CASE WHEN cd.ood = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as ood_percentage,
                ROUND((SUM(CASE WHEN cd.confident_score >= 0.7 THEN 1 ELSE 0 END) * 100.0 / COUNT(*)), 2) as success_rate
            FROM chat_detail cd
            WHERE cd.intent IS NOT NULL
            GROUP BY cd.intent
            ORDER BY total_occurrences DESC
        ");

        return $query->result_array();
    }

    // Chat Details dengan join ke users dan chats
    public function get_chat_details($limit, $offset, $intent_filter = null)
    {
        $this->db->select('
            cd.id,
            cd.user_id,
            cd.chat_id,
            cd.intent,
            cd.confident_score,
            cd.energy,
            cd.ood,
            cd.timestamp,
            u.nama as user_name,
            c.user_message,
            c.bot_response
        ');
        $this->db->from('chat_detail cd');
        $this->db->join('users u', 'cd.user_id = u.id', 'left');
        $this->db->join('chats c', 'cd.chat_id = c.id', 'left');

        if ($intent_filter) {
            $this->db->where('cd.intent', $intent_filter);
        }

        $this->db->order_by('cd.timestamp', 'DESC');
        $this->db->limit($limit, $offset);

        $query = $this->db->get();
        return $query->result_array();
    }

    // Count untuk pagination
    public function count_chat_details($intent_filter = null)
    {
        $this->db->from('chat_detail cd');

        if ($intent_filter) {
            $this->db->where('cd.intent', $intent_filter);
        }

        return $this->db->count_all_results();
    }

    // Class Probabilities berdasarkan prediction_id (chat_id)

    public function get_class_probabilities($chat_id)
    {
        $this->db->select('intent_class, probability');
        $this->db->from('class_probabilities');
        $this->db->where('prediction_id', $chat_id);
        $this->db->order_by('probability', 'DESC');

        $query = $this->db->get();
        return array_map(function ($row) {
            $row['probability'] = (float) $row['probability'];
            return $row;
        }, $query->result_array());
        log_message('debug', json_encode($query->result_array()));

    }


    // Prediction Analysis - Comparison actual vs predicted
    public function get_prediction_analysis()
    {
        $query = $this->db->query("
            SELECT 
                cd.intent as actual_intent,
                cp.intent_class as predicted_intent,
                cp.probability as prediction_confidence,
                cd.confident_score,
                cd.energy,
                cd.ood,
                CASE 
                    WHEN cd.intent = cp.intent_class THEN 'Correct'
                    ELSE 'Incorrect'
                END as prediction_result
            FROM chat_detail cd
            JOIN class_probabilities cp ON cd.id = cp.prediction_id
            WHERE cp.probability = (
                SELECT MAX(cp2.probability) 
                FROM class_probabilities cp2 
                WHERE cp2.prediction_id = cp.prediction_id
            )
            ORDER BY cd.timestamp DESC
        ");

        return $query->result_array();
    }

    // Confusion Matrix
    public function get_confusion_matrix()
    {
        $query = $this->db->query("
            SELECT 
                cd.intent as actual,
                cp.intent_class as predicted,
                COUNT(*) as count
            FROM chat_detail cd
            JOIN class_probabilities cp ON cd.id = cp.prediction_id
            WHERE cp.probability = (
                SELECT MAX(cp2.probability) 
                FROM class_probabilities cp2 
                WHERE cp2.prediction_id = cp.prediction_id
            )
            GROUP BY cd.intent, cp.intent_class
            ORDER BY cd.intent, cp.intent_class
        ");

        $results = $query->result_array();

        // Transform ke format matrix
        $matrix = [];
        $intents = [];

        // Collect all unique intents
        foreach ($results as $row) {
            if (!in_array($row['actual'], $intents)) {
                $intents[] = $row['actual'];
            }
            if (!in_array($row['predicted'], $intents)) {
                $intents[] = $row['predicted'];
            }
        }

        // Initialize matrix
        foreach ($intents as $actual) {
            foreach ($intents as $predicted) {
                $matrix[$actual][$predicted] = 0;
            }
        }

        // Fill matrix with counts
        foreach ($results as $row) {
            $matrix[$row['actual']][$row['predicted']] = (int)$row['count'];
        }

        return [
            'matrix' => $matrix,
            'intents' => $intents
        ];
    }

    // Model Accuracy Metrics
    public function get_accuracy_metrics()
    {
        $query = $this->db->query("
            SELECT 
                COUNT(*) as total_predictions,
                SUM(CASE WHEN cd.intent = cp.intent_class THEN 1 ELSE 0 END) as correct_predictions,
                ROUND(SUM(CASE WHEN cd.intent = cp.intent_class THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as overall_accuracy,
                ROUND(AVG(cd.confident_score), 3) as avg_confidence_score,
                ROUND(AVG(cp.probability), 3) as avg_prediction_probability,
                SUM(CASE WHEN cd.ood = 1 THEN 1 ELSE 0 END) as ood_count,
                ROUND(SUM(CASE WHEN cd.ood = 1 THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 2) as ood_percentage
            FROM chat_detail cd
            JOIN class_probabilities cp ON cd.id = cp.prediction_id
            WHERE cp.probability = (
                SELECT MAX(cp2.probability)
                FROM class_probabilities cp2 
                WHERE cp2.prediction_id = cp.prediction_id
            )

        ");

        $overall_metrics = $query->row_array();

        // Per-intent accuracy
        $intent_query = $this->db->query("
            SELECT 
                cd.intent,
                COUNT(*) as total,
                SUM(CASE WHEN cd.intent = cp.intent_class THEN 1 ELSE 0 END) as correct,
                ROUND(
                    SUM(CASE WHEN cd.intent = cp.intent_class THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 
                    2
                ) as accuracy,
                ROUND(AVG(cd.confident_score), 3) as avg_confidence,
                ROUND(AVG(cp.probability), 3) as avg_prediction_prob
            FROM chat_detail cd
            JOIN class_probabilities cp ON cd.id = cp.prediction_id
            WHERE cp.probability = (
                SELECT MAX(cp2.probability) 
                FROM class_probabilities cp2 
                WHERE cp2.prediction_id = cp.prediction_id
            )
            GROUP BY cd.intent
            ORDER BY accuracy DESC
        ");

        $per_intent_metrics = $intent_query->result_array();

        return [
            'overall' => $overall_metrics,
            'per_intent' => $per_intent_metrics
        ];
    }

    // Get unique intents for filter
    public function get_unique_intents()
    {
        $this->db->select('DISTINCT intent');
        $this->db->from('chat_detail');
        $this->db->where('intent IS NOT NULL');
        $this->db->order_by('intent');

        $query = $this->db->get();
        return array_column($query->result_array(), 'intent');
    }
}
