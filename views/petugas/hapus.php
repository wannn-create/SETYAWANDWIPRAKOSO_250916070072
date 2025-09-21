<?php
include "../../config/Database.php";
include "../../models/Petugas.php";

$db = (new Database())->getConnection();
$petugas = new Petugas($db);

if (isset($_GET['id'])) {
    $petugas->id_petugas = $_GET['id'];
    if ($petugas->delete()) {
        echo "<script>alert('Petugas berhasil dihapus');window.location='index.php';</script>";
    } else {
        echo "<script>alert('Petugas tidak bisa dihapus karena masih dipakai di transaksi!');window.location='index.php';</script>";
    }
}
?>
