<?php
include "../../config/Database.php";
include "../../models/Sales.php";

$db = (new Database())->getConnection();
$sales = new Sales($db);

if (isset($_GET['id'])) {
    $sales->id_sales = $_GET['id'];
    if ($sales->delete()) {
        echo "<script>alert('Sales berhasil dihapus');window.location='index.php';</script>";
    } else {
        echo "<script>alert('Sales tidak bisa dihapus karena masih dipakai di transaksi!');window.location='index.php';</script>";
    }
}
?>
