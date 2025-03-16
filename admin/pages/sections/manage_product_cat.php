<?php
include('../functions/config.php'); // Include your database connection file
?>
    <style>
        .editable-input {
            width: 100%;
        }
        .editable {
            pointer-events: auto;
        }
    </style>

    <div class="container mt-4">        
        <table class="table table-bordered w-auto" id="categoryTable">
            <thead>
                <tr>
                    <th>Manage Categories</th>
                    <th>Actions <span class="badge bg-primary add-new-badge" id="addCategoryItem">New</span></th>
                </tr>
            </thead>
            <tbody id="categoryList">
                <?php
                // Fetch categories from the database
                $categoryQuery = "SELECT * FROM tbl_category";
                $categoryResult = $conn->query($categoryQuery);

                if ($categoryResult->num_rows > 0) {
                    while ($row = $categoryResult->fetch_assoc()) {
                        echo "<tr data-id='{$row['id']}'>
                            <td><input type='text' class='form-control editable-input name' value='{$row['name']}' disabled></td>
                            <td>
                                <button class='btn btn-success btn-sm save-btn d-none' data-id='{$row['id']}'>Save</button>
                                <button class='btn btn-warning btn-sm edit-btn' data-id='{$row['id']}'><i class='fas fa-pen'></i></button>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='{$row['id']}'><i class='fas fa-trash'></i></button>
                            </td>
                        </tr>";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // Delete functionality
            $(".delete-btn").click(function () {
                if (confirm("Are you sure you want to delete this category?")) {
                    var id = $(this).data("id");
                    console.log("Deleting ID:", id); // Debugging
                    $.post("functions/delete_category.php", { id: id }, function(response) {
                        console.log("Delete Response:", response); // Debugging
                        if (response == "success") {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Delete failed!");
                        }
                    });
                }
            });

            // Edit functionality
            $(".edit-btn").click(function () {
                var row = $(this).closest("tr");
                row.find(".editable-input").prop("disabled", false).addClass("table-warning");
                row.find(".save-btn").removeClass("d-none");
                $(this).addClass("d-none");
            });

            // Save functionality
            $(".save-btn").click(function () {
                var row = $(this).closest("tr");
                var id = row.data("id");
                var name = row.find(".name").val();

                console.log("Saving Data:", { id: id, name: name }); // Debugging
                $.post("functions/update_category.php", { id: id, name: name }, function(response) {
                    console.log("Update Response:", response); // Debugging
                    if (response == "success") {
                        row.find(".editable-input").prop("disabled", true).removeClass("table-warning");
                        row.find(".edit-btn").removeClass("d-none");
                        row.find(".save-btn").addClass("d-none");
                    } else {
                        alert("Update failed!");
                    }
                });
            });

            // Double-click to edit
            $(".editable-input").dblclick(function () {
                var row = $(this).closest("tr");
                row.find(".editable-input").prop("disabled", false).addClass("table-warning");
                row.find(".save-btn").removeClass("d-none");
                row.find(".edit-btn").addClass("d-none");
            });

            // Add new row functionality
            $("#addCategoryItem").click(function () {
                // Create a new row with input fields
                var newRow = `
                    <tr data-id="new">
                        <td><input type='text' class='form-control editable-input name' placeholder='Enter Category Name'></td>
                        <td>
                            <button class='btn btn-success btn-sm save-new-btn'>Save</button>
                            <button class='btn btn-secondary btn-sm cancel-new-btn'>Cancel</button>
                        </td>
                    </tr>
                `;

                // Append the new row after the table header
                $("#categoryList").prepend(newRow);
            });

            // Save new row functionality
            $(document).on("click", ".save-new-btn", function () {
                var row = $(this).closest("tr");
                var name = row.find(".name").val();

                if (name) {
                    console.log("Adding New Data:", { name: name }); // Debugging
                    $.post("functions/add_category.php", { name: name }, function(response) {
                        console.log("Add New Response:", response); // Debugging
                        if (response == "success") {
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Failed to add new category!");
                        }
                    });
                } else {
                    alert("Category name is required!");
                }
            });

            // Cancel new row functionality
            $(document).on("click", ".cancel-new-btn", function () {
                $(this).closest("tr").remove(); // Remove the new row
            });
        });
    </script>