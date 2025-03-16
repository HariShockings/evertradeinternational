<?php
include('../functions/config.php'); // Include your database connection file

// Fetch owner information
$ownerQuery = "SELECT * FROM tbl_owner LIMIT 1"; // Assuming there's only one owner record
$ownerResult = $conn->query($ownerQuery);
$ownerData = $ownerResult->fetch_assoc();
?>
    <style>
        .editable-input {
            width: 100%;
        }
        .editable {
            pointer-events: auto;
        }
        .nav-tabs-web-owner-info {
            flex-direction: column;
            border: 0;
        }
        .nav-tabs-web-owner-info .nav-link {
            border: 1px solid #dee2e6;
            border-radius: 0;
            margin-bottom: 5px;
            text-align: left;
        }
        .nav-tabs-web-owner-info .nav-link.active {
            background-color: #0d6efd;
            color: #fff;
            border-color: #0d6efd;
        }
        .tab-content {
            padding: 20px;
            border: 1px solid #dee2e6;
            border-left: 0;
        }

        @media (max-width: 768px) {
            .nav-tabs-web-owner-info {
                flex-direction: row;
            }
        }

    </style>
    <div class="container mt-4">
        <h2>Manage Owner Information</h2>
        <div class="row">
            <!-- Vertical Tabs -->
            <div class="col-lg-3 col-md-12 col-sm-12">
                <div class="nav nav-tabs nav-tabs-web-owner-info" id="v-tabs-tab" role="tablist">
                    <button class="nav-link active" id="v-tabs-basic-tab" data-bs-toggle="tab" data-bs-target="#v-tabs-basic" type="button" role="tab" aria-controls="v-tabs-basic" aria-selected="true">
                        Basic Info
                    </button>
                    <button class="nav-link" id="v-tabs-social-tab" data-bs-toggle="tab" data-bs-target="#v-tabs-social" type="button" role="tab" aria-controls="v-tabs-social" aria-selected="false">
                        Social Media
                    </button>
                    <button class="nav-link" id="v-tabs-other-tab" data-bs-toggle="tab" data-bs-target="#v-tabs-other" type="button" role="tab" aria-controls="v-tabs-other" aria-selected="false">
                        Other Info
                    </button>
                </div>
            </div>

            <!-- Tab Content -->
            <div class="col-lg-9 col-md-12 col-sm-12">
                <div class="tab-content" id="v-tabs-tabContent">
                    <!-- Basic Info Tab -->
                    <div class="tab-pane fade show active" id="v-tabs-basic" role="tabpanel" aria-labelledby="v-tabs-basic-tab">
                        <form id="ownerForm" enctype="multipart/form-data">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Name</td>
                                    <td><input type="text" class="form-control editable-input" name="name" value="<?php echo $ownerData['name']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Logo URL</td>
                                    <td>
                                        <div id="logoWrapper">
                                            <?php if ($ownerData['logo']) { ?>
                                                <img src="<?php echo '../uploads/' . $ownerData['logo']; ?>" alt="Owner Logo" class="img-fluid" width="100" onerror="this.onerror=null; this.src='../assets/img/placeholder.jpg'">
                                                <br>
                                                <button type="button" class="btn btn-danger" id="removeLogoBtn" style="display:none;">Remove Logo</button>
                                                <input type="file" class="form-control editable-input" name="logo" id="logoInput" style="display:none;">
                                                <input type="hidden" name="old_logo" value="<?php echo $ownerData['logo']; ?>">
                                            <?php } else { ?>
                                                <input type="file" class="form-control editable-input" name="logo" id="logoInput">
                                                <input type="hidden" name="old_logo" value="">
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Contact</td>
                                    <td><input type="text" class="form-control editable-input" name="contact" value="<?php echo $ownerData['contact']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><input type="email" class="form-control editable-input" name="email" value="<?php echo $ownerData['email']; ?>" disabled></td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <!-- Social Media Tab -->
                    <div class="tab-pane fade" id="v-tabs-social" role="tabpanel" aria-labelledby="v-tabs-social-tab">
                        <form id="ownerFormSocial">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Facebook</td>
                                    <td><input type="text" class="form-control editable-input" name="facebook" value="<?php echo $ownerData['facebook']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Twitter</td>
                                    <td><input type="text" class="form-control editable-input" name="twitter" value="<?php echo $ownerData['twitter']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Instagram</td>
                                    <td><input type="text" class="form-control editable-input" name="instagram" value="<?php echo $ownerData['instagram']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>LinkedIn</td>
                                    <td><input type="text" class="form-control editable-input" name="linkedin" value="<?php echo $ownerData['linkedin']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>WhatsApp</td>
                                    <td><input type="text" class="form-control editable-input" name="whatsapp" value="<?php echo $ownerData['whatsapp']; ?>" disabled></td>
                                </tr>
                            </table>
                        </form>
                    </div>

                    <!-- Other Info Tab -->
                    <div class="tab-pane fade" id="v-tabs-other" role="tabpanel" aria-labelledby="v-tabs-other-tab">
                        <form id="ownerFormOther">
                            <table class="table table-bordered">
                                <tr>
                                    <td>Location</td>
                                    <td><input type="text" class="form-control editable-input" name="location" value="<?php echo $ownerData['location']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Office Time</td>
                                    <td><input type="text" class="form-control editable-input" name="office_time" value="<?php echo $ownerData['office_time']; ?>" disabled></td>
                                </tr>
                                <tr>
                                    <td>Google Map Link</td>
                                    <td><input type="text" class="form-control editable-input" name="google_map_link" value="<?php echo $ownerData['google_map_link']; ?>" disabled></td>
                                </tr>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit and Save Buttons -->
        <div class="mt-3">
            <button type="button" class="btn btn-primary" id="editButton">Edit</button>
            <button type="button" class="btn btn-success" id="saveOwnerInfo" style="display: none;">Save Changes</button>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            // Edit button functionality
            $("#editButton").click(function () {
                // Enable form fields for editing
                $("input[name]").prop("disabled", false);
                $("#editButton").hide(); // Hide the edit button
                $("#saveOwnerInfo").show(); // Show the save button
                $("#removeLogoBtn").show();
                $("#logoInput").show();
            });

            // Remove logo button functionality
            $("#removeLogoBtn").click(function () {
                if (confirm("Are you sure you want to remove the logo?")) {
                    // Remove the logo image by setting the value to empty
                    $("#logoWrapper").html('<input type="file" class="form-control editable-input" name="logo" id="logoInput">');
                }
            });

            // Save functionality
            $("#saveOwnerInfo").click(function () {
                var formData = new FormData($("#ownerForm")[0]); // Use FormData for file uploads

                // Append data from other forms
                formData.append("facebook", $("input[name='facebook']").val());
                formData.append("twitter", $("input[name='twitter']").val());
                formData.append("instagram", $("input[name='instagram']").val());
                formData.append("linkedin", $("input[name='linkedin']").val());
                formData.append("whatsapp", $("input[name='whatsapp']").val());
                formData.append("location", $("input[name='location']").val());
                formData.append("office_time", $("input[name='office_time']").val());
                formData.append("google_map_link", $("input[name='google_map_link']").val());

                $.ajax({
                    url: "functions/update_owner_info.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log("Server Response:", response); // Log the exact response
                        if (response.trim() === "success") { // Trim whitespace and check for "success"
                            alert("Owner information updated successfully!");
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert("Failed to update owner information! Response: " + response);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", xhr.responseText); // Debugging
                        alert("An error occurred while updating owner information.");
                    }
                });
            });
        });
    </script>