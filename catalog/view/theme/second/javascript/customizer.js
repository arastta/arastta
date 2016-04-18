$(document).ready(function() {
    $('#customizer-control-menu-dropdown-menu_background-color input').on('change', function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', 'inherit');
    });

    $('#customizer-control-menu-dropdown-menu_background-color input').mouseout(function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', '');
    });

    $('#customizer-control-menu-dropdown-menu-item_background-color input').on('change', function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', 'inherit');
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu .dropdown-inner .list-unstyled li a', $('#customizer-preview iframe').contents()).css('background-color', this.value);
    });

    $('#customizer-control-menu-dropdown-menu-item_background-color input').on('change', function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', 'inherit');
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu .dropdown-inner .list-unstyled li a', $('#customizer-preview iframe').contents()).css('background-color', this.value);
    });

    $('#customizer-control-menu-dropdown-menu-item_background-color input').mouseout(function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', '');
    });

    $('#customizer-control-menu-text-hover_color input').on('change', function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', 'inherit');
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu .dropdown-inner .list-unstyled li a', $('#customizer-preview iframe').contents()).css('color', this.value);
    });

    $('#customizer-control-menu-text-hover_color input').mouseout(function() {
        $('.collapse.navbar-collapse .nav.navbar-nav .dropdown-menu', $('#customizer-preview iframe').contents()).css('display', '');
    });

    $( '#customizer-control-layout_width select').on('change', function() {
        if ($(this).val() != '100%') {
            $('.container', $('#customizer-preview iframe').contents()).css('padding-left', 0);
            $('.container', $('#customizer-preview iframe').contents()).css('padding-right', 0);
            $('footer .container', $('#customizer-preview iframe').contents()).css('padding-left', '15px');
            $('footer .container', $('#customizer-preview iframe').contents()).css('padding-right', '15px');
        } else {
            $('.container', $('#customizer-preview iframe').contents()).css('padding-left', '15px');
            $('.container', $('#customizer-preview iframe').contents()).css('padding-right', '15px');
            $('footer .container', $('#customizer-preview iframe').contents()).css('padding-left', 0);
            $('footer .container', $('#customizer-preview iframe').contents()).css('padding-right', 0);
        }
    });
});
