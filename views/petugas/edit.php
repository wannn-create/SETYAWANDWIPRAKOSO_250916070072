<?php
include "../../config/Database.php";
include "../../models/Petugas.php";

$db = (new Database())->getConnection();
$petugas = new Petugas($db);

if (!isset($_GET['id'])) header("Location:index.php");
$petugas->id_petugas = $_GET['id'];
$data = $petugas->readOne();

if ($_POST) {
    $petugas->nama_petugas = $_POST['nama'];
    $petugas->username = $_POST['username'];
    $petugas->password = $_POST['password']; // plain text untuk latihan
    $petugas->level = $_POST['level'];
    if ($petugas->update()) {
        header("Location:index.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Petugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:Poppins,sans-serif;background:#f3f4f6;padding:40px}
        .form-box{max-width:500px;margin:auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.1)}
        h2{text-align:center;margin-bottom:20px;color:#374151}
        label{display:block;margin:12px 0 6px;font-weight:600}
        input,select{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;margin-bottom:15px}
        button{width:100%;padding:12px;background:#3b82f6;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer}
        button:hover{background:#2563eb}
        a{display:inline-block;margin-top:10px;text-decoration:none;color:#ef4444}
    </style>
</head>
<body>
<div class="form-box">
    <h2>Edit Petugas</h2>
    <form method="post">
        <label>Nama Petugas</label>
        <input type="text" name="nama" required value="<?= htmlspecialchars($data['nama_petugas']) ?>">
        <label>Username</label>
        <input type="text" name="username" required value="<?= htmlspecialchars($data['username']) ?>">
        <label>Password</label>
        <input type="password" name="password" required value="<?= htmlspecialchars($data['password']) ?>">
        <label>Level</label>
        <select name="level" required>
            <option value="Admin" <?= ($data['level']=="Admin"?"selected":"") ?>>Admin</option>
            <option value="Manager" <?= ($data['level']=="Manager"?"selected":"") ?>>Manager</option>
            <option value="Staff" <?= ($data['level']=="Staff"?"selected":"") ?>>Staff</option>
        </select>
        <button type="submit">Update</button>
        <a href="index.php">Batal</a>
    </form>
</div>
</body>
</html>
