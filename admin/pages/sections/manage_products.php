<?php
include('../functions/config.php'); // your DB connection file

// Fetch categories for use in the product form dropdown and for inline quick edit
$catQuery = "SELECT * FROM tbl_category";
$catResult = $conn->query($catQuery);
$catOptions = "";
$catArray = [];
if ($catResult->num_rows > 0) {
    while ($cat = $catResult->fetch_assoc()) {
        $catOptions .= "<option value='{$cat['id']}'>{$cat['name']}</option>";
        $catArray[] = $cat;
    }
}

// Now fetch products with join to get category name
$productQuery = "SELECT h.*, c.name AS category_name 
                 FROM tbl_hardware h 
                 LEFT JOIN tbl_category c ON h.category_id = c.id";
$productResult = $conn->query($productQuery);
?>
  
<div class="container mt-4">
    <div class="d-flex align-items-center my-2">
        <div class="">
            <input type="text" id="searchInput" class="form-control" placeholder="Search products..." aria-label="Search">
        </div>
        <button class="btn btn-sm btn-primary mx-3" onclick="showAddForm()"> 
            <i class="fas fa-plus"></i> Add New
        </button>
    </div>
    <!-- Product Form (Hidden by default) -->
    <div class="card mb-4 product-form" id="productForm" style="display: none;">
        <div class="card-header">
            <span id="formTitle">Add New Product</span>
            <button type="button" class="btn-close float-end" aria-label="Close" onclick="closeForm()"></button>
        </div>
        <div class="card-body">
            <form id="mainProductForm">
                <input type="hidden" id="editId">
                <div class="row">
                    <div class="col-md-4">
                        <label>Name</label>
                        <input type="text" class="form-control" id="name" required>
                    </div>
                    <div class="col-md-4">
                        <label>Price</label>
                        <input type="number" class="form-control" id="price" step="0.01" required>
                    </div>
                    <div class="col-md-4">
                        <label>Stock</label>
                        <input type="number" class="form-control" id="stock" required>
                    </div>
                    <!-- Replace Category ID text input with dropdown -->
                    <div class="col-md-4">
                        <label>Category</label>
                        <select class="form-control" id="category_id" required>
                            <?php echo $catOptions; ?>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label>Description</label>
                        <textarea class="form-control" id="description" rows="2"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Page Slug</label>
                        <input type="text" class="form-control" id="page_slug">
                    </div>
                    <div class="col-md-6">
                        <label>Use Cases</label>
                        <input type="textarea" class="form-control" id="use_cases">
                    </div>
                    <!-- Images Section -->
                    <div class="col-md-12 mt-3">
                        <label>Images</label>
                        <div id="imageList" class="d-flex flex-wrap gap-2"></div>
                        <button type="button" class="btn btn-sm btn-info mt-2" id="uploadBtn">
                            <i class="fas fa-upload"></i> Upload Image
                        </button>
                        <input type="file" id="imageFile" style="display:none" multiple>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="closeForm()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <table class="table table-bordered w-auto">
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($productResult->num_rows > 0) {
                while ($row = $productResult->fetch_assoc()) {
                    // Use the joined category_name and store category id in a data attribute
                    echo "<tr data-id='{$row['id']}'>
                            <td class='editable name'>{$row['name']}</td>
                            <td class='editable price'>{$row['price']}</td>
                            <td class='editable stock'>{$row['stock']}</td>
                            <td class='editable category_id' data-id='{$row['category_id']}'>{$row['category_name']}</td>
                            <td class='action-cell'>
                                <button class='btn btn-warning btn-sm quick-edit'><i class='fas fa-edit'></i></button>
                                <button class='btn btn-danger btn-sm delete-btn'><i class='fas fa-trash'></i></button>
                                <button class='btn btn-info btn-sm full-edit' onclick='showEditForm({$row['id']})'>
                                    <i class='fas fa-pen'></i> Full Edit
                                </button>
                            </td>
                          </tr>";
                }
            }
            ?>
        </tbody>
    </table>
</div>

<script>
// Global images array to store image URLs for the form
var imagesArray = [];

// Populate the image preview list in the form
function populateImageList(images) {
    $('#imageList').empty();
    images.forEach(function(image, index) {
        $('#imageList').append(`
            <div class="image-item" data-index="${index}" style="position:relative; display:inline-block; margin:5px;">
                <img src="../uploads/${image}" alt="Image" style="width:100px; height:100px; object-fit:cover; border:1px solid #ccc;">
                <button type="button" class="btn btn-danger btn-sm remove-image" 
                    style="position:absolute; top:0; right:0;">&times;</button>
            </div>
        `);
    });
}

