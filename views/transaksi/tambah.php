<?php
include "../../config/Database.php";
include "../../models/Transaksi.php";

$db = (new Database())->getConnection();
$transaksi = new Transaksi($db);

// Data dropdown
$customers = $db->query("SELECT * FROM customer ORDER BY nama_customer")->fetchAll(PDO::FETCH_ASSOC);
$sales = $db->query("SELECT * FROM sales ORDER BY nama_sales")->fetchAll(PDO::FETCH_ASSOC);
$items = $db->query("SELECT * FROM item ORDER BY nama_item")->fetchAll(PDO::FETCH_ASSOC);

$error = "";
$old_customer = "";
$old_sales = "";
$old_checked_items = [];
$old_qty = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_customer = $_POST['customer'] ?? "";
    $old_sales = $_POST['sales'] ?? "";
    $old_checked_items = $_POST['item'] ?? [];
    $old_qty = $_POST['qty'] ?? [];

    if (empty($old_customer)) {
        $error = "Customer harus dipilih.";
    } elseif (empty($old_sales)) {
        $error = "Sales harus dipilih.";
    } elseif (empty($old_checked_items)) {
        $error = "Pilih minimal 1 item dengan quantity.";
    } else {
        $qty_valid = true;
        foreach ($old_checked_items as $item_id) {
            $qty = isset($old_qty[$item_id]) ? (int)$old_qty[$item_id] : 0;
            if ($qty <= 0) {
                $error = "Masukkan quantity yang valid untuk semua item yang dipilih.";
                $qty_valid = false;
                break;
            }
        }
        
        if ($qty_valid) {
            $transaksi->id_customer = (int)$old_customer;
            $transaksi->id_sales = (int)$old_sales;
            $transaksi->tanggal = date("Y-m-d");

            if ($transaksi->create($old_checked_items, $old_qty)) {
                header("Location: index.php");
                exit();
            } else {
                $error = "Gagal menyimpan transaksi: " . $transaksi->error_message;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Transaksi</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body{font-family:Poppins,sans-serif;background:#f3f4f6;padding:40px}
        .form-box{max-width:750px;margin:auto;background:#fff;padding:25px;border-radius:12px;box-shadow:0 4px 12px rgba(0,0,0,.1)}
        h2{text-align:center;margin-bottom:20px;color:#374151}
        label{display:block;margin:12px 0 6px;font-weight:600}
        select,input{width:100%;padding:10px;border:1px solid #d1d5db;border-radius:8px;margin-bottom:15px;box-sizing:border-box}

        /* Layout item lebih rapi dengan grid */
        .item-row{
            display:grid;
            grid-template-columns: 40px 1fr 90px;
            align-items:center;
            gap:12px;
            padding:10px 0;
            border-bottom:1px solid #f1f5f9
        }
        .item-name{font-weight:600;color:#111827}
        .item-info{font-size:0.85em;color:#6b7280}
        .qty-input{width:80px;padding:8px;text-align:center}
        
        .actions{margin-top:20px;text-align:right}
        button{padding:12px 16px;background:#10b981;color:#fff;border:none;border-radius:8px;font-weight:600;cursor:pointer}
        button:hover{background:#059669}
        .btn-cancel{margin-left:8px;padding:12px 16px;background:#ef4444;color:#fff;border-radius:8px;text-decoration:none;display:inline-block}
        .btn-cancel:hover{background:#dc2626}
        .error{background:#fee2e2;color:#b91c1c;padding:15px;border-radius:8px;margin-bottom:12px;border-left:4px solid #dc2626}
    </style>
</head>
<body>
<div class="form-box">
    <h2>Tambah Transaksi</h2>

    <?php if ($error): ?>
        <div class="error"><strong>Error:</strong> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="post" id="formTransaksi">
        <label>Customer *</label>
        <select name="customer" required>
            <option value="">-- Pilih Customer --</option>
            <?php foreach($customers as $c){ ?>
                <option value="<?= $c['id_customer'] ?>" <?= ($old_customer == $c['id_customer']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($c['nama_customer']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Sales *</label>
        <select name="sales" required>
            <option value="">-- Pilih Sales --</option>
            <?php foreach($sales as $s){ ?>
                <option value="<?= $s['id_sales'] ?>" <?= ($old_sales == $s['id_sales']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($s['nama_sales']) ?>
                </option>
            <?php } ?>
        </select>

        <label>Item (centang lalu masukkan Qty) *</label>

        <?php foreach($items as $i){ 
            $id = $i['id_item'];
            $checked = in_array($id, $old_checked_items) ? 'checked' : '';
            $qtyVal = isset($old_qty[$id]) ? (int)$old_qty[$id] : '';
        ?>
            <div class="item-row">
                <input type="checkbox" class="chk-item" data-id="<?= $id ?>" name="item[]" value="<?= $id ?>" <?= $checked ?>>
                <div>
                    <div class="item-name"><?= htmlspecialchars($i['nama_item']) ?></div>
                    <div class="item-info">Rp<?= number_format($i['harga'],0,',','.') ?> - Stok: <?= $i['stok'] ?></div>
                </div>
                <input type="number" 
                       name="qty[<?= $id ?>]" 
                       min="1" max="<?= $i['stok'] ?>"
                       class="qty-input" 
                       data-id="<?= $id ?>" 
                       placeholder="Qty" 
                       value="<?= ($qtyVal ? $qtyVal : '') ?>" 
                       <?= $checked ? '' : 'readonly' ?>
                       title="Maksimal <?= $i['stok'] ?> item">
            </div>
        <?php } ?>

        <div class="actions">
            <button type="submit">Simpan Transaksi</button>
            <a href="index.php" class="btn-cancel">Batal</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    document.querySelectorAll('.chk-item').forEach(function(chk){
        chk.addEventListener('change', function(){
            const id = this.dataset.id;
            const qty = document.querySelector('.qty-input[data-id="'+id+'"]');
            if(this.checked){
                qty.removeAttribute('readonly');
                if(!qty.value) qty.value = 1;
                qty.focus();
            } else {
                qty.setAttribute('readonly', true);
                qty.value = '';
            }
        });
    });

    document.getElementById('formTransaksi').addEventListener('submit', function(e){
        const customer = document.querySelector('select[name="customer"]').value;
        const sales = document.querySelector('select[name="sales"]').value;
        const checked = Array.from(document.querySelectorAll('.chk-item')).filter(c => c.checked);
        
        if (!customer) { alert('Customer harus dipilih.'); e.preventDefault(); return; }
        if (!sales) { alert('Sales harus dipilih.'); e.preventDefault(); return; }
        if(checked.length === 0){ alert('Pilih minimal 1 item.'); e.preventDefault(); return; }
        
        for(const c of checked){
            const id = c.dataset.id;
            const q = document.querySelector('.qty-input[data-id="'+id+'"]');
            const maxQty = parseInt(q.getAttribute('max'));
            const currentQty = parseInt(q.value);
            
            if(!q || !q.value || currentQty <= 0){
                alert('Masukkan quantity yang valid untuk item yang dicentang.');
                e.preventDefault();
                return;
            }
            if(currentQty > maxQty){
                alert(`Quantity untuk item ID ${id} melebihi stok yang tersedia (max: ${maxQty}).`);
                e.preventDefault();
                return;
            }
        }
    });
});
</script>
</body>
</html>
