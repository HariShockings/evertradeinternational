$(document).ready(function () {
    let lastScrollTop = 0;

    $(window).on("scroll", function () {
        let scrollTop = $(this).scrollTop();
        let $image = $("#scrollImage");

        if (scrollTop > lastScrollTop) {
            $image.css("object-position", "center bottom");
        } else {
            $image.css("object-position", "center top");
        }

        lastScrollTop = scrollTop;
    });
    
    $("#teamCarousel").owlCarousel({
        loop: true,
        margin: 10,
        nav: true,
        dots: true,
        autoplay: true,
        autoplayTimeout: 3000,
        responsive: {
            0: { items: 1 },
            600: { items: 2 },
            1000: { items: 3 }
        }
    });
});