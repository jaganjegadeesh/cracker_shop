<?php
include "../db.php";

$sql = "SELECT * FROM shop_product";
$result = $conn->query($sql);
$product_list = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $product_list[] = $row;
    }
} else {
    $product_list = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Estimate Form</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .product-row .form-control { min-width: 120px; }
    .product-row { gap: 10px; margin-bottom: 10px; }
    .invalid-feedback {
        display: block; /* Ensure visible when inserted */
    }
  </style>
</head>
<body>
<div class="container mt-5">
  <div class="card shadow">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0">Estimate Form</h4>
    </div>
    <div class="card-body">
      <form method="POST" action="invoice_preview.php">
        <!-- Form Fields -->
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-control">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Mobile</label>
            <input type="tel" class="form-control" name="mobile" pattern="[0-9]{10}">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">City</label>
            <input type="text" class="form-control" name="city">
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Date</label>
            <input type="date" class="form-control" name="date">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Estimate #</label>
            <input type="number" class="form-control" name="estimate_no">
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">Discount (%)</label>
            <input type="number" class="form-control" name="discount" min="0" max="100">
          </div>
        </div>

        <!-- Product Section -->
        <div class="d-flex justify-content-between align-items-center mb-3 mt-4">
          <h5 class="mb-0">Product Items</h5>
          <button type="button" class="btn btn-outline-primary" onclick="addProductRow()">Add Item</button>
        </div>

        <div id="product-items"></div>

        <div class="mt-4">
          <button type="submit" class="btn btn-success">Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Product List for JS -->
<script>
  const productList = <?php echo json_encode($product_list); ?>;

  function addProductRow() {
  const container = document.getElementById("product-items");
console.log(productList);
  const row = document.createElement("div");
  row.className = "row g-2 align-items-end mb-2";

  // Product ID
  const idCol = document.createElement("div");
  idCol.className = "col-12 col-md-2";
  const idSelect = document.createElement("select");
  idSelect.className = "form-select product-id";
  idSelect.name = "product_id[]";
  idSelect.innerHTML = `<option value="">By ID</option>` +
    productList.map(prod => `<option value="${prod.id}">${prod.id}</option>`).join("");
  idCol.appendChild(idSelect);

  // Product Name
  const nameCol = document.createElement("div");
  nameCol.className = "col-12 col-md-2";
  const nameSelect = document.createElement("select");
  nameSelect.className = "form-select product-name";
  nameSelect.name = "product_name[]";
  nameSelect.innerHTML = `<option value="">By Name</option>` +
    productList.map(prod =>
      `<option value="${prod.product_name}" data-rate="${prod.rate}" data-id="${prod.id}" data-unit="${prod.unit}">${prod.product_name}</option>`
    ).join("");
  nameCol.appendChild(nameSelect);

  // Unit
  const unitCol = document.createElement("div");
  unitCol.className = "col-12 col-md-2";
  const unitInput = document.createElement("input");
  unitInput.className = "form-control unit";
  unitInput.name = "unit[]";
  unitInput.type = "text";
  unitInput.placeholder = "Unit";
  unitInput.readOnly = true;
  unitCol.appendChild(unitInput);

  // Rate
  const rateCol = document.createElement("div");
  rateCol.className = "col-12 col-md-1";
  const rateInput = document.createElement("input");
  rateInput.className = "form-control rate";
  rateInput.name = "product_rate[]";
  rateInput.type = "number";
  rateInput.placeholder = "Rate";
  rateInput.readOnly = true;
  rateCol.appendChild(rateInput);

  // Quantity
  const qtyCol = document.createElement("div");
  qtyCol.className = "col-12 col-md-2";
  const qtyInput = document.createElement("input");
  qtyInput.className = "form-control qty";
  qtyInput.name = "product_qty[]";
  qtyInput.type = "number";
  qtyInput.placeholder = "Qty";
  qtyInput.min = 1;
  qtyInput.value = 1;
  qtyCol.appendChild(qtyInput);

  // Amount
  const amtCol = document.createElement("div");
  amtCol.className = "col-12 col-md-1";
  const amtInput = document.createElement("input");
  amtInput.className = "form-control amount";
  amtInput.name = "product_amount[]";
  amtInput.type = "number";
  amtInput.placeholder = "Amount";
  amtInput.readOnly = true;
  amtCol.appendChild(amtInput);

  // Remove button
  const btnCol = document.createElement("div");
  btnCol.className = "col-12 col-md-2";
  const removeBtn = document.createElement("button");
  removeBtn.className = "btn btn-danger w-100";
  removeBtn.type = "button";
  removeBtn.innerText = "Remove";
  btnCol.appendChild(removeBtn);

  // Sync behavior
  idSelect.onchange = function () {
    const selected = productList.find(p => p.id == this.value);
    if (selected) {
      nameSelect.value = selected.product_name;
      rateInput.value = selected.rate;
      unitInput.value = selected.unit;
      updateAmount(row);
    }
  };
  nameSelect.onchange = function () {
    const selectedOption = this.options[this.selectedIndex];
    const productId = selectedOption.getAttribute('data-id');
    const unit = selectedOption.getAttribute('data-unit');
    const rate = selectedOption.getAttribute('data-rate');
    idSelect.value = productId;
    unitInput.value = unit;
    rateInput.value = rate;
    updateAmount(row);
  };
  qtyInput.oninput = () => updateAmount(row);
  removeBtn.onclick = () => row.remove();

  row.appendChild(idCol);
  row.appendChild(nameCol);
  row.appendChild(unitCol);
  row.appendChild(rateCol);
  row.appendChild(qtyCol);
  row.appendChild(amtCol);
  row.appendChild(btnCol);

  container.appendChild(row);
}


  function updateAmount(row) {
    const rate = parseFloat(row.querySelector(".rate").value) || 0;
    const qty = parseFloat(row.querySelector(".qty").value) || 0;
    row.querySelector(".amount").value = rate * qty;
  }
  document.querySelector('form').addEventListener('submit', function(event) {
  event.preventDefault();

  // Clear previous errors
  const invalids = this.querySelectorAll('.is-invalid');
  invalids.forEach(el => el.classList.remove('is-invalid'));
  const feedbacks = this.querySelectorAll('.invalid-feedback');
  feedbacks.forEach(el => el.remove());

  let isValid = true;

  // Helper to add error message
  function showError(input, message) {
    input.classList.add('is-invalid');
    const feedback = document.createElement('div');
    feedback.className = 'invalid-feedback';
    feedback.innerText = message;
    input.insertAdjacentElement('afterend', feedback);
    isValid = false;
  }

  // Validate main fields
  const nameInput = this.querySelector('input[type="text"]');
  if (!nameInput.value.trim()) showError(nameInput, 'Please enter your full name.');

  const mobileInput = this.querySelector('input[type="tel"]');
  if (!mobileInput.value.trim() || !mobileInput.value.match(/^\d{10}$/))
    showError(mobileInput, 'Please enter a valid 10-digit mobile number.');

  const cityInput = this.querySelectorAll('input[type="text"]')[1];
  if (!cityInput.value.trim()) showError(cityInput, 'Please enter your city.');

  const dateInput = this.querySelector('input[type="date"]');
  if (!dateInput.value) showError(dateInput, 'Please select a date.');

  const estimateInput = this.querySelector('input[type="number"]');
  if (!estimateInput.value || estimateInput.value <= 0)
    showError(estimateInput, 'Please enter a valid estimate number.');

  const discountInput = this.querySelector('input[type="number"][min="0"]');
  if (discountInput.value) {
    const discNum = Number(discountInput.value);
    if (discNum < 0 || discNum > 100)
      showError(discountInput, 'Discount must be between 0 and 100.');
  }

  // Validate product items
  const productRows = this.querySelectorAll('#product-items .row');
  if (productRows.length === 0) {
    alert('Please add at least one product item.');
    isValid = false;
  }

  productRows.forEach(row => {
    const idSelect = row.querySelector('.product-id');
    const nameSelect = row.querySelector('.product-name');
    const qtyInput = row.querySelector('input.qty');

    if (!idSelect.value) showError(idSelect, 'Please select a product ID.');
    if (!nameSelect.value) showError(nameSelect, 'Please select a product name.');
    if (!qtyInput.value || qtyInput.value <= 0)
      showError(qtyInput, 'Please enter a quantity greater than 0.');
  });

  if (isValid) {
    this.submit();
  }
});


</script>
</body>
</html>

