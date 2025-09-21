<?php
include "../../config/Database.php";
include "../../models/Item.php";

$db = (new Database())->getConnection();
$item = new Item($db);

if (isset($_GET['id'])) {
    $item->id_item = $_GET['id'];
    if ($item->delete()) {
        echo "<script>alert('Item berhasil dihapus');window.location='index.php';</script>";
    } else {
        echo "<script>alert('Item tidak bisa dihapus karena masih dipakai di transaksi!');window.location='index.php';</script>";
    }
}
?>
