<?php
include "../../config/Database.php";
include "../../models/Customer.php";

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);
$stmt = $customer->readAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Data Customer</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f3f4f6; margin:0; padding:20px; }
        .container { max-width:900px; margin:auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        h2 { text-align:center; margin-bottom:20px; color:#374151; }
        /* Style tombol */
        .btn { display:inline-block; padding:10px 16px; border-radius:8px; font-weight:600; text-decoration:none; margin-bottom:15px; transition:.3s; }
        .btn-dashboard { background:#10b981; color:#fff; margin-right:8px; }
        .btn-dashboard:hover { background:#059669; }
        .btn-add { background:#6366f1; color:#fff; }
        .btn-add:hover { background:#4f46e5; }
        /* Table */
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th,td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f9fafb; }
        /* Tombol aksi */
        .aksi a { padding:6px 10px; border-radius:6px; text-decoration:none; margin-right:5px; font-size:14px; }
        .edit { background:#3b82f6; color:#fff; }
        .hapus { background:#ef4444; color:#fff; }
        .edit:hover { background:#2563eb; }
        .hapus:hover { background:#dc2626; }
    </style>
</head>
<body>
<div class="container">
    <h2>Data Customer</h2>
    
    <!-- Tombol kembali ke dashboard & tambah customer -->
    <a class="btn btn-dashboard" href="../../dashboard.php">← Kembali ke Dashboard</a>
    <a class="btn btn-add" href="tambah.php">+ Tambah Customer</a>
    
    <table>
        <tr>
            <th>ID</th><th>Nama</th><th>Alamat</th><th>Telepon</th><th>Aksi</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?= $row['id_customer'] ?></td>
            <td><?= $row['nama_customer'] ?></td>
            <td><?= $row['alamat'] ?></td>
            <td><?= $row['telepon'] ?></td>
            <td class="aksi">
                <a class="edit" href="edit.php?id=<?= $row['id_customer'] ?>">Edit</a>
                <a class="hapus" href="hapus.php?id=<?= $row['id_customer'] ?>" onclick="return confirm('Yakin hapus data?')">Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
