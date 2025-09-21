<?php
class Sales {
    private $conn; private $table = "sales";
    public $id_sales, $nama_sales, $telepon;
    public function __construct($db){ $this->conn = $db; }
    public function readAll(){ $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id_sales DESC"); $stmt->execute(); return $stmt; }
    public function readOne(){ $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id_sales=:id LIMIT 1"); $stmt->bindParam(":id",$this->id_sales); $stmt->execute(); return $stmt->fetch(PDO::FETCH_ASSOC); }
    public function create(){ $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nama_sales, telepon) VALUES (:nama,:telepon)"); $stmt->bindParam(":nama",$this->nama_sales); $stmt->bindParam(":telepon",$this->telepon); return $stmt->execute(); }
    public function update(){ $stmt = $this->conn->prepare("UPDATE {$this->table} SET nama_sales=:nama, telepon=:telepon WHERE id_sales=:id"); $stmt->bindParam(":nama",$this->nama_sales); $stmt->bindParam(":telepon",$this->telepon); $stmt->bindParam(":id",$this->id_sales); return $stmt->execute();}
    public function delete(){
    // cek apakah sales dipakai di transaksi
    $cek = $this->conn->prepare("SELECT COUNT(*) FROM transaksi WHERE id_sales=:id");
    $cek->bindParam(":id",$this->id_sales);
    $cek->execute();

    if ($cek->fetchColumn() > 0) {
        return false; // masih dipakai
    }

    $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_sales=:id");
    $stmt->bindParam(":id",$this->id_sales);
    return $stmt->execute();
}

}
?>
