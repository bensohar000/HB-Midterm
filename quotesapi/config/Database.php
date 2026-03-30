<?php

class Database {
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'pgsql:host=' . getenv('DB_HOST') .
                   ';port=' . getenv('DB_PORT') .
                   ';dbname=' . getenv('DB_NAME');

            $this->conn = new PDO(
                $dsn,
                getenv('DB_USER'),
                getenv('DB_PASS')
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo json_encode(['message' => 'Connection Error: ' . $e->getMessage()]);
        }

        return $this->conn;
    }
}

        return $this->conn;
    }
}

