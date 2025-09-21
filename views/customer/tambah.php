<?php
include "../../config/Database.php";
include "../../models/Customer.php";

$database = new Database();
$db = $database->getConnection();
$customer = new Customer($db);

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);

    // Validasi: semua harus diisi
    if (empty($nama) || empty($alamat) || empty($telepon)) {
        $error = "Semua field (Nama, Alamat, Telepon) wajib diisi!";
    } else {
        $customer->nama_customer = $nama;
        $customer->alamat = $alamat;
        $customer->telepon = $telepon;

        if ($customer->create()) {
            header("Location: index.php");
            exit;
        } else {
            $error = "Gagal menyimpan data customer.";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tambah Customer</title>
    <style>
        body { font-family:Poppins,sans-serif; background:#f3f4f6; padding:40px; }
        .form-box { max-width:500px; margin:auto; background:#fff; padding:25px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.1); }
        h2 { text-align:center; margin-bottom:20px; color:#374151; }
        label { display:block; margin:12px 0 6px; font-weight:600; }
        input,textarea { width:100%; padding:10px; border:1px solid #d1d5db; border-radius:8px; }
        button { margin-top:15px; width:100%; padding:12px; background:#6366f1; color:#fff; border:none; border-radius:8px; font-weight:600; cursor:pointer; }
        button:hover { background:#4f46e5; }
        .error { background:#fee2e2; color:#b91c1c; padding:12px; border-radius:8px; margin-bottom:15px; border-left:4px solid #dc2626; }
    </style>
</head>
<body>
<div class="form-box">
    <h2>Tambah Customer</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <label>Nama</label>
        <input type="text" name="nama" value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>" required>

        <label>Alamat</label>
        <textarea name="alamat" required><?= isset($_POST['alamat']) ? htmlspecialchars($_POST['alamat']) : '' ?></textarea>

        <label>Telepon</label>
        <input type="text" name="telepon" value="<?= isset($_POST['telepon']) ? htmlspecialchars($_POST['telepon']) : '' ?>" required>

        <button type="submit">Simpan</button>
    </form>
</div>
</body>
</html>
