<?php
include "../../config/Database.php";
include "../../models/Sales.php";

$db = (new Database())->getConnection();
$sales = new Sales($db);
$stmt = $sales->readAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Sales</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Poppins',sans-serif; background:#f3f4f6; padding:30px; }
        .container { max-width:900px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.1); }
        h2 { text-align:center; margin-bottom:20px; color:#374151; }
        a.btn { display:inline-block; padding:10px 14px; border-radius:8px; font-weight:600; text-decoration:none; margin-bottom:15px; transition:.3s; }
        .btn-dashboard { background:#10b981; color:#fff; margin-right:8px; }
        .btn-dashboard:hover { background:#059669; }
        .btn-add { background:#6366f1; color:#fff; }
        .btn-add:hover { background:#4f46e5; }
        table { width:100%; border-collapse:collapse; margin-top:10px; }
        th,td { padding:12px; border-bottom:1px solid #e5e7eb; text-align:left; }
        th { background:#f9fafb; }
        .aksi a { padding:8px 12px; border-radius:6px; text-decoration:none; font-size:14px; margin-right:5px; display:inline-block; }
        .edit { background:#3b82f6; color:#fff; }
        .edit:hover { background:#2563eb; }
        .hapus { background:#ef4444; color:#fff; }
        .hapus:hover { background:#dc2626; }
    </style>
</head>
<body>
<div class="container">
    <h2><i class="fa-solid fa-users"></i> Data Sales</h2>

    <!-- Tombol kembali ke dashboard + tambah sales -->
    <a class="btn btn-dashboard" href="../../dashboard.php"><i class="fa-solid fa-arrow-left"></i> Kembali ke Dashboard</a>
    <a class="btn btn-add" href="tambah.php"><i class="fa-solid fa-plus"></i> Tambah Sales</a>

    <table>
        <tr>
            <th>ID</th><th>Nama</th><th>Telepon</th><th>Aksi</th>
        </tr>
        <?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
        <tr>
            <td><?= $row['id_sales'] ?></td>
            <td><?= $row['nama_sales'] ?></td>
            <td><?= $row['telepon'] ?></td>
            <td class="aksi">
                <a class="edit" href="edit.php?id=<?= $row['id_sales'] ?>"><i class="fa-solid fa-pen-to-square"></i> Edit</a>
                <a class="hapus" href="hapus.php?id=<?= $row['id_sales'] ?>" onclick="return confirm('Yakin hapus data ini?')"><i class="fa-solid fa-trash"></i> Hapus</a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
