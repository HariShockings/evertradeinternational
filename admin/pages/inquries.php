<style>
.message-cell {
    max-width: 300px;
    word-wrap: break-word;
    white-space: normal;
}
</style>
<?php
include('../functions/config.php');

// Fetch inquiries
$inquiryQuery = "SELECT `id`, `name`, `email`, `message`, `created_at`, `status`, `updated_at` FROM `tbl_inquiry` ORDER BY `created_at` DESC";
$inquiryResult = $conn->query($inquiryQuery);

// Separate inquiries into responded and pending
$pendingInquiries = [];
$respondedInquiries = [];

if ($inquiryResult->num_rows > 0) {
    while ($row = $inquiryResult->fetch_assoc()) {
        if ($row['status'] === 'responded') {
            $respondedInquiries[] = $row;
        } else {
            $pendingInquiries[] = $row;
        }
    }
}
?>

<div class="container mt-4">
    <h3>Customer Inquiries</h3>
    
    <!-- Pending Inquiries -->
    <div class="card mb-4">
        <div class="card-header bg-warning text-white">
            Pending Inquiries
        </div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Wrap the table with the responsive class -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pendingInquiries as $inquiry): ?>
                        <tr data-id="<?= $inquiry['id'] ?>">
                            <td><?= htmlspecialchars($inquiry['name']) ?></td>
                            <td><?= htmlspecialchars($inquiry['email']) ?></td>
                            <td class="message-cell"><?= htmlspecialchars($inquiry['message']) ?></td>
                            <td><?= date('M d, Y H:i', strtotime($inquiry['created_at'])) ?></td>
                            <td>
                                <button class="btn btn-primary btn-sm respond-btn" 
                                        data-email="<?= htmlspecialchars($inquiry['email']) ?>"
                                        data-id="<?= $inquiry['id'] ?>">
                                    Respond
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- End of table-responsive -->
        </div>
    </div>

    <!-- Responded Inquiries -->
    <div class="card">
        <div class="card-header bg-success text-white">
            Responded Inquiries
        </div>
        <div class="card-body">
            <div class="table-responsive"> <!-- Wrap the table with the responsive class -->
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Date</th>
                            <th>Response Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($respondedInquiries as $inquiry): ?>
                        <tr>
                            <td><?= htmlspecialchars($inquiry['name']) ?></td>
                            <td><?= htmlspecialchars($inquiry['email']) ?></td>
                            <td class="message-cell"><?= htmlspecialchars($inquiry['message']) ?></td>
                            <td><?= date('M d, Y H:i', strtotime($inquiry['created_at'])) ?></td>
                            <td><?= date('M d, Y H:i', strtotime($inquiry['updated_at'])) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div> <!-- End of table-responsive -->
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" aria-labelledby="responseModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="responseModalLabel">Respond to Inquiry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="responseForm">
                    <input type="hidden" id="inquiryId">
                    <div class="mb-3">
                        <label for="recipientEmail" class="form-label">To</label>
                        <input type="email" class="form-control" id="recipientEmail" required readonly>
                    </div>
                    <div class="mb-3">
                        <label for="emailSubject" class="form-label">Subject</label>
                        <input type="text" class="form-control" id="emailSubject" required>
                    </div>
                    <div class="mb-3">
                        <label for="emailMessage" class="form-label">Message</label>
                        <textarea class="form-control" id="emailMessage" rows="5" required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send Response</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle respond button click
    $('.respond-btn').click(function() {
        const inquiryId = $(this).data('id');
        const recipientEmail = $(this).data('email');
        
        $('#inquiryId').val(inquiryId);
        $('#recipientEmail').val(recipientEmail);
        $('#responseModal').modal('show');
    });

    $('#responseForm').submit(function(e) {
    e.preventDefault();
    
    const formData = {
        inquiry_id: $('#inquiryId').val(),
        recipient_email: $('#recipientEmail').val(),
        subject: $('#emailSubject').val(),
        message: $('#emailMessage').val()
    };

    $.ajax({
        url: 'functions/send-response.php',
        method: 'POST',
        contentType: 'application/json',
        data: JSON.stringify(formData),
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                $('#responseModal').modal('hide');
                location.reload();
            } else {
                alert('Error: ' + (response.message || 'Unknown error'));
            }
        },
        error: function(xhr) {
            let error = 'Request failed';
            try {
                const res = JSON.parse(xhr.responseText);
                error = res.message || error;
            } catch(e) {}
            alert(error);
        }
    });
});
});
</script>