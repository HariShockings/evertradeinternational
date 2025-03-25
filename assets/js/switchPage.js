$(document).ready(function() {
    var pageStack = JSON.parse(localStorage.getItem('pageStack')) || [];
    var currentPage = '';

    function addToPageStack(page) {
        if (pageStack.length >= 10) {
            pageStack.shift();
        }
        pageStack.push(page);
        localStorage.setItem('pageStack', JSON.stringify(pageStack));
    }

    function getUrlParameter(name) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(window.location.href);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }

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
            if (pageStack.length === 0) {
                currentPage = 'home.php';
            } else {
                currentPage = localStorage.getItem('currentPage') || 'home.php';
            }
            localStorage.setItem('currentPage', currentPage);
            loadPage(currentPage);
        }
    }

    loadContentFromUrl();

    $('.navbar-toggler').on('click', function() {
        $('.sidebar').toggleClass('show');
    });

    $('.sidebar-close').on('click', function() {
        $('.sidebar').removeClass('show');
    });

    $('nav ul li a, .sidebar-content ul li a, .footer-links a').on('click', function(e) {
        e.preventDefault();
        var pageUrl = $(this).data('page');
        
        addToPageStack(currentPage);
        currentPage = pageUrl;
        localStorage.setItem('currentPage', currentPage);
        
        loadPage(pageUrl);
        updateUrl('page', pageUrl);
    });

    function loadPage(pageUrl) {
        $.ajax({
            url: 'pages/' + pageUrl,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#content').html(response);
                $(window).scrollTop(0); // Scroll to top
            },
            error: function(xhr, status, error) {
                console.error('Error loading page:', error);
                loadPage('home.php');
            }
        });
    }

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

    function loadProduct(productNameUrl) {
        $.ajax({
            url: 'pages/productData.php?product=' + productNameUrl,
            type: 'GET',
            dataType: 'html',
            success: function(response) {
                $('#content').html(response);
                $(window).scrollTop(0); // Scroll to top
            },
            error: function(xhr, status, error) {
                console.error('Error loading product:', error);
                loadPage('home.php');
            }
        });
    }

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

    function updateUrl(type, value) {
        var newUrl = window.location.protocol + "//" + window.location.host + 
                    window.location.pathname + '?' + type + '=' + value;
        history.pushState({ type: type, url: value }, '', newUrl);
    }

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
