<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Koperasi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body {
            font-family:'Poppins',sans-serif;
            background: linear-gradient(135deg, #a18cd1 0%, #fbc2eb 100%);
            min-height:100vh; display:flex; justify-content:center; align-items:center;
            padding:20px;
        }

        .container {
            width:100%; max-width:1200px;
            background:rgba(255,255,255,0.9);
            border-radius:24px;
            padding:40px 30px;
            box-shadow:0 20px 40px rgba(0,0,0,0.1);
            backdrop-filter:blur(25px);
            animation: fadeIn 0.8s ease;
        }

        @keyframes fadeIn {
            from {opacity:0; transform:translateY(20px);}
            to {opacity:1; transform:translateY(0);}
        }

        .header { text-align:center; margin-bottom:40px; }
        .header h1 { font-size:34px; font-weight:700; color:#333; margin-bottom:12px; }
        .welcome {
            background:linear-gradient(135deg, #dbeafe, #e0e7ff);
            padding:18px; border-radius:16px;
            display:inline-block;
        }
        .welcome p { color:#374151; font-size:16px; }
        .welcome .name { font-weight:700; color:#111827; }
        .welcome .level {
            background:linear-gradient(135deg, #6366f1, #8b5cf6);
            color:white; padding:4px 12px; border-radius:20px; font-size:14px; margin-left:6px;
        }

        .menu {
            display:grid;
            grid-template-columns:repeat(auto-fit, minmax(240px,1fr));
            gap:24px; margin-top:35px;
        }

        .menu-item {
            background:rgba(255,255,255,0.8);
            border-radius:20px;
            padding:30px;
            text-align:center;
            font-weight:600; font-size:18px;
            color:#374151;
            text-decoration:none;
            border:1px solid rgba(255,255,255,0.3);
            box-shadow:0 6px 18px rgba(0,0,0,0.08);
            transition:all .35s ease;
            position:relative;
            overflow:hidden;
        }
        .menu-item::before {
            content:''; position:absolute; inset:0;
            background:linear-gradient(135deg, rgba(99,102,241,0.15), rgba(139,92,246,0.15));
            opacity:0; transition:opacity .35s;
        }
        .menu-item:hover::before { opacity:1; }
        .menu-item:hover {
            transform:translateY(-8px) scale(1.03);
            color:#111827;
            box-shadow:0 12px 28px rgba(99,102,241,0.3);
        }

        .logout-section { text-align:center; margin-top:50px; }
        .logout-btn {
            background:linear-gradient(135deg, #ef4444, #f87171);
            border:none; color:white;
            padding:14px 32px; font-size:16px; font-weight:600;
            border-radius:16px; cursor:pointer;
            transition:all .35s;
            position:relative; overflow:hidden;
        }
        .logout-btn:hover {
            transform:translateY(-3px);
            box-shadow:0 10px 20px rgba(239,68,68,0.3);
            background:linear-gradient(135deg,#dc2626,#ef4444);
        }

        /* Modal */
        .modal {
            display:none; position:fixed; inset:0;
            background:rgba(0,0,0,0.55);
            backdrop-filter:blur(5px);
            z-index:1000;
            justify-content:center; align-items:center;
        }
        .modal-content {
            background:white; padding:30px;
            border-radius:20px; max-width:400px; width:90%;
            text-align:center; box-shadow:0 15px 35px rgba(0,0,0,0.15);
            animation:scaleIn .4s ease;
        }
        @keyframes scaleIn {
            from {transform:scale(0.8); opacity:0;}
            to {transform:scale(1); opacity:1;}
        }
        .modal h3 { font-size:20px; margin-bottom:10px; color:#111827; }
        .modal p { color:#6b7280; margin-bottom:20px; }
        .modal-buttons { display:flex; gap:12px; justify-content:center; }
        .btn-confirm, .btn-cancel {
            padding:12px 20px; border:none; border-radius:12px;
            font-weight:600; cursor:pointer; transition:all .3s;
        }
        .btn-confirm {
            background:linear-gradient(135deg,#ef4444,#f87171); color:white;
        }
        .btn-confirm:hover { background:linear-gradient(135deg,#dc2626,#ef4444); }
        .btn-cancel { background:#e5e7eb; color:#374151; }
        .btn-cancel:hover { background:#d1d5db; }

        /* Responsive */
        @media(max-width:600px){
            .header h1 { font-size:26px; }
            .menu-item { font-size:16px; padding:24px; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Dashboard Koperasi</h1>
            <div class="welcome">
                <p>Selamat datang, <span class="name"><?= htmlspecialchars($_SESSION['nama_petugas']); ?></span> 
                <span class="level"><?= htmlspecialchars($_SESSION['level']); ?></span></p>
            </div>
        </div>

        <div class="menu">
            <a href="views/customer/index.php" class="menu-item">👥 Kelola Customer</a>
            <a href="views/sales/index.php" class="menu-item">🛍️ Kelola Sales</a>
            <a href="views/item/index.php" class="menu-item">📦 Kelola Item</a>
            <a href="views/transaksi/index.php" class="menu-item">💰 Kelola Transaksi</a>
            <a href="views/petugas/index.php" class="menu-item">👨‍💼 Kelola Petugas</a>
        </div>

        <div class="logout-section">
            <button class="logout-btn" onclick="showLogoutModal()">🚪 Logout</button>
        </div>
    </div>

    <!-- Modal -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <h3>Konfirmasi Logout</h3>
            <p>Apakah Anda yakin ingin keluar dari sistem?</p>
            <div class="modal-buttons">
                <button class="btn-confirm" onclick="confirmLogout()">Ya, Logout</button>
                <button class="btn-cancel" onclick="closeLogoutModal()">Batal</button>
            </div>
        </div>
    </div>

    <script>
        function showLogoutModal(){ document.getElementById('logoutModal').style.display='flex'; }
        function closeLogoutModal(){ document.getElementById('logoutModal').style.display='none'; }
        function confirmLogout(){ window.location.href='logout.php'; }
        window.onclick = function(e){ if(e.target==document.getElementById('logoutModal')) closeLogoutModal(); }
        document.addEventListener('keydown', function(e){ if(e.key==='Escape') closeLogoutModal(); });
    </script>
</body>
</html>
