$(document).ready(function () {
    let loadingSpinner =
        '<div class="spinner-wrapper"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    var pageStackDash = JSON.parse(localStorage.getItem("pageStackDash")) || [];
    var currentPage = "";

    function addToPageStackDash(page) {
        if (pageStackDash.length >= 10) {
            pageStackDash.shift();
        }
        pageStackDash.push(page);
        localStorage.setItem("pageStackDash", JSON.stringify(pageStackDash));
    }

    function loadContentFromUrl() {
        var pageParam = getUrlParameter("page");

        if (pageParam) {
            loadPage(pageParam);
            currentPage = pageParam;
        } else {
            if (pageStackDash.length === 0) {
                currentPage = "dashboard.php";
            } else {
                currentPage = localStorage.getItem("currentPage") || "dashboard.php";
            }
            localStorage.setItem("currentPage", currentPage);
            loadPage(currentPage);
        }
    }

    function loadPage(pageUrl) {
        $("#content")
            .html(loadingSpinner)
            .fadeOut(150, function () {
                $(this).load("pages/" + pageUrl, function (response, status) {
                    if (status === "error") {
                        $(this).html("<p class='text-danger'>Page not found.</p>").fadeIn(200);
                    } else {
                        $(this).fadeIn(200);
                    }
                });
            });
    }

    // Ensure sidebar links are clicked correctly
    $(".sidebar-link").click(function (e) {
        e.preventDefault();
        let page = $(this).data("page");

        if (!page) return;

        addToPageStackDash(currentPage);
        currentPage = page;
        localStorage.setItem("currentPage", currentPage);

        loadPage(page);
        updateUrl("page", page);
    });

    $(window).on("popstate", function (e) {
        var state = e.originalEvent.state;
        if (state) {
            var previousPage = pageStackDash.pop();
            localStorage.setItem("pageStackDash", JSON.stringify(pageStackDash));

            if (previousPage) {
                currentPage = previousPage;
                localStorage.setItem("currentPage", currentPage);
                loadPage(previousPage);
            } else {
                loadContentFromUrl();
            }
        } else {
            loadContentFromUrl();
        }
    });

    function updateUrl(type, value) {
        var newUrl =
            window.location.protocol +
            "//" +
            window.location.host +
            window.location.pathname +
            "?" +
            type +
            "=" +
            value;
        history.pushState({ type: type, url: value }, "", newUrl);
    }

    function getUrlParameter(name) {
        let urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(name);
    }

    loadContentFromUrl();
});
