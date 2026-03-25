<?php

class WinnerFetchController
{
    private $conn;

    public function __construct($dbConfig)
    {
        $this->conn = new mysqli(
            $dbConfig['host'],
            $dbConfig['username'],
            $dbConfig['password'],
            $dbConfig['database'],
            (int)$dbConfig['port']
        );

        if ($this->conn->connect_error) {
            die("DB Connection failed: " . $this->conn->connect_error);
        }
        $this->conn->set_charset('utf8mb4');
    }

    public function getApprovedWinners($limit = 4)
    {
        try {
            $sql = "SELECT first_name, last_name, city, province, winner_photo_path 
                    FROM wp_winner_photo_release_submissions
                    WHERE status = 'approved'
                    ORDER BY created_at DESC
                    LIMIT ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $limit);
            $stmt->execute();

            $result = $stmt->get_result();
           
            if (!$result || $result->num_rows === 0) {
                return [];
            }
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
             
            return $data;
        } catch (\mysqli_sql_exception $e) {
            return []; // ✅ prevent crash
        }
    }
}