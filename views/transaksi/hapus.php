<?php
include "../../config/Database.php";
include "../../models/Transaksi.php";

$db = (new Database())->getConnection();
$transaksi = new Transaksi($db);

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    if ($transaksi->delete($id)) {
        echo "<script>alert('Transaksi berhasil dihapus');window.location='index.php';</script>";
    } else {
        echo "<script>alert('Transaksi gagal dihapus');window.location='index.php';</script>";
    }
}
?>
