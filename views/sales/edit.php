<?php
include "../../config/Database.php"; 
include "../../models/Sales.php";

$db = (new Database())->getConnection(); 
$sales = new Sales($db);

if (!isset($_GET['id'])) header("Location:index.php");
$sales->id_sales = $_GET['id']; 
$data = $sales->readOne();

if ($_POST) {
    $sales->nama_sales = $_POST['nama']; 
    $sales->telepon = $_POST['telepon'];
    if ($sales->update()) header("Location:index.php");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Sales</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <!-- Font Awesome untuk ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        body { font-family:'Poppins',sans-serif; background:#f3f4f6; padding:40px; }
        .box { max-width:500px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.1); }
        h2 { text-align:center; margin-bottom:20px; color:#374151; }
        label { display:block; margin:12px 0 6px; font-weight:600; }
        input[type=text] {
            width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px;
            margin-bottom:15px; font-size:14px;
        }
        .btn { display:inline-block; padding:10px 16px; border-radius:8px; font-weight:600; text-decoration:none; transition:.3s; }
        .btn-update { background:#3b82f6; color:#fff; border:none; cursor:pointer; }
        .btn-update:hover { background:#2563eb; }
        .btn-cancel { background:#ef4444; color:#fff; margin-left:8px; }
        .btn-cancel:hover { background:#dc2626; }
    </style>
</head>
<body>
<div class="box">
    <h2><i class="fa-solid fa-pen-to-square"></i> Edit Sales</h2>
    <form method="post">
        <label>Nama</label>
        <input type="text" name="nama" required value="<?= htmlspecialchars($data['nama_sales']) ?>">
        
        <label>Telepon</label>
        <input type="text" name="telepon" value="<?= htmlspecialchars($data['telepon']) ?>">

        <button type="submit" class="btn btn-update"><i class="fa-solid fa-save"></i> Update</button>
        <a href="index.php" class="btn btn-cancel"><i class="fa-solid fa-xmark"></i> Batal</a>
    </form>
</div>
</body>
</html>
