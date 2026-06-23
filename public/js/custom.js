/*---------------------------------------------------------------------
    File Name: custom.js
    BYTE2BITE - Cleaned Version
---------------------------------------------------------------------*/

$(function () {

    "use strict";

    /* User Dropdown - Custom Implementation
    -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- */

    $(document).on('click', '.user-dropdown-btn', function (e) {
        e.preventDefault();
        e.stopPropagation();
        $('#userDropdownMenu').toggleClass('show');
    });

    // Close dropdown when clicking anywhere outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.user-dropdown-wrap').length) {
            $('#userDropdownMenu').removeClass('show');
        }
    });

    /* Navbar Toggler (Mobile)
    -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- */

    $(document).on('click', '.navbar-toggler', function () {
        $('#navbarMain').toggleClass('show');
    });

    /* Flash Message Auto-Dismiss
    -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- */

    setTimeout(function () {
        $('.alert-dismissible').fadeOut('slow');
    }, 4000);

    /* CSRF for AJAX
    -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- */

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /* Scroll to Top
    -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- */

    $(window).on('scroll', function () {
        if ($(window).scrollTop() >= 100) {
            $("#back-to-top").addClass('b-show_scrollBut');
        } else {
            $("#back-to-top").removeClass('b-show_scrollBut');
        }
    });

    $("#back-to-top").on("click", function () {
        $('body,html').animate({ scrollTop: 0 }, 800);
    });

    /* Tooltip
    -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- */

    $('[data-toggle="tooltip"]').tooltip();

});