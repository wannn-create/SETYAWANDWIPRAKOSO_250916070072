<?php
class Petugas {
    private $conn; 
    private $table = "petugas";

    public $id_petugas;
    public $username;
    public $password;
    public $nama_petugas;
    public $level;

    public function __construct($db){
        $this->conn = $db;
    }

    public function readAll(){
        $stmt = $this->conn->prepare("SELECT id_petugas, username, nama_petugas, level FROM {$this->table} ORDER BY id_petugas DESC");
        $stmt->execute();
        return $stmt;
    }

    public function readOne(){
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id_petugas=:id LIMIT 1");
        $stmt->bindParam(":id",$this->id_petugas);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(){
        // Menyimpan password sesuai permintaan (plain text)
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (username, password, nama_petugas, level) VALUES (:username, :password, :nama, :level)");
        $stmt->bindParam(":username",$this->username);
        $stmt->bindParam(":password",$this->password);
        $stmt->bindParam(":nama",$this->nama_petugas);
        $stmt->bindParam(":level",$this->level);
        return $stmt->execute();
    }

    public function update(){
        if(!empty($this->password)){
            // update termasuk password (plain)
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET username=:username, password=:password, nama_petugas=:nama, level=:level WHERE id_petugas=:id");
            $stmt->bindParam(":password",$this->password);
        } else {
            // update tanpa mengganti password
            $stmt = $this->conn->prepare("UPDATE {$this->table} SET username=:username, nama_petugas=:nama, level=:level WHERE id_petugas=:id");
        }
        $stmt->bindParam(":username",$this->username);
        $stmt->bindParam(":nama",$this->nama_petugas);
        $stmt->bindParam(":level",$this->level);
        $stmt->bindParam(":id",$this->id_petugas);
        return $stmt->execute();
    }

    public function delete(){
        try {
            // Cek dulu apakah kolom id_petugas ada di tabel transaksi
            $checkCol = $this->conn->prepare("
                SELECT COUNT(*) 
                FROM information_schema.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                  AND TABLE_NAME = 'transaksi' 
                  AND COLUMN_NAME = 'id_petugas'
            ");
            $checkCol->execute();
            $hasCol = (int) $checkCol->fetchColumn();

            if ($hasCol > 0) {
                // Jika kolom ada, lakukan pengecekan apakah petugas dipakai di transaksi
                $cek = $this->conn->prepare("SELECT COUNT(*) FROM transaksi WHERE id_petugas = :id");
                $cek->bindParam(":id", $this->id_petugas);
                $cek->execute();

                if ($cek->fetchColumn() > 0) {
                    // Masih dipakai -> jangan hapus
                    return false;
                }
            }

            // Aman untuk dihapus (atau kolom tidak ada)
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_petugas = :id");
            $stmt->bindParam(":id", $this->id_petugas);
            return $stmt->execute();

        } catch (Exception $e) {
            // log supaya bisa ditelusuri jika terjadi error lain
            error_log("Petugas::delete error: " . $e->getMessage());
            return false;
        }
    }
}
?>
