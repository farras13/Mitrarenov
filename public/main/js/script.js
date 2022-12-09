$(function () {
    $(window).scroll(function () {
        var sticky = $('.header-main'),
            scroll = $(window).scrollTop();
        if (scroll >= 60) sticky.addClass('scroller');
        else sticky.removeClass('scroller');
    });

    $(".top-banner").slick({
        centerMode: true,
        centerPadding: '120px',
        slidesToShow: 1,
        prevArrow: '.btn-prev',
        nextArrow: '.btn-next',
        autoplay: true,
        autoplaySpeed: 5000,
        dots: true,
        responsive: [
            {
                breakpoint: 991.98,
                settings: {
                    centerMode: true,
                    centerPadding: '60px',
                }
            },
            {
                breakpoint: 767.98,
                settings: {
                    centerMode: false,
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 575.98,
                settings: {
                    centerMode: false,
                    slidesToShow: 1,
                }
            }
        ]
    })
    $(".top-banner-mobile").slick({
        centerMode: true,
        centerPadding: '120px',
        slidesToShow: 1,
        // prevArrow: '.btn-prev',
        // nextArrow: '.btn-next',
        autoplay: true,
        autoplaySpeed: 5000,
        dots: true,
        arrows: false,
        responsive: [
            {
                breakpoint: 991.98,
                settings: {
                    centerMode: true,
                    centerPadding: '60px',
                }
            },
            {
                breakpoint: 767.98,
                settings: {
                    centerMode: false,
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 575.98,
                settings: {
                    centerMode: false,
                    slidesToShow: 1,
                }
            }
        ]
    })

    $('.testi-slide').slick({
        slidesToShow: 2,
        autoplay: true,
        autoplaySpeed: 5000,
        prevArrow: '.testi-prev',
        nextArrow: '.testi-next',
        responsive: [
            {
                breakpoint: 767.98,
                settings: {
                    slidesToShow: 1,
                }
            },
            {
                breakpoint: 575.98,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    })
    $('.article-slide').slick({
        slidesToShow: 3,
        prevArrow: '.article-prev',
        nextArrow: '.article-next',
        autoplay: true,
        autoplaySpeed: 5000,
        responsive: [
            {
                breakpoint: 767.98,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 575.98,
                settings: {
                    slidesToShow: 1,
                }
            }
        ]
    })
    $('.client-slide').slick({
        slidesToShow: 4,
        prevArrow: '.client-prev',
        nextArrow: '.client-next',
        dots: true,
        autoplay: true,
        autoplaySpeed: 5000,
        responsive: [
            {
                breakpoint: 767.98,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 575.98,
                settings: {
                    slidesToShow: 2,
                }
            }
        ]
    })
    $('.liputan-slide').slick({
        slidesToShow: 5,
        prevArrow: '.liputan-prev',
        nextArrow: '.liputan-next',
        dots: true,
        autoplay: true,
        autoplaySpeed: 5000,
        responsive: [
            {
                breakpoint: 767.98,
                settings: {
                    slidesToShow: 2,
                }
            },
            {
                breakpoint: 575.98,
                settings: {
                    slidesToShow: 2,
                }
            }
        ]
    })
    $('.article-slider').slick({
        slidesToShow: 1,
        prevArrow: '.article-btn-prev',
        nextArrow: '.article-btn-next',
    })

    $('.btn-nav-mobile a').click(function (e) {
        e.preventDefault();
        $('.header-main-nav').toggleClass('show');
        $(this).find('.humburger-menu').toggleClass('open');
        $('body').toggleClass('noscroll')
    })

    $.fn.select2.defaults.set("theme", "bootstrap");

    $('.single-file-upload').click(function () {
        var x = $(this).find('input[type="file"]').attr("id");
        $("#" + x).change(function () {
            var x = $(this).val().replace(/.*(\/|\\)/, '');
            var filename = $(this).closest('.single-file-upload').find('label');
            $(filename).html(x);
        })
    });
    $('.menu-nav-mobile').click(function (e) {
        e.preventDefault();
        $('.card-menu-nav').addClass('show');
        $('body').append("<div class='menu-overlay'></div>")
    });
    $(document).on('click', '.menu-overlay', function () {
        $(this).remove();
        $('.card-menu-nav').removeClass('show');

    })
    $(document).on('click', '.close-nav', function () {
        $('.menu-overlay').remove();
        $('.card-menu-nav').removeClass('show');

    })

    $('.header-nav-second .dropdown').on('shown.bs.dropdown', function () {
        $('body').append('<div class="dropdown-overlay"></div>')
    })

    $('.header-nav-second .dropdown').on('hidden.bs.dropdown', function () {
        $('.dropdown-overlay').remove();
    })

})

