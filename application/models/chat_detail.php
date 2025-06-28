<?php
class Chat_detail_model extends CI_Model
{

    public function get_intent_distribution($limit = 10)
    {
        $this->db->select('intent, COUNT(*) as count');
        $this->db->from('chat_detail');
        $this->db->where('intent IS NOT NULL');
        $this->db->group_by('intent');
        $this->db->order_by('count', 'DESC');
        $this->db->limit($limit);

        $query = $this->db->get();
        $results = $query->result_array();

        $labels = [];
        $data = [];

        foreach ($results as $row) {
            $labels[] = $row['intent'];
            $data[] = (int)$row['count'];
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    public function get_confidence_distribution()
    {
        $sql = "SELECT 
                    CASE 
                        WHEN confident_score BETWEEN 0 AND 0.2 THEN '0-20%'
                        WHEN confident_score BETWEEN 0.21 AND 0.4 THEN '21-40%'
                        WHEN confident_score BETWEEN 0.41 AND 0.6 THEN '41-60%'
                        WHEN confident_score BETWEEN 0.61 AND 0.8 THEN '61-80%'
                        ELSE '81-100%'
                    END as confidence_range,
                    COUNT(*) as count
                FROM chat_detail 
                WHERE confident_score IS NOT NULL
                GROUP BY confidence_range
                ORDER BY confident_score";

        $query = $this->db->query($sql);
        $results = $query->result_array();

        $data = [0, 0, 0, 0, 0]; // Initialize for 5 ranges
        $ranges = ['0-20%', '21-40%', '41-60%', '61-80%', '81-100%'];

        foreach ($results as $row) {
            $index = array_search($row['confidence_range'], $ranges);
            if ($index !== false) {
                $data[$index] = (int)$row['count'];
            }
        }

        return [
            'data' => $data
        ];
    }

    public function get_average_confidence($period = null)
    {
        $this->db->select('AVG(confident_score) as avg_confidence');
        $this->db->from('chat_detail');
        $this->db->where('confident_score IS NOT NULL');

        if ($period) {
            $this->db->where('timestamp >=', date('Y-m-d H:i:s', strtotime("-{$period} days")));
        }

        $query = $this->db->get();
        $result = $query->row();

        return $result ? $result->avg_confidence * 100 : 0;
    }

    public function get_ood_rate($period = null)
    {
        $this->db->select('(SUM(ood) / COUNT(*)) * 100 as ood_rate');
        $this->db->from('chat_detail');

        if ($period) {
            $this->db->where('timestamp >=', date('Y-m-d H:i:s', strtotime("-{$period} days")));
        }

        $query = $this->db->get();
        $result = $query->row();

        return $result ? $result->ood_rate : 0;
    }

    public function get_energy_confidence_correlation($limit = 100)
    {
        $this->db->select('energy, confident_score');
        $this->db->from('chat_detail');
        $this->db->where('energy IS NOT NULL');
        $this->db->where('confident_score IS NOT NULL');
        $this->db->order_by('RAND()');
        $this->db->limit($limit);

        $query = $this->db->get();
        $results = $query->result_array();

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'x' => (float)$row['energy'],
                'y' => (float)$row['confident_score'] * 100
            ];
        }

        return $data;
    }

    public function get_intent_performance_stats()
    {
        $sql = "SELECT 
                    cd.intent as name,
                    COUNT(*) as count,
                    AVG(cd.confident_score * 100) as avgConfidence,
                    AVG(cd.energy) as avgEnergy,
                    (SUM(cd.ood) / COUNT(*)) * 100 as oodRate,
                    (SELECT 
                        ((COUNT(*) - prev.count) / prev.count) * 100
                     FROM (
                        SELECT intent, COUNT(*) as count
                        FROM chat_detail 
                        WHERE timestamp >= DATE_SUB(NOW(), INTERVAL 60 DAY)
                        AND timestamp < DATE_SUB(NOW(), INTERVAL 30 DAY)
                        AND intent = cd.intent
                     ) prev
                    ) as trend
                FROM chat_detail cd
                WHERE cd.timestamp >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                AND cd.intent IS NOT NULL
                GROUP BY cd.intent
                ORDER BY count DESC";

        $query = $this->db->query($sql);
        $results = $query->result_array();

        foreach ($results as &$row) {
            $row['avgConfidence'] = (float)$row['avgConfidence'];
            $row['avgEnergy'] = (float)$row['avgEnergy'];
            $row['oodRate'] = (float)$row['oodRate'];
            $row['trend'] = (float)($row['trend'] ?: 0);
            $row['count'] = (int)$row['count'];
        }

        return $results;
    }

    public function get_confidence_trends($period = 30)
    {
        $sql = "SELECT 
                    DATE(timestamp) as date,
                    AVG(confident_score * 100) as avg_confidence
                FROM chat_detail 
                WHERE timestamp >= DATE_SUB(NOW(), INTERVAL ? DAY)
                AND confident_score IS NOT NULL
                GROUP BY DATE(timestamp)
                ORDER BY date ASC";

        $query = $this->db->query($sql, [$period]);
        return $query->result_array();
    }
}
