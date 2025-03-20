<?php
include('../functions/config.php');
$leadershipQuery = "SELECT * FROM tbl_leadership ORDER BY id ASC";
$leadershipResult = $conn->query($leadershipQuery);
?>

<div class="container mt-4">
    <h2>Manage Leadership</h2>
    
    <!-- Add/Edit Form -->
    <div class="card mb-4" id="leadershipForm" style="display: none;">
        <div class="card-header">
            <span id="formTitle">Add New Member</span>
            <button type="button" class="btn-close float-end" onclick="$('#leadershipForm').hide()"></button>
        </div>
        <div class="card-body">
            <form id="mainFormLeadership" enctype="multipart/form-data">
                <input type="hidden" id="editIdLeadership" name="id">
                <div class="row">
                    <div class="col-md-6">
                        <label>Image</label>
                        <input type="file" class="form-control" name="image" id="imageInput">
                        <div id="imagePreviewLeadership" class="mt-2"></div>
                        <input type="hidden" name="remove_image" id="removeImage" value="0">
                        <button type="button" class="btn btn-danger btn-sm mt-2" id="removeImageBtnLeadership" 
                                style="display: none;">Remove Image</button>
                    </div>
                    <div class="col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>Position</label>
                        <input type="text" class="form-control" name="position" required>
                    </div>
                    <div class="col-md-6 mt-2">
                        <label>Quote</label>
                        <input type="text" class="form-control" name="quote">
                    </div>
                </div>
                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-secondary" onclick="$('#leadershipForm').hide()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Leadership Table -->
    <button class="btn btn-primary mb-3" onclick="showAddFormLeadership()">
        <i class="fas fa-plus"></i> Add New
    </button>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th style="width: 200px;">Image</th>
                <th>Name</th>
                <th>Position</th>
                <th>Quote</th>
                <th style="width: 150px;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if($leadershipResult->num_rows > 0): 
                while($row = $leadershipResult->fetch_assoc()): ?>
                <tr>
                    <td>
                        <img src="../uploads/<?= $row['image_url'] ?>" 
                             class="img-thumbnail" style="max-height: 100px;">
                    </td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['position']) ?></td>
                    <td><?= htmlspecialchars($row['quote']) ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" onclick="editItemLeadership(<?= $row['id'] ?>)">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-danger btn-sm" onclick="deleteItemLeadership(<?= $row['id'] ?>)">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" class="text-center">No leadership members found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<script>
$(document).ready(function() {
    // Image preview functionality
    $("#imageInput").change(function(e) {
        const file = e.target.files[0];
        if(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                $("#imagePreviewLeadership").html('<img src="' + e.target.result + '" class="img-thumbnail">');
                $("#removeImageBtnLeadership").show();
            }
            reader.readAsDataURL(file);
        }
    });

    // Remove image functionality
    $("#removeImageBtnLeadership").click(function() {
        $("#imagePreviewLeadership").html('');
        $("#removeImage").val('1');
        $(this).hide();
    });

    // Handle form submission
    $("#mainFormLeadership").submit(function(e) {
        e.preventDefault();
        const formDataLeadership = new FormData(this); // Use new FormData(this)
        
        $.ajax({
            url: "functions/save_leadership.php",
            type: "POST",
            data: formDataLeadership,
            contentType: false,
            processData: false,
            success: (res) => {
                if (res.trim() === 'success') {
                    alert('Operation successful!');
                    $('#leadershipForm').hide();
                    location.reload();
                } else {
                    alert('Error: ' + res);
                }
            }
        });
    });

    // Cancel button - hide form
    $(".btn-secondary").click(function() {
        $('#leadershipForm').hide();
    });
});

// Show Add Form
function showAddFormLeadership() {
    $("#formTitle").text('Add New Member');
    $("#leadershipForm").show();
    $("#mainFormLeadership")[0].reset(); // Reset form for adding new member
    $("#imagePreviewLeadership").empty();
    $("#removeImageBtnLeadership").hide();
    $("#editIdLeadership").val('');
}

function editItemLeadership(id) {
    $.post("functions/get_leadership.php", {id}, (item) => {
        item = JSON.parse(item);
        $("#formTitle").text('Edit Member');
        $("#leadershipForm").show();
        $("#editIdLeadership").val(id);  // Ensure the ID is correctly set in the hidden field
        $("[name='name']").val(item.name);
        $("[name='position']").val(item.position);
        $("[name='quote']").val(item.quote);
        
        // Reset the remove image flag in case of editing
        $("#removeImage").val('0');
        
        // Handle image preview and remove button
        if(item.image_url) {
            // Set image preview with the image URL
            $("#imagePreviewLeadership").html('<img src="../uploads/' + item.image_url + '" class="img-thumbnail">');
            $("#removeImageBtnLeadership").show();  // Show the remove image button
        } else {
            $("#imagePreviewLeadership").empty();  // Clear the image preview if no image exists
            $("#removeImageBtnLeadership").hide();  // Hide the remove button if no image exists
        }
    });
}


// Delete Item
function deleteItemLeadership(id) {
    if (confirm('Are you sure?')) {
        $.post("functions/delete_leadership.php", {id}, (res) => {
            if (res.trim() === 'success') {
                alert('Item deleted successfully!');
                location.reload(); // Reload to reflect changes
            } else {
                alert('Error deleting item! ' + res);
            }
        });
    }
}
</script>


