<?php
class Transaksi {
    private $conn;
    private $table = "transaksi";
    public $error_message = ""; // Tambahan untuk menyimpan pesan error

    public $id_transaksi;
    public $id_customer;
    public $id_sales;
    public $tanggal;

    public function __construct($db){
        $this->conn = $db;
    }

    // Ambil semua transaksi
    public function readAll(){
        $query = "SELECT t.*, c.nama_customer, s.nama_sales
                  FROM transaksi t
                  JOIN customer c ON t.id_customer=c.id_customer
                  JOIN sales s ON t.id_sales=s.id_sales
                  ORDER BY t.id_transaksi DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Detail transaksi (header)
    public function readOne($id){
        $query = "SELECT t.*, c.nama_customer, s.nama_sales
                  FROM transaksi t
                  JOIN customer c ON t.id_customer=c.id_customer
                  JOIN sales s ON t.id_sales=s.id_sales
                  WHERE t.id_transaksi=:id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Ambil detail item transaksi
    public function readDetail($id){
        $query = "SELECT td.*, i.nama_item, i.harga 
                  FROM transaksi_detail td
                  JOIN item i ON td.id_item=i.id_item
                  WHERE td.id_transaksi=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id",$id);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Tambah transaksi + detail
     * $items : array dari checkbox (id_item)
     * $qtys  : associative array qty[id_item] => jumlah
     *
     * returns true on success, false on failure
     */
    public function create($items, $qtys){
        try {
            // Reset error message
            $this->error_message = "";
            
            // Validasi input yang lebih lengkap
            if (empty($this->id_customer)) {
                throw new Exception("Customer harus dipilih.");
            }
            
            if (empty($this->id_sales)) {
                throw new Exception("Sales harus dipilih.");
            }
            
            if (!is_array($items) || count($items) == 0) {
                throw new Exception("Minimal pilih 1 item.");
            }

            $this->conn->beginTransaction();

            // Insert transaksi header - PERBAIKAN: gunakan bindParam yang konsisten
            $query = "INSERT INTO {$this->table} (id_customer, id_sales, tanggal) VALUES (:customer, :sales, :tanggal)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':customer', $this->id_customer);
            $stmt->bindParam(':sales', $this->id_sales);
            $stmt->bindParam(':tanggal', $this->tanggal);
            
            if (!$stmt->execute()) {
                throw new Exception("Gagal menyimpan header transaksi.");
            }
            
            $this->id_transaksi = $this->conn->lastInsertId();

            // Prepare statements
            $insertDetail = $this->conn->prepare("INSERT INTO transaksi_detail (id_transaksi, id_item, jumlah) VALUES (:tid, :item, :qty)");
            $selectStock  = $this->conn->prepare("SELECT stok FROM item WHERE id_item = :item FOR UPDATE");
            $updateStock  = $this->conn->prepare("UPDATE item SET stok = stok - :qty WHERE id_item = :item");

            // Loop tiap item yang dicentang
            foreach($items as $id_item) {
                $id_item = (int)$id_item;
                $qty = isset($qtys[$id_item]) ? (int)$qtys[$id_item] : 0;

                if ($qty <= 0) {
                    throw new Exception("Quantity untuk item ID {$id_item} tidak valid atau kosong.");
                }

                // Cek stok saat ini (lock row)
                $selectStock->bindParam(':item', $id_item);
                if (!$selectStock->execute()) {
                    throw new Exception("Gagal mengecek stok item ID {$id_item}.");
                }
                
                $row = $selectStock->fetch(PDO::FETCH_ASSOC);
                if (!$row) {
                    throw new Exception("Item ID {$id_item} tidak ditemukan.");
                }
                
                $stok_tersedia = (int)$row['stok'];
                if ($stok_tersedia < $qty) {
                    throw new Exception("Stok item ID {$id_item} tidak mencukupi. Tersedia: {$stok_tersedia}, diminta: {$qty}.");
                }

                // Insert detail
                $insertDetail->bindParam(':tid', $this->id_transaksi);
                $insertDetail->bindParam(':item', $id_item);
                $insertDetail->bindParam(':qty', $qty);
                
                if (!$insertDetail->execute()) {
                    throw new Exception("Gagal menyimpan detail transaksi untuk item ID {$id_item}.");
                }

                // Kurangi stok
                $updateStock->bindParam(':qty', $qty);
                $updateStock->bindParam(':item', $id_item);
                
                if (!$updateStock->execute()) {
                    throw new Exception("Gagal mengupdate stok item ID {$id_item}.");
                }
            }

            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            // Rollback dan simpan pesan error
            $this->conn->rollBack();
            $this->error_message = $e->getMessage();
            
            // Optional: log error ke file
            error_log("Transaksi Error: " . $e->getMessage());
            
            return false;
        }
    }

    // Hapus transaksi + detail
    public function delete($id){
        try {
            $this->error_message = "";
            $this->conn->beginTransaction();

            // Restore stok sebelum hapus detail (ambil semua detail)
            $q = $this->conn->prepare("SELECT id_item, jumlah FROM transaksi_detail WHERE id_transaksi = :id");
            $q->bindParam(':id', $id);
            $q->execute();
            $rows = $q->fetchAll(PDO::FETCH_ASSOC);
            
            $restore = $this->conn->prepare("UPDATE item SET stok = stok + :qty WHERE id_item = :item");
            foreach($rows as $r) {
                $restore->bindParam(':qty', $r['jumlah']);
                $restore->bindParam(':item', $r['id_item']);
                $restore->execute();
            }

            // Hapus detail terlebih dahulu
            $deleteDetail = $this->conn->prepare("DELETE FROM transaksi_detail WHERE id_transaksi=:id");
            $deleteDetail->bindParam(":id", $id);
            $deleteDetail->execute();
            
            // Hapus header
            $deleteHeader = $this->conn->prepare("DELETE FROM {$this->table} WHERE id_transaksi=:id");
            $deleteHeader->bindParam(":id", $id);
            $deleteHeader->execute();

            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollBack();
            $this->error_message = $e->getMessage();
            return false;
        }
    }
}
?>