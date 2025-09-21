<?php
include "../../config/Database.php";
include "../../models/Petugas.php";

$db = (new Database())->getConnection();
$petugas = new Petugas($db);
$stmt = $petugas->readAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Petugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body{font-family:Poppins,sans-serif;background:#f3f4f6;padding:30px}
        .container{max-width:900px;margin:auto;background:#fff;padding:20px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.1)}
        h2{text-align:center;margin-bottom:20px;color:#374151}
        table{width:100%;border-collapse:collapse;margin-top:15px}
        th,td{border:1px solid #e5e7eb;padding:10px;text-align:left}
        th{background:#f9fafb}
        a.btn{padding:8px 12px;border-radius:6px;text-decoration:none;font-size:14px;margin-bottom:10px;display:inline-block}
        .btn-dashboard{background:#6366f1;color:#fff;font-weight:600}
        .btn-add{background:#10b981;color:#fff}
        .btn-edit{background:#3b82f6;color:#fff}
        .btn-del{background:#ef4444;color:#fff}
        .btn-dashboard:hover{background:#4f46e5}
        .btn-add:hover{background:#059669}
        .btn-edit:hover{background:#2563eb}
        .btn-del:hover{background:#dc2626}
    </style>
</head>
<body>
<div class="container">
    <h2>Data Petugas</h2>

    <!-- Tombol kembali ke dashboard -->
    <a href="../../dashboard.php" class="btn btn-dashboard"><i class="fa fa-arrow-left"></i> Kembali ke Dashboard</a>
    <a href="tambah.php" class="btn btn-add"><i class="fa fa-plus"></i> Tambah Petugas</a>

    <table>
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Username</th>
            <th>Level</th>
            <th>Aksi</th>
        </tr>
        <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>
        <tr>
            <td><?= $row['id_petugas'] ?></td>
            <td><?= htmlspecialchars($row['nama_petugas']) ?></td>
            <td><?= htmlspecialchars($row['username']) ?></td>
            <td><?= htmlspecialchars($row['level']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['id_petugas'] ?>" class="btn btn-edit"><i class="fa fa-pen"></i></a>
                <a href="hapus.php?id=<?= $row['id_petugas'] ?>" class="btn btn-del" onclick="return confirm('Yakin hapus?')"><i class="fa fa-trash"></i></a>
            </td>
        </tr>
        <?php } ?>
    </table>
</div>
</body>
</html>
