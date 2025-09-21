<?php
include "../../config/Database.php";
include "../../models/Petugas.php";

$db = (new Database())->getConnection();
$petugas = new Petugas($db);

$success = false;
$error = "";

if ($_POST) {
    $petugas->nama_petugas = $_POST['nama'];
    $petugas->username = $_POST['username'];
    $petugas->password = $_POST['password']; // masih plain text untuk latihan
    $petugas->level = $_POST['level'];

    if ($petugas->create()) {
        $success = true;
    } else {
        $error = "Gagal menyimpan data petugas. Username mungkin sudah digunakan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Petugas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .register-card {
            width: 100%;
            max-width: 460px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 24px;
            padding: 36px 28px;
            box-shadow: 0 8px 28px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(16px);
        }

        .logo-section {
            text-align: center;
            margin-bottom: 28px;
        }

        .logo {
            width: 70px; height: 70px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 14px;
            font-size: 28px;
            color: #fff;
        }

        .register-title { font-size: 26px; font-weight: 700; text-align: center; margin-bottom: 6px; color: #1f2937; }
        .register-subtitle { font-size: 15px; text-align: center; color: #6b7280; margin-bottom: 24px; }

        .form-group { margin-bottom: 18px; }
        .form-label { font-weight: 500; margin-bottom: 6px; display: block; font-size: 14px; color: #374151; }

        .form-input, .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 14px;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16,185,129,0.15);
        }

        .btn-primary {
            width: 100%;
            padding: 14px 18px;
            border: none;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            background: linear-gradient(135deg, #667eea, #059669);
            color: #fff;
            cursor: pointer;
            margin-bottom: 14px;
            transition: all 0.3s ease;
        }
        .btn-primary:hover { box-shadow: 0 6px 20px rgba(16,185,129,0.4); transform: translateY(-2px); }

        .btn-secondary {
            width: 100%;
            padding: 14px 18px;
            border-radius: 14px;
            font-weight: 600;
            font-size: 15px;
            text-decoration: none;
            display: inline-block;
            text-align: center;
            border: 2px solid #6b7280;
            color: #6b7280;
            transition: 0.3s;
        }
        .btn-secondary:hover { background: #6b7280; color: #fff; }

        .success-message, .error-message {
            padding: 14px 18px;
            border-radius: 14px;
            margin-bottom: 18px;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
        }
        .success-message { background: #d1fae5; border: 1px solid #6ee7b7; color: #065f46; }
        .error-message { background: #fee2e2; border: 1px solid #fca5a5; color: #b91c1c; }

        @media (max-width: 480px) {
            .register-card { padding: 28px 22px; border-radius: 18px; }
            .register-title { font-size: 22px; }
            .form-input, .form-select, .btn-primary, .btn-secondary { font-size: 14px; padding: 12px 14px; }
        }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="logo-section">
            <div class="logo">👤</div>
            <h1 class="register-title">Register Petugas</h1>
            <p class="register-subtitle">Buat akun petugas baru</p>
        </div>

        <?php if ($success): ?>
        <div class="success-message">
            ✅ Akun berhasil dibuat. Anda akan diarahkan ke halaman login dalam <span id="countdown">3</span> detik...
        </div>
        <script>
            let count = 3;
            const el = document.getElementById("countdown");
            const timer = setInterval(() => {
                count--;
                el.textContent = count;
                if (count <= 0) {
                    clearInterval(timer);
                    window.location.href = "../../index.php";
                }
            }, 1000);
        </script>
        <?php elseif (!empty($error)): ?>
        <div class="error-message">❌ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!$success): ?>
        <form method="post">
            <div class="form-group">
                <label class="form-label" for="nama">Nama Petugas</label>
                <input type="text" name="nama" id="nama" class="form-input" required value="<?= isset($_POST['nama']) ? htmlspecialchars($_POST['nama']) : '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="username">Username</label>
                <input type="text" name="username" id="username" class="form-input" required value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>">
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="level">Level</label>
                <select name="level" id="level" class="form-select" required>
                    <option value="">-- Pilih Level --</option>
                    <option value="Admin" <?= (isset($_POST['level']) && $_POST['level'] == 'Admin') ? 'selected' : '' ?>>Admin</option>
                    <option value="Manager" <?= (isset($_POST['level']) && $_POST['level'] == 'Manager') ? 'selected' : '' ?>>Manager</option>
                    <option value="Staff" <?= (isset($_POST['level']) && $_POST['level'] == 'Staff') ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>
            <button type="submit" class="btn-primary">Daftar Akun</button>
        </form>
        <?php endif; ?>

        <a href="../../index.php" class="btn-secondary">⬅ Kembali ke Login</a>
    </div>
</body>
</html>
