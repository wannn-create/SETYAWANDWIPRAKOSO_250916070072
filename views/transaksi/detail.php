<?php
include "../../config/Database.php";
include "../../models/Transaksi.php";

$db = (new Database())->getConnection();
$transaksi = new Transaksi($db);

if (!isset($_GET['id'])) header("Location:index.php");
$id = $_GET['id'];
$data = $transaksi->readOne($id);
$detail = $transaksi->readDetail($id);

// Cek apakah data transaksi ditemukan
if (!$data) {
    echo "<script>alert('Transaksi tidak ditemukan'); window.location='index.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:Poppins,sans-serif;background:#f3f4f6;padding:40px}
        .box{max-width:800px;margin:auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.1)}
        h2{text-align:center;margin-bottom:20px;color:#374151}
        .info-section{background:#f8fafc;padding:15px;border-radius:8px;margin-bottom:20px}
        .info-section p{margin:8px 0}
        h3{color:#374151;border-bottom:2px solid #3b82f6;padding-bottom:5px}
        table{width:100%;border-collapse:collapse;margin-top:15px}
        th,td{border:1px solid #e5e7eb;padding:12px;text-align:center}
        th{background:#3b82f6;color:#fff;font-weight:600}
        td{background:#ffffff}
        .total-row{background:#f1f5f9 !important;font-weight:600}
        .btn-back{margin-top:20px;display:inline-block;padding:12px 16px;border-radius:8px;background:#6b7280;color:#fff;text-decoration:none;font-weight:600}
        .btn-back:hover{background:#4b5563}
        .text-right{text-align:right}
    </style>
</head>
<body>
<div class="box">
    <h2>Detail Transaksi</h2>
    
    <div class="info-section">
        <p><b>ID Transaksi:</b> <?= htmlspecialchars($data['id_transaksi']) ?></p>
        <p><b>Tanggal:</b> <?= date('d-m-Y', strtotime($data['tanggal'])) ?></p>
        <p><b>Customer:</b> <?= htmlspecialchars($data['nama_customer']) ?></p>
        <p><b>Sales:</b> <?= htmlspecialchars($data['nama_sales']) ?></p>
    </div>

    <h3>Item yang Dibeli</h3>
    <table>
        <tr>
            <th>Nama Item</th>
            <th>Harga</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php 
        $total = 0;
        while($row = $detail->fetch(PDO::FETCH_ASSOC)){
            // PERBAIKAN: gunakan 'jumlah' bukan 'qty'
            $qty = $row['jumlah'];
            $harga = $row['harga'];
            $subtotal = $harga * $qty;
            $total += $subtotal;
        ?>
        <tr>
            <td><?= htmlspecialchars($row['nama_item']) ?></td>
            <td class="text-right">Rp<?= number_format($harga, 0, ',', '.') ?></td>
            <td><?= $qty ?></td>
            <td class="text-right">Rp<?= number_format($subtotal, 0, ',', '.') ?></td>
        </tr>
        <?php } ?>
        <tr class="total-row">
            <th colspan="3">Total</th>
            <th class="text-right">Rp<?= number_format($total, 0, ',', '.') ?></th>
        </tr>
    </table>
    
    <a href="index.php" class="btn-back">Kembali</a>
</div>
</body>
</html>