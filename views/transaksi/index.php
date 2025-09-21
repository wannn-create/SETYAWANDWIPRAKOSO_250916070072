<?php
include "../../config/Database.php";
include "../../models/Transaksi.php";

$db = (new Database())->getConnection();
$transaksi = new Transaksi($db);
$stmt = $transaksi->readAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Poppins',sans-serif; background:#f3f4f6; padding:40px; }
        .container { max-width:1000px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.1); }
        h2 { text-align:center; margin-bottom:20px; color:#374151; }
        table { width:100%; border-collapse:collapse; margin-top:20px; }
        th,td { border:1px solid #e5e7eb; padding:10px; text-align:center; }
        th { background:#f9fafb; color:#374151; }
        a.btn { padding:10px 14px; border-radius:8px; text-decoration:none; font-weight:600; margin:5px 5px 15px 0; display:inline-block; font-size:14px; }
        .btn-add { background:#3b82f6; color:#fff; }          /* biru */
        .btn-add:hover { background:#2563eb; }
        .btn-delete { background:#ef4444; color:#fff; }
        .btn-delete:hover { background:#dc2626; }
        .btn-detail { background:#6366f1; color:#fff; }
        .btn-detail:hover { background:#4f46e5; }
        .btn-back { background:#10b981; color:#fff; }
        .btn-back:hover { background:#059669; }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fa-solid fa-file-invoice"></i> Daftar Transaksi</h2>

    <!-- Tombol kembali + tambah transaksi di atas tabel -->
    <a href="../../dashboard.php" class="btn btn-back"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>
    <a href="tambah.php" class="btn btn-add"><i class="fa fa-plus"></i> Tambah Transaksi</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Tanggal</th>
            <th>Customer</th>
            <th>Sales</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?= $row['id_transaksi'] ?></td>
            <td><?= $row['tanggal'] ?></td>
            <td><?= $row['nama_customer'] ?></td>
            <td><?= $row['nama_sales'] ?></td>
            <td>
                <a href="detail.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-detail"><i class="fa fa-eye"></i> Detail</a>
                <a href="hapus.php?id=<?= $row['id_transaksi'] ?>" class="btn btn-delete" onclick="return confirm('Hapus transaksi ini?')"><i class="fa fa-trash"></i> Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
