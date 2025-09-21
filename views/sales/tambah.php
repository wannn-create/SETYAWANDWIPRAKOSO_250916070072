<?php
include "../../config/Database.php";
include "../../models/Sales.php";

$db = (new Database())->getConnection();
$sales = new Sales($db);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama']);
    $telepon = trim($_POST['telepon']);

    // Validasi: semua input wajib diisi
    if (empty($nama) || empty($telepon)) {
        $error = "Semua field (Nama dan Telepon) wajib diisi!";
    } else {
        $sales->nama_sales = $nama;
        $sales->telepon = $telepon;

        if ($sales->create()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menyimpan data sales.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Tambah Sales</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family:'Poppins',sans-serif;
            background:#f3f4f6;
            padding:30px;
        }
        .box {
            max-width:540px;
            margin:auto;
            background:#fff;
            padding:22px;
            border-radius:12px;
            box-shadow:0 8px 24px rgba(15,23,42,0.06);
        }
        h2 {
            margin-bottom:16px;
            color:#374151;
            text-align:center;
        }
        label {
            display:block;
            margin-top:10px;
            font-weight:600;
        }
        input {
            width:100%;
            padding:10px;
            border-radius:8px;
            border:1px solid #e6e9ef;
            margin-top:4px;
        }
        button {
            margin-top:14px;
            padding:12px 16px;
            border-radius:10px;
            border:none;
            background:#06b6d4;
            color:#fff;
            font-weight:600;
            cursor:pointer;
            width:100%;
        }
        button:hover {
            background:#0891b2;
        }
        .btn-cancel {
            display:inline-block;
            margin-top:10px;
            padding:12px 16px;
            border-radius:10px;
            background:#ef4444;
            color:#fff;
            text-decoration:none;
            text-align:center;
        }
        .btn-cancel:hover {
            background:#dc2626;
        }
        .error {
            background:#fee2e2;
            color:#b91c1c;
            padding:12px;
            border-radius:8px;
            margin-bottom:15px;
            border-left:4px solid #dc2626;
        }
    </style>
</head>
<body>
<div class="box">
    <h2>Tambah Sales</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" required>

        <label>Telepon</label>
        <input type="text" name="telepon" value="<?= isset($_POST['telepon']) ? htmlspecialchars($_POST['telepon']) : '' ?>" required>

        <button type="submit">Simpan</button>
        <a href="index.php" class="btn-cancel">Batal</a>
    </form>
</div>
</body>
</html>
