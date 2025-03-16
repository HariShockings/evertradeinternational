$(document).ready(function() {
    // Initialize the page stack
    var pageStack = JSON.parse(localStorage.getItem('pageStack')) || [];
    var currentPage = '';

    // Function to handle adding pages to the stack with size limit
    function addToPageStack(page) {
        // Remove the oldest entry if stack reaches 10 items
        if (pageStack.length >= 10) {
            pageStack.shift();
        }
        pageStack.push(page);
        localStorage.setItem('pageStack', JSON.stringify(pageStack));
    }

    // Function to parse URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

    // Function to load the appropriate content based on URL parameters
    function loadContentFromUrl() {
        var pageParam = getUrlParameter('page');
        var productParam = getUrlParameter('product');

        if (productParam) {
            loadProduct(productParam);
            currentPage = productParam;
        } else if (pageParam) {
            loadPage(pageParam);
            currentPage = pageParam;
        } else {
            // Force home.php if stack is empty
            if (pageStack.length === 0) {
                currentPage = 'home.php';
            } else {
                currentPage = localStorage.getItem('currentPage') || 'home.php';
            }
            localStorage.setItem('currentPage', currentPage);
            loadPage(currentPage);
        }
    }

    // Load content based on URL parameters when the page loads
    loadContentFromUrl();

    // Toggle sidebar
    $('.navbar-toggler').on('click', function() {
        $('.sidebar').toggleClass('show');
    });

    // Close sidebar
    $('.sidebar-close').on('click', function() {
        $('.sidebar').removeClass('show');
    });

    // Handle menu item clicks
    $('nav ul li a, .sidebar-content ul li a, .footer-links a').on('click', function(e) {
        e.preventDefault();
        var pageUrl = $(this).data('page');
        
        addToPageStack(currentPage);
        currentPage = pageUrl;
        localStorage.setItem('currentPage', currentPage);
        
        loadPage(pageUrl);
        updateUrl('page', pageUrl);
    });

    // Load page content
    function loadPage(pageUrl) {
        $.ajax({
            url: 'pages/' + pageUrl,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#content').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading page:', error);
                loadPage('home.php'); // Fallback to home on error
            }
        });
    }

    // Handle product clicks
    $(document).on('click', '.hardware-item a', function(e) {
        e.preventDefault();
        var productName = $(this).closest('.hardware-item').data('product');
        var productNameUrl = encodeURIComponent(productName.replace(/\s+/g, '-').toLowerCase());

        addToPageStack(currentPage);
        currentPage = productNameUrl;
        localStorage.setItem('currentPage', currentPage);
        
        loadProduct(productNameUrl);
        updateUrl('product', productNameUrl);
    });

    // Load product content
    function loadProduct(productNameUrl) {
        $.ajax({
            url: 'pages/productData.php?product=' + productNameUrl,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#content').html(response);
            },
            error: function(xhr, status, error) {
                console.error('Error loading product:', error);
                loadPage('home.php'); // Fallback to home on error
            }
        });
    }

    // Handle browser navigation
    $(window).on('popstate', function(e) {
        var state = e.originalEvent.state;
        if (state) {
            var previousPage = pageStack.pop();
            localStorage.setItem('pageStack', JSON.stringify(pageStack));

            if (previousPage) {
                currentPage = previousPage;
                localStorage.setItem('currentPage', currentPage);
                state.type === 'page' ? loadPage(previousPage) : loadProduct(previousPage);
            } else {
                loadContentFromUrl();
            }
        } else {
            loadContentFromUrl();
        }
    });

    // Update browser URL
    function updateUrl(type, value) {
        var newUrl = window.location.protocol + "//" + window.location.host + 
                    window.location.pathname + '?' + type + '=' + value;
        history.pushState({ type: type, url: value }, '', newUrl);
    }

    // Handle special navigation buttons
    $(document).on('click', '#loadProducts, #homeBtn', function(e) {
        e.preventDefault();
        var pageUrl = $(this).data('page');
        
        addToPageStack(currentPage);
        currentPage = pageUrl;
        localStorage.setItem('currentPage', currentPage);
        
        loadPage(pageUrl);
        updateUrl('page', pageUrl);
    });
});