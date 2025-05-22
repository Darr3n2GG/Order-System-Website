<?php
// require_once dirname(__FILE__, 1) . "/adminBootstrap.php";
require_once dirname(__FILE__, 3) . "/backend/Database.php";
require_once dirname(__FILE__, 3) . "/backend/Autoloader.php";
// Get pesanan ID from query string
$pesananId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($pesananId <= 0) {
    echo "ID resit tidak sah.";
    exit;
}

// Fetch receipt using the Pesanan model directly (bypassing handleRequest)
$Database = createDatabaseConn();
$PesananModel = new lib\Pesanan($Database);

$receipt = $PesananModel->getReceipt($pesananId);

if (!$receipt) {
    echo "Resit tidak dijumpai.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Resit Pesanan</title>
    <link rel="stylesheet" href="receipt.css">
</head>

<body>

    <div class="receipt-header">
        <h2>Resit Pesanan</h2>
        <p><strong>ID Pesanan:</strong> <?= htmlspecialchars($receipt['id']) ?></p>
        <p><strong>Nama Pelanggan:</strong> <?= htmlspecialchars($receipt['nama']) ?></p>
        <p><strong>Tarikh:</strong> <?= htmlspecialchars($receipt['tarikh']) ?></p>
        <p><strong>Cara:</strong> <?= htmlspecialchars($receipt['cara']) ?></p>
        <?php if (!empty($receipt['no_meja'])): ?>
            <p><strong>No Meja:</strong> <?= htmlspecialchars($receipt['no_meja']) ?></p>
        <?php endif; ?>
        <p><strong>Status:</strong> <?= htmlspecialchars($receipt['status']) ?></p>
    </div>

    <table class="receipt-table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Kuantiti</th>
                <th>Harga Seunit</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($receipt['belian'] as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['nama']) ?></td>
                    <td><?= htmlspecialchars($item['kuantiti']) ?></td>
                    <td>RM <?= number_format($item['harga'], 2) ?></td>
                    <td>RM <?= number_format($item['jumlah'], 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        Jumlah Keseluruhan: <strong>RM <?= number_format($receipt['jumlah_harga'], 2) ?></strong>
    </div>

</body>

</html>