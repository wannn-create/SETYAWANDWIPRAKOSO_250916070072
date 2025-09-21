<?php
include "../../config/Database.php";
include "../../models/Item.php";

$db = (new Database())->getConnection();
$item = new Item($db);

if ($_POST) {
    $item->nama_item = $_POST['nama'];

    // Hapus titik/koma agar harga disimpan sebagai angka murni
    $harga = str_replace(['.', ','], '', $_POST['harga']);
    $item->harga = $harga;

    $item->stok = $_POST['stok'];
    if ($item->create()) header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family:Poppins,sans-serif; background:#f3f4f6; padding:40px; }
        .form-box { max-width:500px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.1); }
        h2 { text-align:center; margin-bottom:20px; color:#374151; }
        label { display:block; margin:12px 0 6px; font-weight:600; }
        input { width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; margin-bottom:15px; font-size:14px; }
        button { width:100%; padding:12px; background:#10b981; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer; transition:.3s; }
        button:hover { background:#059669; }
        a { display:inline-block; margin-top:10px; text-decoration:none; color:#ef4444; }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Tambah Item</h2>
    <form method="post">
        <label>Nama Item</label>
        <input type="text" name="nama" required>

        <label>Harga</label>
        <!-- pakai text agar bisa input 15.000 -->
        <input type="text" name="harga" required placeholder="contoh: 15000 atau 15.000">

        <label>Stok</label>
        <input type="number" name="stok" required>

        <button type="submit">Simpan</button>
        <a href="index.php">Batal</a>
    </form>
</div>
</body>
</html>
