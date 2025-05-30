<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $mobile = $_POST['mobile'];
    $city = $_POST['city'];
    $date = $_POST['date'];
    $estimate_no = $_POST['estimate_no'];
    $discount = floatval($_POST['discount']);

    $product_ids = $_POST['product_id'];
    $product_names = $_POST['product_name'];
    $units = $_POST['unit'];
    $rates = $_POST['product_rate'];
    $qtys = $_POST['product_qty'];
    $amounts = $_POST['product_amount'];

    $subtotal = array_sum($amounts);
    $discount_amount = $subtotal * ($discount / 100);
    $total = $subtotal - $discount_amount;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Invoice Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <form method="POST" action="invoice_pdf.php" target="_blank" id="pdfForm">

        <div class="container my-4">
            <!-- Top Buttons -->
            <div class="d-flex justify-content-between mb-4">
                <a href="javascript:history.back()" class="btn btn-outline-secondary">← Back</a>
                <button type="submit" class="btn btn-outline-success">Download PDF</button>
            </div>

            <!-- Customer Details -->
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Estimate - RETAIL</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4"><strong>Full Name:</strong> <?= htmlspecialchars($full_name) ?></div>
                        <div class="col-md-4"><strong>Mobile:</strong> <?= htmlspecialchars($mobile) ?></div>
                        <div class="col-md-4"><strong>City:</strong> <?= htmlspecialchars($city) ?></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-4"><strong>Date:</strong> <?= htmlspecialchars($date) ?></div>
                        <div class="col-md-4"><strong>Estimate #:</strong><?= htmlspecialchars($estimate_no) ?></div>
                    </div>
                </div>
            </div>
            <!-- Hidden fields to send all data -->
            <input type="hidden" name="full_name" value="<?= htmlspecialchars($full_name) ?>">
            <input type="hidden" name="mobile" value="<?= htmlspecialchars($mobile) ?>">
            <input type="hidden" name="city" value="<?= htmlspecialchars($city) ?>">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">
            <input type="hidden" name="estimate_no" value="<?= htmlspecialchars($estimate_no) ?>">
            <input type="hidden" name="discount" value="<?= htmlspecialchars($discount) ?>">

            <!-- Product Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <h6 class="mb-0">Product List</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Product ID</th>
                                    <th>Name</th>
                                    <th>Unit</th>
                                    <th>Rate</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($product_ids as $index => $pid): ?>
                                    <input type="hidden" name="product_id[]" value="<?= htmlspecialchars($pid) ?>">
                                    <input type="hidden" name="product_name[]" value="<?= htmlspecialchars($product_names[$index]) ?>">
                                    <input type="hidden" name="product_rate[]" value="<?= htmlspecialchars($rates[$index]) ?>">
                                    <input type="hidden" name="unit[]" value="<?= htmlspecialchars($units[$index]) ?>">
                                    <input type="hidden" name="product_qty[]" value="<?= htmlspecialchars($qtys[$index]) ?>">
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($pid) ?></td>
                                        <td><?= htmlspecialchars($product_names[$index]) ?></td>
                                        <td><?= htmlspecialchars($units[$index]) ?></td>
                                        <td><?= number_format($rates[$index], 2) ?></td>
                                        <td><?= $qtys[$index] ?></td>
                                        <td><?= number_format($amounts[$index], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                                    <td><?= number_format($subtotal, 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Discount (<?= $discount ?>%):</strong></td>
                                    <td>-<?= number_format($discount_amount, 2) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                    <td><strong><?= number_format($total, 2) ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
</body>

</html>