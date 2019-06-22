; (function ($) {
    'use strict';
    jQuery(document).ready(function () {
        console.log('reach');
        $('[href="#virtual-pass"]').click(function (event) {
            event.preventDefault();
            $('.service-option').addClass('service-detail');
            $('#service-buttons').removeClass("service-detail");
            $('#virtual-pass').removeClass("service-detail");
        })
        $('[href="#dedicated-desk"]').click(function (event) {
            event.preventDefault();
            $('.service-option').addClass('service-detail');
            $('#service-buttons').removeClass("service-detail");
            $('#dedicated-desk').removeClass("service-detail");
        })
        $('[href="#private-office"]').click(function (event) {
            event.preventDefault();
            $('.service-option').addClass('service-detail');
            $('#service-buttons').removeClass("service-detail");
            $('#private-office').removeClass("service-detail");
        })
        $('[href="#team-space"]').click(function (event) {
            event.preventDefault();
            $('.service-option').addClass('service-detail');
            $('#service-buttons').removeClass("service-detail");
            $('#team-space').removeClass("service-detail");
        })
    })
})(jQuery);