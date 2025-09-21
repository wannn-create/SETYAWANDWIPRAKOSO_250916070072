<?php
class Customer {
    private $conn;
    private $table = "customer";

    public $id_customer;
    public $nama_customer;
    public $alamat;
    public $telepon;

    public function __construct($db) { $this->conn = $db; }

    function readAll() {
        $query = "SELECT * FROM {$this->table} ORDER BY id_customer DESC";
        return $this->conn->query($query);
    }
    function create() {
        $query = "INSERT INTO {$this->table} (nama_customer, alamat, telepon) VALUES (:nama, :alamat, :telepon)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama", $this->nama_customer);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":telepon", $this->telepon);
        return $stmt->execute();
    }
    function update() {
        $query = "UPDATE {$this->table} SET nama_customer=:nama, alamat=:alamat, telepon=:telepon WHERE id_customer=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nama", $this->nama_customer);
        $stmt->bindParam(":alamat", $this->alamat);
        $stmt->bindParam(":telepon", $this->telepon);
        $stmt->bindParam(":id", $this->id_customer);
        return $stmt->execute();
    }
    public function delete(){
    // cek apakah customer dipakai di transaksi
    $cek = $this->conn->prepare("SELECT COUNT(*) FROM transaksi WHERE id_customer=:id");
    $cek->bindParam(":id",$this->id_customer);
    $cek->execute();

    if ($cek->fetchColumn() > 0) {
        return false;
    }

    $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_customer=:id");
    $stmt->bindParam(":id",$this->id_customer);
    return $stmt->execute();
}

    function readOne() {
        $query = "SELECT * FROM {$this->table} WHERE id_customer=:id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $this->id_customer);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
