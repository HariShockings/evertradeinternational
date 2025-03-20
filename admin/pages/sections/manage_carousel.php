<?php
include('../functions/config.php');
$carouselQuery = "SELECT * FROM tbl_carousel ORDER BY display_order ASC";
$carouselResult = $conn->query($carouselQuery);
?>

<div class="container mt-4">
    <h2>Manage Carousel</h2>
    
    <!-- Add/Edit Form -->
    <div class="card mb-4" id="carouselForm" style="display: none;">
        <div class="card-header">
            <span id="formTitle">Add New Image</span>
            <button type="button" class="btn-close float-end" onclick="$('#carouselForm').hide()"></button>
        </div>
        <div class="card-body">
            <form id="mainForm" enctype="multipart/form-data">
                <input type="hidden" id="editId" name="id">
                <div class="row">
                    <div class="col-md-6">
                        <label>Image</label>
                        <input type="file" class="form-control" name="image" id="imageInput">
                        <div id="imagePreview" class="mt-2"></div>
                        <input type="hidden" name="remove_image" id="removeImage" value="0">
                        <button type="button" class="btn btn-danger btn-sm mt-2" id="removeImageBtn" 
                                style="display: none;">Remove Image</button>
                    </div>
                    <div class="col-md-6">
                        <label>Alt Text</label>
                        <input type="text" class="form-control" name="alt_text" required>
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="$('#carouselForm').hide()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Carousel Table -->
    <button class="btn btn-primary mb-3" onclick="showAddForm()">
        <i class="fas fa-plus"></i> Add New
    </button>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th style="width: 50px;">Order</th>
                <th style="width: 200px;">Image</th>
                <th>Alt Text</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody id="sortable">
            <?php if($carouselResult->num_rows > 0): 
                while($row = $carouselResult->fetch_assoc()): ?>
                <tr data-id="<?= $row['id'] ?>">
                    <td class="handle">☰</td>
                    <td>
                        <img src="../uploads/<?= $row['image_url'] ?>" 
                             class="img-thumbnail" style="max-height: 100px;">
                    </td>
                    <td><?= htmlspecialchars($row['alt_text']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editItem(<?= $row['id'] ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteItem(<?= $row['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No carousel images found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Initialize sortable
    $("#sortable").sortable({
        handle: ".handle",
        update: function() {
            const order = $("#sortable tr").map((i,tr) => $(tr).data('id')).get();
            $.post("functions/update_carousel_order.php", {order, table: 'carousel'}, () => {
                location.reload();
            });
        }
    });

    // Image preview
    $("#imageInput").change(function(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $("#imagePreview").html(`<img src="${e.target.result}" class="img-thumbnail">`);
                $("#removeImageBtn").show();
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove image button
    $("#removeImageBtn").click(function() {
        $("#imagePreview").html('');
        $("#removeImage").val('1');
        $(this).hide();
    });

    // Form submission
    $("#mainForm").submit(function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        $.ajax({
            url: "functions/save_carousel.php",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: (res) => {
                if(res === 'success') location.reload();
                else alert('Error: ' + res);
            }
        });
    });
});

function showAddForm() {
    $("#formTitle").text('Add New Image');
    $("#carouselForm").show();
    $("#mainForm")[0].reset();
    $("#imagePreview").empty();
    $("#removeImageBtn").hide();
    $("#editId").val('');
}

function editItem(id) {
    $.post("functions/get_carousel_item.php", {id, table: 'carousel'}, (item) => {
        item = JSON.parse(item);
        $("#formTitle").text('Edit Image');
        $("#carouselForm").show();
        $("#editId").val(id);
        $("[name='alt_text']").val(item.alt_text);
        
        if(item.image_url) {
            $("#imagePreview").html(
                `<img src="../uploads/${item.image_url}" class="img-thumbnail">`
            );
            $("#removeImageBtn").show();
        }
    });
}

function deleteItem(id) {
    if(confirm('Are you sure?')) {
        $.post("functions/delete_carousel.php", {id, table: 'carousel'}, () => {
            location.reload();
        });
    }
}
</script>