<?php
class Database {
    private $host = "localhost";        // host XAMPP
    private $db_name = "koperasi_db";   // nama database sesuai phpMyAdmin Anda
    private $username = "root";         // default user XAMPP
    private $password = "";             // default password kosong
    public $conn;

    // Fungsi koneksi
    public function getConnection() {
        $this->conn = null;
        try {
            // Gunakan PDO untuk koneksi
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );

            // Atur mode error PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Atur charset supaya support UTF-8
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Koneksi database gagal: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>
