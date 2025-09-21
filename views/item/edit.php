<?php
include "../../config/Database.php"; 
include "../../models/Item.php";

$db = (new Database())->getConnection(); 
$item = new Item($db);

if (!isset($_GET['id'])) header("Location:index.php");

$item->id_item = $_GET['id']; 
$data = $item->readOne();

if ($_POST) {
    $item->nama_item = $_POST['nama'];

    // Bersihkan input harga agar tetap angka murni
    $harga = str_replace(['.', ','], '', $_POST['harga']);
    $item->harga = $harga;

    $item->stok = $_POST['stok'];

    if ($item->update()) {
        header("Location:index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Item</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { 
            font-family:'Poppins',sans-serif; 
            background:#f3f4f6; 
            padding:40px; 
        }
        .box { 
            max-width:500px; 
            margin:auto; 
            background:#fff; 
            padding:25px; 
            border-radius:12px; 
            box-shadow:0 4px 12px rgba(0,0,0,.1); 
        }
        h2 { 
            text-align:center; 
            margin-bottom:20px; 
            color:#374151; 
        }
        label { 
            display:block; 
            margin:12px 0 6px; 
            font-weight:600; 
        }
        input { 
            width:100%; 
            padding:10px; 
            border:1px solid #d1d5db; 
            border-radius:8px; 
            margin-bottom:15px; 
            font-size:14px; 
        }
        .btn { 
            display:inline-block; 
            padding:10px 16px; 
            border-radius:8px; 
            font-weight:600; 
            text-decoration:none; 
            transition:.3s; 
        }
        .btn-update { 
            background:#3b82f6; 
            color:#fff; 
            border:none; 
            cursor:pointer; 
        }
        .btn-update:hover { background:#2563eb; }
        .btn-cancel { 
            background:#ef4444; 
            color:#fff; 
            margin-left:8px; 
        }
        .btn-cancel:hover { background:#dc2626; }
    </style>
</head>
<body>
<div class="box">
    <h2><i class="fa-solid fa-pen-to-square"></i> Edit Item</h2>
    <form method="post">
        <label>Nama Item</label>
        <input type="text" name="nama" required value="<?= htmlspecialchars($data['nama_item']) ?>">
        
        <label>Harga</label>
        <!-- tampilkan harga dengan format ribuan -->
        <input type="text" name="harga" 
               value="<?= number_format($data['harga'], 0, ',', '.') ?>" 
               placeholder="contoh: 15000 atau 15.000">

        <label>Stok</label>
        <input type="number" name="stok" value="<?= htmlspecialchars($data['stok']) ?>">

        <button type="submit" class="btn btn-update">
            <i class="fa-solid fa-save"></i> Update
        </button>
        <a href="index.php" class="btn btn-cancel">
            <i class="fa-solid fa-xmark"></i> Batal
        </a>
    </form>
</div>
</body>
</html>
