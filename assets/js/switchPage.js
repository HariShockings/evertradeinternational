$(document).ready(function() {
    var currentPage = localStorage.getItem('currentPage');

    if (!currentPage) {
        currentPage = 'home.php'; // Set home.php as the default initial page
    }

    loadPage(currentPage);

    // Handle click events on menu items
    $('nav ul li a').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior

        var pageUrl = $(this).data('page'); // Get the data-page attribute value
        loadPage(pageUrl);

        // Save the current page to local storage
        localStorage.setItem('currentPage', pageUrl);
    });

    $('.sidebar-content ul li a').on('click', function(e) {
        e.preventDefault(); // Prevent default link behavior
    
        var pageUrl = $(this).data('page'); // Get the data-page attribute value
        loadPage(pageUrl);
    
        // Save the current page to local storage
        localStorage.setItem('currentPage', pageUrl);
    });
    
    function loadPage(pageUrl) {
        // Load the content of the selected page using AJAX
        $.ajax({
            url: 'pages/' + pageUrl,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#content').html(response); // Insert the loaded content into the #content div
            },
            error: function(xhr, status, error) {
                console.error('Error loading page:', error);
            }
        });
    }
});
