<?php
include "../../config/Database.php";
include "../../models/Customer.php";
$database = new Database(); $db = $database->getConnection();
$customer = new Customer($db);
if (!isset($_GET['id'])) { header("Location:index.php"); exit; }
$customer->id_customer = $_GET['id'];
$data = $customer->readOne();
if ($_POST) {
    $customer->nama_customer = $_POST['nama'];
    $customer->alamat = $_POST['alamat'];
    $customer->telepon = $_POST['telepon'];
    if ($customer->update()) { header("Location: index.php"); exit; } else { $error="Gagal update"; }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Edit Customer</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
<style>body{font-family:'Poppins',sans-serif;background:#f3f4f6;padding:30px}.box{max-width:640px;margin:auto;background:#fff;padding:22px;border-radius:12px;box-shadow:0 10px 30px rgba(15,23,42,0.06)} label{display:block;margin-top:10px} input,textarea{width:100%;padding:10px;border-radius:8px;border:1px solid:#e6e9ef} button{margin-top:14px;padding:12px 16px;border-radius:10px;border:none;background:#3b82f6;color:#fff;font-weight:600;cursor:pointer} .err{background:#fee2e2;color:#b91c1c;padding:8px;border-radius:8px}</style>
</head>
<body>
<div class="box">
  <h2>Edit Customer</h2>
  <?php if(!empty($error)) echo "<div class='err'>{$error}</div>"; ?>
  <form method="post">
    <label>Nama</label><input type="text" name="nama" required value="<?= htmlspecialchars($data['nama_customer']) ?>">
    <label>Alamat</label><textarea name="alamat" rows="3"><?= htmlspecialchars($data['alamat']) ?></textarea>
    <label>Telepon</label><input type="text" name="telepon" value="<?= htmlspecialchars($data['telepon']) ?>">
    <button type="submit">Update</button>
    <a href="index.php" style="display:inline-block;margin-left:10px;padding:12px 16px;border-radius:10px;background:#ef4444;color:#fff;text-decoration:none">Batal</a>
  </form>
</div>
</body>
</html>
