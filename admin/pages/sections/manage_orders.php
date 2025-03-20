<?php
include('../functions/config.php'); // your DB connection file

// Fetch products for use in the order form dropdown
$productQuery = "SELECT * FROM tbl_hardware";
$productResult = $conn->query($productQuery);
$productOptions = "";
$productArray = [];
if ($productResult->num_rows > 0) {
    while ($product = $productResult->fetch_assoc()) {
        $productOptions .= "<option value='{$product['id']}' data-price='{$product['price']}'>{$product['name']}</option>";
        $productArray[] = $product;
    }
}

// Now fetch orders with join to get product name and price
$orderQuery = "SELECT o.*, h.name AS product_name, h.price AS product_price 
               FROM tbl_orders o 
               LEFT JOIN tbl_hardware h ON o.product_id = h.id";
$orderResult = $conn->query($orderQuery);
?>
  
<div class="container mt-4">
    <div class="d-flex align-items-center my-2">
        <div class="">
            <input type="text" id="searchInput" class="form-control" placeholder="Search orders..." aria-label="Search">
        </div>
        <div class="d-flex justify-content-between align-items-start">
        <button class="btn btn-sm btn-primary" onclick="showAddForm()"> 
            <i class="fas fa-plus"></i> Add New
        </button>
        <!-- New Export Buttons -->
        <button class="btn btn-sm btn-success" onclick="exportToExcel()"> 
            <i class="fas fa-file-excel"></i> Export to Excel
        </button>
        <button class="btn btn-sm btn-danger" onclick="exportToPDF()"> 
            <i class="fas fa-file-pdf"></i> Export to PDF
        </button>
        </div>
    </div>
    <!-- Order Form (Hidden by default) -->
    <div class="card mb-4 order-form" id="orderForm" style="display: none;">
        <div class="card-header">
            <span id="formTitle">Add New Order</span>
            <button type="button" class="btn-close float-end" aria-label="Close" onclick="closeForm()"></button>
        </div>
        <div class="card-body">
            <form id="mainOrderForm">
                <input type="hidden" id="editId">
                <div class="row">
                    <div class="col-md-4">
                        <label>Product</label>
                        <select class="form-control" id="product_id" required>
                            <?php echo $productOptions; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Quantity</label>
                        <input type="number" class="form-control" id="quantity" required>
                    </div>
                    <div class="col-md-4">
                        <label>Order Amount</label>
                        <input type="number" class="form-control" id="order_amount" readonly>
                    </div>
                    <div class="col-md-4">
                        <label>Status</label>
                        <select class="form-control" id="status" required>
                            <option value="pending">Pending</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="closeForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <table class="table table-bordered w-auto" id="ordersTable">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Order Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($orderResult->num_rows > 0) {
                while ($row = $orderResult->fetch_assoc()) {
                    echo "<tr data-id='{$row['id']}'>
                            <td class='editable product_id' data-id='{$row['product_id']}'>{$row['product_name']}</td>
                            <td class='editable quantity'>{$row['quantity']}</td>
                            <td class='editable order_amount'>{$row['order_amount']}</td>
                            <td class='editable status'>{$row['status']}</td>
                            <td class='action-cell'>
                                <button class='btn btn-warning btn-sm quick-edit'><i class='fas fa-edit'></i></button>
                                <button class='btn btn-danger btn-sm delete-btn'><i class='fas fa-trash'></i></button>
                                <button class='btn btn-info btn-sm full-edit' onclick='showEditForm({$row['id']})'>
                                    <i class='fas fa-pen'></i> Full Edit
                                </button>
                                <!-- Add this button for quick status toggle -->
                                <button class='btn btn-secondary btn-sm toggle-status' data-id='{$row['id']}' data-status='{$row['status']}'>
                                    <i class='fas fa-sync'></i>
                                </button>
                            </td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- jsPDF Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- jsPDF AutoTable Plugin -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
$(document).ready(function() {

    // Show add form
    window.showAddForm = function() {
        $('#orderForm').show();
        $('#formTitle').text('Add New Order');
        $('#mainOrderForm')[0].reset();
        $('#editId').val('');
    }

    // Close form
    window.closeForm = function() {
        $('#orderForm').hide();
    }

    // Show full edit form
    window.showEditForm = function(id) {
        $.post('functions/get_order.php', {id: id}, function(response) {
            const order = JSON.parse(response);
            $('#editId').val(order.id);
            $('#product_id').val(order.product_id);
            $('#quantity').val(order.quantity);
            $('#order_amount').val(order.order_amount);
            $('#status').val(order.status);
            $('#formTitle').text('Edit Order');
            $('#orderForm').show();
        });
    }

    // Calculate order amount based on product price and quantity
    $('#product_id, #quantity').on('change', function() {
        const productId = $('#product_id').val();
        const quantity = $('#quantity').val();
        const productPrice = $('#product_id option:selected').data('price');
        const orderAmount = productPrice * quantity;
        $('#order_amount').val(orderAmount.toFixed(2));
    });

    // Handle form submission (Add/Update order)
    $('#mainOrderForm').submit(function(e) {
        e.preventDefault();
        const formData = {
            id: $('#editId').val(),
            product_id: $('#product_id').val(),
            quantity: $('#quantity').val(),
            order_amount: $('#order_amount').val(),
            status: $('#status').val()
        };

        const url = formData.id ? 'functions/update_nav_order.php' : 'functions/add_order.php';
        
        $.post(url, formData, function(response) {
            if (response.trim() === 'success') {
                location.reload();
            } else {
                alert('Operation failed: ' + response);
            }
        });
    });

    // Quick edit: transform row cells into inline editable inputs/selects
    $(document).on('click', '.quick-edit', function() {
        const row = $(this).closest('tr');
        row.find('.editable').each(function() {
            var cell = $(this);
            if(cell.hasClass('product_id')) {
                var currentId = cell.data('id');
                cell.data('originalId', currentId);
                var select = '<select class="form-control form-control-sm">';
                products.forEach(function(product) {
                    select += '<option value="'+product.id+'"'+ (product.id == currentId ? ' selected' : '') +'>'+product.name+'</option>';
                });
                select += '</select>';
                cell.html(select);
            } else if(cell.hasClass('status')) {
                var currentStatus = cell.text();
                cell.data('originalStatus', currentStatus);
                var select = '<select class="form-control form-control-sm">';
                select += '<option value="pending"'+ (currentStatus == 'pending' ? ' selected' : '') +'>Pending</option>';
                select += '<option value="done"'+ (currentStatus == 'done' ? ' selected' : '') +'>Done</option>';
                select += '</select>';
                cell.html(select);
            } else {
                cell.data('original', cell.text());
                var value = cell.text();
                cell.html('<input type="text" class="form-control form-control-sm" value="'+value+'">');
            }
        });
        // Save original action buttons
        const actionCell = row.find('.action-cell');
        actionCell.data('originalButtons', actionCell.html());
        // Replace action cell with Save and Cancel buttons
        actionCell.html(`
            <button class="btn btn-success btn-sm quick-save"><i class="fas fa-save"></i> Save</button>
            <button class="btn btn-secondary btn-sm quick-cancel"><i class="fas fa-times"></i> Cancel</button>
        `);
    });

    // Quick cancel: revert inline edit changes
    $(document).on('click', '.quick-cancel', function() {
        const row = $(this).closest('tr');
        row.find('.editable').each(function() {
            var cell = $(this);
            if(cell.hasClass('product_id')){
                var origId = cell.data('originalId');
                var origName = '';
                products.forEach(function(product) {
                    if(product.id == origId) { origName = product.name; }
                });
                cell.text(origName);
                cell.attr('data-id', origId);
            } else if(cell.hasClass('status')) {
                cell.text(cell.data('originalStatus'));
            } else {
                cell.text(cell.data('original'));
            }
        });
        const actionCell = row.find('.action-cell');
        const originalButtons = actionCell.data('originalButtons');
        actionCell.html(originalButtons);
    });

    // Quick save: save inline edits
    $(document).on('click', '.quick-save', function() {
        const row = $(this).closest('tr');
        row.find('.editable').each(function() {
            var cell = $(this);
            if(cell.hasClass('product_id')){
                var newId = cell.find('select').val();
                var newName = cell.find('select option:selected').text();
                cell.text(newName);
                cell.attr('data-id', newId);
                cell.data('newId', newId);
            } else if(cell.hasClass('status')) {
                var newStatus = cell.find('select').val();
                cell.text(newStatus);
            } else {
                var newVal = cell.find('input').val();
                cell.text(newVal);
            }
        });
        // Build data for quick update
        var data = {
            id: row.data('id'),
            product_id: row.find('.product_id').data('newId') || row.find('.product_id').data('originalId'),
            quantity: row.find('.quantity').text(),
            status: row.find('.status').text()
        };

        $.post('functions/quick_update_orders.php', data, function(response) {
            if (response.trim() === 'success') {
                // Restore original action buttons
                const actionCell = row.find('.action-cell');
                actionCell.html(`
                    <button class="btn btn-warning btn-sm quick-edit"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-danger btn-sm delete-btn"><i class="fas fa-trash"></i></button>
                    <button class="btn btn-info btn-sm full-edit" onclick="showEditForm(${row.data('id')})">
                        <i class="fas fa-pen"></i> Full Edit
                    </button>
                `);
            } else {
                alert('Update failed: ' + response);
            }
        });
    });

    // Delete functionality
    $(document).on('click', '.delete-btn', function() {
        if (confirm('Are you sure you want to delete this order?')) {
            const id = $(this).closest('tr').data('id');
            $.post('functions/delete_order.php', {id: id}, function(response) {
                if (response.trim() === 'success') {
                    location.reload();
                } else {
                    alert('Delete failed: ' + response);
                }
            });
        }
    });

    let debounceTimer;
    $('#searchInput').on('keyup', function() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            const searchText = $(this).val().toLowerCase();
            $('table tbody tr').each(function() {
                const rowText = $(this).text().toLowerCase();
                if (rowText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }, 300); // Adjust the delay as needed
    });

    // Handle status toggle button click
    $(document).on('click', '.toggle-status', function() {
    const button = $(this);
    const id = button.data('id');
    const currentStatus = button.data('status');
    const newStatus = currentStatus === 'pending' ? 'done' : 'pending';

    // Update the status in the database
    $.post('functions/quick_update_order_status.php', { id: id, status: newStatus }, function(response) {
        if (response.trim() === 'success') {
            // Update the button's data-status and the table cell
            button.data('status', newStatus);
            button.closest('tr').find('.status').text(newStatus);
        } else {
            alert('Failed to update status: ' + response);
        }
    });
    });

    // Quick edit: transform row cells into inline editable inputs/selects
    $(document).on('click', '.quick-edit', function() {
    const row = $(this).closest('tr');
    row.find('.editable').each(function() {
        var cell = $(this);
        if (cell.hasClass('product_id')) {
            var currentId = cell.data('id');
            cell.data('originalId', currentId);
            var select = '<select class="form-control form-control-sm">';
            products.forEach(function(product) {
                select += '<option value="' + product.id + '"' + (product.id == currentId ? ' selected' : '') + '>' + product.name + '</option>';
            });
            select += '</select>';
            cell.html(select);
        } else if (cell.hasClass('status')) {
            var currentStatus = cell.text();
            cell.data('originalStatus', currentStatus);
            var select = '<select class="form-control form-control-sm">';
            select += '<option value="pending"' + (currentStatus == 'pending' ? ' selected' : '') + '>Pending</option>';
            select += '<option value="done"' + (currentStatus == 'done' ? ' selected' : '') + '>Done</option>';
            select += '</select>';
            cell.html(select);
        } else {
            cell.data('original', cell.text());
            var value = cell.text();
            cell.html('<input type="text" class="form-control form-control-sm" value="' + value + '">');
        }
    });
    // Save original action buttons
    const actionCell = row.find('.action-cell');
    actionCell.data('originalButtons', actionCell.html());
    // Replace action cell with Save and Cancel buttons
    actionCell.html(`
        <button class="btn btn-success btn-sm quick-save"><i class="fas fa-save"></i> Save</button>
        <button class="btn btn-secondary btn-sm quick-cancel"><i class="fas fa-times"></i> Cancel</button>
    `);
    });

});

// Pass products array to JavaScript for inline quick edit dropdowns
var products = <?php echo json_encode($productArray); ?>;

// Function to export table data to Excel (excluding the "Actions" column)
function exportToExcel() {
    const table = document.getElementById('ordersTable');
    const rows = table.querySelectorAll('tr');
    const data = [];

    // Loop through rows and exclude the "Actions" column
    rows.forEach((row, rowIndex) => {
        const rowData = [];
        const cols = row.querySelectorAll('td, th');

        cols.forEach((col, colIndex) => {
            // Skip the "Actions" column (last column)
            if (colIndex < cols.length - 1) {
                rowData.push(col.innerText);
            }
        });

        data.push(rowData);
    });

    // Convert data to worksheet
    const ws = XLSX.utils.aoa_to_sheet(data);
    const wb = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(wb, ws, "Orders");
    XLSX.writeFile(wb, "orders.xlsx");
}

// Function to export table data to PDF (excluding the "Actions" column)
function exportToPDF() {
    // Ensure jsPDF is available
    const { jsPDF } = window.jspdf;

    // Initialize jsPDF
    const doc = new jsPDF();

    const table = document.getElementById('ordersTable');
    const headers = [];
    const rows = [];

    // Extract headers (excluding the "Actions" column)
    table.querySelectorAll('thead th').forEach((th, index) => {
        if (index < table.querySelectorAll('thead th').length - 1) {
            headers.push(th.innerText);
        }
    });

    // Extract rows (excluding the "Actions" column)
    table.querySelectorAll('tbody tr').forEach((tr) => {
        const rowData = [];
        tr.querySelectorAll('td').forEach((td, index) => {
            if (index < tr.querySelectorAll('td').length - 1) {
                rowData.push(td.innerText);
            }
        });
        rows.push(rowData);
    });

    // Generate PDF using autoTable
    doc.autoTable({
        head: [headers],
        body: rows,
    });

    // Save the PDF
    doc.save('orders.pdf');
}
</script>