
<div class="container mt-4">
    <h2>Manage Navigation</h2>
    
    <table class="table table-bordered w-auto" id="navTable">
        <thead>
            <tr>
                <th>Order</th>
                <th>Title</th>
                <th>Page Slug</th>
                <th>Actions <span class="badge bg-primary add-new-badge" id="addNavItem">New</span></th>
            </tr>
        </thead>
        <tbody id="sortable">
            <?php
            include('../functions/config.php');
            $navQuery = "SELECT * FROM tbl_navbar ORDER BY display_order ASC";
            $navResult = $conn->query($navQuery);

            if ($navResult->num_rows > 0) {
                while ($row = $navResult->fetch_assoc()) {
                    echo "<tr data-id='{$row['id']}'>
                        <td class='handle'>☰</td>
                        <td><input type='text' class='form-control editable-input title' value='{$row['title']}' disabled></td>
                        <td><input type='text' class='form-control editable-input page_slug' value='{$row['page_slug']}' disabled></td>
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
    // Enable sorting
    $("#sortable").sortable({
        handle: ".handle",
        update: function () {
            var order = [];
            $("#sortable tr").each(function () {
                order.push($(this).data("id"));
            });
            console.log("Order being sent:", order); // Debugging
            $.post("functions/update_nav_order.php", { order: order }, function(response) {
                console.log("Update Order Response:", response); // Debugging
                if (response !== "success") {
                    alert("Failed to update order!");
                }
            });
        }
    });

    // Delete functionality
    $(".delete-btn").click(function () {
        if (confirm("Are you sure you want to delete this item?")) {
            var id = $(this).data("id");
            console.log("Deleting ID:", id); // Debugging
            $.post("functions/delete_nav.php", { id: id }, function(response) {
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
        var title = row.find(".title").val();
        var page_slug = row.find(".page_slug").val();

        console.log("Saving Data:", { id: id, title: title, page_slug: page_slug }); // Debugging
        $.post("functions/update_nav.php", { id: id, title: title, page_slug: page_slug }, function(response) {
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
    $("#addNavItem").click(function () {
        // Count existing rows to calculate display_order
        var display_order = $("#sortable tr").length + 1;

        // Create a new row with input fields
        var newRow = `
            <tr data-id="new">
                <td class='handle'>☰</td>
                <td><input type='text' class='form-control editable-input title' placeholder='Enter Title'></td>
                <td><input type='text' class='form-control editable-input page_slug' placeholder='Enter Page Slug'></td>
                <td>
                    <button class='btn btn-success btn-sm save-new-btn'>Save</button>
                    <button class='btn btn-secondary btn-sm cancel-new-btn'>Cancel</button>
                </td>
            </tr>
        `;

        // Append the new row after the table header
        $("#sortable").prepend(newRow);
    });

    // Save new row functionality
    $(document).on("click", ".save-new-btn", function () {
        var row = $(this).closest("tr");
        var title = row.find(".title").val();
        var page_slug = row.find(".page_slug").val();
        var display_order = $("#sortable tr").length; // Calculate display_order

        if (title && page_slug) {
            console.log("Adding New Data:", { title: title, page_slug: page_slug, display_order: display_order }); // Debugging
            $.post("functions/add_nav.php", { title: title, page_slug: page_slug, display_order: display_order }, function(response) {
                console.log("Add New Response:", response); // Debugging
                if (response == "success") {
                    location.reload(); // Reload the page to reflect changes
                } else {
                    alert("Failed to add new item!");
                }
            });
        } else {
            alert("Title and Page Slug are required!");
        }
    });

    // Cancel new row functionality
    $(document).on("click", ".cancel-new-btn", function () {
        $(this).closest("tr").remove(); // Remove the new row
    });
});
</script>