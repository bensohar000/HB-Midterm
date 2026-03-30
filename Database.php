<?php

class Database {
    private $conn;

    public function connect() {
        $this->conn = null;

        try {
            $dsn = 'pgsql:host=' . getenv('dpg-d75di6450q8c73av2l1g-a.oregon-postgres.render.com') .
                   ';port=' . getenv('5432') .
                   ';dbname=' . getenv('quotesdb_ed9i');

            $this->conn = new PDO(
                $dsn,
                getenv('root'),
                getenv('R6aZOrjHM1TO0VoyZPkuynl5DEWsNh9B')
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
