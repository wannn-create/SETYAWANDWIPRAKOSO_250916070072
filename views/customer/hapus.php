<?php
include "../../config/Database.php";
include "../../models/Customer.php";

$db = (new Database())->getConnection();
$customer = new Customer($db);

if (isset($_GET['id'])) {
    $customer->id_customer = $_GET['id'];
    if ($customer->delete()) {
        echo "<script>alert('Customer berhasil dihapus');window.location='index.php';</script>";
    } else {
        echo "<script>alert('Customer tidak bisa dihapus karena masih dipakai di transaksi!');window.location='index.php';</script>";
    }
}
?>