$(document).ready(function() {

    // Show add form
    window.showAddForm = function() {
        $('#productForm').show();
        $('#formTitle').text('Add New Product');
        $('#mainProductForm')[0].reset();
        $('#editId').val('');
        imagesArray = [];
        $('#imageList').empty();
    }

    // Close form
    window.closeForm = function() {
        $('#productForm').hide();
    }

    // Show full edit form
    window.showEditForm = function(id) {
        $.post('functions/get_product.php', {id: id}, function(response) {
            const product = JSON.parse(response);
            $('#editId').val(product.id);
            $('#name').val(product.name);
            $('#price').val(product.price);
            $('#stock').val(product.stock);
            $('#description').val(product.description);
            $('#page_slug').val(product.page_slug);
            $('#use_cases').val(product.use_cases);
            // Set category dropdown to the product's category id
            $('#category_id').val(product.category_id);
            // Populate images (images stored as JSON array)
            imagesArray = JSON.parse(product.images);
            populateImageList(imagesArray);
            $('#formTitle').text('Edit Product');
            $('#productForm').show();
        });
    }

    // Trigger file upload when upload button is clicked
    $('#uploadBtn').click(function(){
        $('#imageFile').click();
    });

    // Handle file upload
    $('#imageFile').change(function(){
    var files = this.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var formData = new FormData();
        formData.append('image', file);
        $.ajax({
            url: 'functions/upload_image.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response){
                // Assuming response is the preview URL/path (e.g., "filename.jpg")
                imagesArray.push(response);
                populateImageList(imagesArray);
            },
            error: function(){
                alert('Image upload failed.');
            }
        });
    }
});

    // Remove an image from the list
    $(document).on('click', '.remove-image', function(){
        var index = $(this).closest('.image-item').data('index');
        imagesArray.splice(index, 1);
        populateImageList(imagesArray);
    });

    // Handle form submission (Add/Update product)
    $('#mainProductForm').submit(function(e) {
        e.preventDefault();
        const formData = {
            id: $('#editId').val(),
            name: $('#name').val(),
            price: $('#price').val(),
            stock: $('#stock').val(),
            category_id: $('#category_id').val(),
            images: JSON.stringify(imagesArray),
            description: $('#description').val(),
            page_slug: $('#page_slug').val(),
            use_cases: $('#use_cases').val()
        };

        const url = formData.id ? 'functions/update_product.php' : 'functions/add_product.php';
        
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
            // For category cell, replace with a dropdown using the global categories variable (populated below)
            if(cell.hasClass('category_id')) {
                var currentId = cell.data('id');
                cell.data('originalId', currentId);
                var select = '<select class="form-control form-control-sm">';
                // Use the global categories variable to build options
                categories.forEach(function(cat) {
                    select += '<option value="'+cat.id+'"'+ (cat.id == currentId ? ' selected' : '') +'>'+cat.name+'</option>';
                });
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
            if(cell.hasClass('category_id')){
                // Revert to original text and data-id remains unchanged
                var origId = cell.data('originalId');
                // Find category name from the global categories list
                var origName = '';
                categories.forEach(function(cat) {
                    if(cat.id == origId) { origName = cat.name; }
                });
                cell.text(origName);
                cell.attr('data-id', origId);
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
        // For category cell, get the selected value and its text
        row.find('.editable').each(function() {
            var cell = $(this);
            if(cell.hasClass('category_id')){
                var newId = cell.find('select').val();
                var newName = cell.find('select option:selected').text();
                cell.text(newName);
                cell.attr('data-id', newId);
                cell.data('newId', newId);
            } else {
                var newVal = cell.find('input').val();
                cell.text(newVal);
            }
        });
        // Build data for quick update, including the category id from the edited cell
        var data = {
            id: row.data('id'),
            name: row.find('.name').text(),
            price: row.find('.price').text(),
            stock: row.find('.stock').text(),
            category_id: row.find('.category_id').data('newId') || row.find('.category_id').data('originalId')
        };

        $.post('functions/quick_update_products.php', data, function(response) {
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
        if (confirm('Are you sure you want to delete this product?')) {
            const id = $(this).closest('tr').data('id');
            $.post('functions/delete_product.php', {id: id}, function(response) {
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

});

// Pass categories array to JavaScript for inline quick edit dropdowns
var categories = <?php echo json_encode($catArray); ?>;
</script>