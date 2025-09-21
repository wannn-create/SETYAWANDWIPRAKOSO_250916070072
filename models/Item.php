<?php
class Item {
    private $conn; private $table = "item";
    public $id_item, $nama_item, $harga, $stok;
    public function __construct($db){ $this->conn = $db; }
    public function readAll(){ $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id_item DESC"); $stmt->execute(); return $stmt; }
    public function readOne(){ $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id_item=:id LIMIT 1"); $stmt->bindParam(":id",$this->id_item); $stmt->execute(); return $stmt->fetch(PDO::FETCH_ASSOC); }
    public function create(){ $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nama_item, harga, stok) VALUES (:nama,:harga,:stok)"); $stmt->bindParam(":nama",$this->nama_item); $stmt->bindParam(":harga",$this->harga); $stmt->bindParam(":stok",$this->stok); return $stmt->execute(); }
    public function update(){ $stmt = $this->conn->prepare("UPDATE {$this->table} SET nama_item=:nama, harga=:harga, stok=:stok WHERE id_item=:id"); $stmt->bindParam(":nama",$this->nama_item); $stmt->bindParam(":harga",$this->harga); $stmt->bindParam(":stok",$this->stok); $stmt->bindParam(":id",$this->id_item); return $stmt->execute(); }
    public function delete(){ $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_item=:id"); $stmt->bindParam(":id",$this->id_item); return $stmt->execute(); }
}
?>
