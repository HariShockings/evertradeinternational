
    <div class="container mt-4">
        <!-- Tab Navigation -->
        <ul class="nav nav-tabs nav-tabs-web" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="manage-nav-tab" data-bs-toggle="tab" data-bs-target="#manage-nav" type="button" role="tab" aria-controls="manage-nav" aria-selected="true">
                    Manage Navigation
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="manage-owner-info-tab" data-bs-toggle="tab" data-bs-target="#manage-owner-info" type="button" role="tab" aria-controls="manage-owner-info" aria-selected="false">
                    Manage Owner Info
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="manage-nav" role="tabpanel" aria-labelledby="manage-nav-tab">
                <?php include('sections/manage_nav.php'); ?>
            </div>
            <div class="tab-pane fade" id="manage-owner-info" role="tabpanel" aria-labelledby="manage-owner-info-tab">
                <?php include('sections/manage_owner_info.php'); ?>
            </div>
        </div>
    </div>