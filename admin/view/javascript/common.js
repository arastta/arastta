function getURLVar(key) {
    var value = [];

    var query = String(document.location).split('?');

    if (query[1]) {
        var part = query[1].split('&');

        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');

            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }

        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
}

function redirectURL(url) {
    $(location).attr("href", url);
}

function removeURLParameter(url, parameter) {
    var path = url.split('?');

    if (path.length >= 2) {

        var prefix = encodeURIComponent(parameter) + '=';

        var queryStrings = path[1].split(/[&;]/g);

        for (var i = queryStrings.length; i-- > 0;) {
            if (queryStrings[i].lastIndexOf(prefix, 0) !== -1) {
                queryStrings.splice(i, 1);
            }
        }

        url = path[0] + '?' + queryStrings.join('&');

        return url;
    } else {
        return url;
    }
}

function changeStatus(status) {
    $.ajax({
        url: 'index.php?route=common/edit/changeStatus&token=' + getURLVar('token'),
        dataType: 'json',
        data:  $("form[id^='form-']").serialize() + '&route=' + getURLVar('route') + '&status=' + status + '&filter_type=' + getURLVar('filter_type'),
        method: 'post',
        beforeSend: function(xhr, data) {
            //data.data = '&route=' + getURLVar('route') + '&status=' + status;
        },
        success: function(json) {
            if (json['redirect']) {
                window.location.href = json['redirect'];
            } else {
                html  = '<div class="alert alert-warning">';
                html += '<i class="fa fa-warning"></i> ' + json['warning'] + '<button type="button" class="close" data-dismiss="alert">×</button>';
                html += '</div>';

                $('.panel.panel-default').before(html);
            }
        }
    }).done(function () {
        setTimeout(function () {
            $('.alert.alert-warning').remove();
        }, 4000);
    });
}

$(window).on('resize', function () {
    if(window.innerWidth < 768) {
        $('#header').removeClass('wide');
        $('#column-left').removeClass('active');
        $('#header').addClass('short');
    }
    else{
        if (localStorage.getItem('column-left') == 'active') {
            $('#header').addClass('wide');
            $('#column-left').addClass('active');
            $('#header').removeClass('short');
        }
    }
});

$(document).ready(function() {

    $(document).on('click', '.popup', function(e) {
        e.preventDefault();

        $('#modal-popup').remove();

        var element = this;

        $.ajax({
            url: $(element).attr('href'),
            type: 'get',
            dataType: 'html',
            success: function(data) {
                html  = '<div id="modal-popup" class="modal">';
                html += '  <div class="modal-dialog">';
                html += '    <div class="modal-content">';
                html += '      <div class="modal-header">';
                html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
                html += '      </div>';
                html += '      <div class="modal-body">' + data + '</div>';
                html += '    </div';
                html += '  </div>';
                html += '</div>';

                $('body').append(html);

                $('#modal-popup').modal('show');
            }
        });
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        container = $('#container').height();
        content = $('#content').height();

        if(container < content) {
            //   $('#container').css('height', content+'px');
        }

        menuFix();
    });

    // Menu scroll fix
    var columnLeft = $('#column-left'),
        isIE8 = $( document.documentElement ).hasClass('ie8'),
        isIOS = /iPhone|iPad|iPod/.test(navigator.userAgent),
        height = {
            window: $(window).height(),
            container: $('#container').height(),
            header: $('#header').height(),
            columnLeft: columnLeft.height(),
            menu: $('#column-left #menu').height()
        };

    $(window).on('scroll', menuFix);

    if (height.menu + height.header <= height.window) {
        columnLeft.css({
            position: 'fixed'
        });
    } else {
        columnLeft.css({
            position: 'absolute'
        });
    }

    function menuFix() {
        menu = $('#menu').height();

        container = $('#content').height() + 35;
        header = $('#header').height();

        if(menu > container) {
            $('#container').height(menu + header);
        } else {
            $('#container').height(container + 35);
        }

        if (isIOS || isIE8) {
            return;
        }

        if (menu + height.header > height.window || height.container < height.window) {
            menuTop = parseInt($(window).scrollTop())+parseInt(height.header);
            columnLeft.css({
                position: 'absolute'
                //top: menuTop+'px'
                //top: '46px'
            });
        } else if (menu + height.header <= height.window) {
            columnLeft.css({
                position: 'fixed'
                //top: height.header
            });
        }
    }

    // Menu child setting position
    $("#column-left #menu li .parent").hover(function(){
        if ($('#column-left').hasClass('active')) {
            $(this).parent().children("ul").css({
                top: "0" + "px"
            });
        } else {
            var xyz = $(this).parent().children("ul");
            var position = $(this).parent().children("ul").offset();
            var	parentMenu = $(this).parent().children("ul").height();
            var parentMenuHeight =  position.top;
            var windowHeight = $(window).height();


            if (windowHeight < parentMenuHeight + parentMenu) {
                var topHeight = ( ( parentMenuHeight + parentMenu ) - windowHeight ) + 10;
                $(this).parent().children("ul").css({
                    top: "-" + topHeight + "px"
                });
            }
        }
    });

    //Form Submit for IE Browser
    $('button[type=\'submit\']').on('click', function() {
        $("form[id*='form-']").submit();
    });

    // Highlight any found errors
    $('.text-danger').each(function() {
        var element = $(this).parent().parent();

        if (element.hasClass('form-group')) {
            element.addClass('has-error');
        }
    });
    // Set last page opened on the menu
    $('#column-left #menu a[href],#column-right #right-menu a[href]').on('click', function() {
        sessionStorage.setItem('menu', $(this).attr('href'));
    });

    if (!sessionStorage.getItem('menu')) {
        $('#menu #dashboard').addClass('active');
        $('#right-menu #dashboard').addClass('active');
    } else {
        // Sets active and open to selected page in the left column menu.
        $('#menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('active open');
        $('#right-menu a[href=\'' + sessionStorage.getItem('menu') + '\']').parents('li').addClass('active open');
    }

    if (localStorage.getItem('column-left') == 'active' || (localStorage.getItem('column-left') == null && $( window ).width() > 768)) {
        $('#button-menu i').replaceWith('<i class="fa fa-play-circle rotate-collapse"></i>');
        $('#column-left').addClass('active');
        $('#header').addClass('wide');

        // Slide Down Menu
        $('#column-left #menu li.active').has('ul').children('ul').addClass('collapse in');
        $('#column-left #menu li').not('.active').has('ul').children('ul').addClass('collapse');
        $('.footer-text').css('padding-left', '220px');

        if (!$('#column-left').hasClass('right')) {
            $.ajax({
                url     : 'index.php?route=common/menu/position&token=' + getURLVar('token') + '&position=active',
                dataType: 'json',
                success : function (json) {
                }
            });
        }
    } else {
        $('#header').addClass('short');
        $('#column-left').removeClass('active');
        $('#button-menu i').replaceWith('<i class="fa fa-play-circle "></i>');

        $('#column-left #menu li span').hide();
        $('#column-left #menu > li > ul').removeClass('in collapse');
        $('#column-left #menu li li.active').has('ul').children('ul').addClass('collapse in');
        $('#column-left #menu li li').not('.active').has('ul').children('ul').addClass('collapse');
        $('.footer-text').css('padding-left', '70px');

        if (!$('#column-left').hasClass('right')) {
            $.ajax({
                url     : 'index.php?route=common/menu/position&token=' + getURLVar('token') + '&position=passive',
                dataType: 'json',
                success : function (json) {
                }
            });
        }
    }

    menuFix();

    // Menu button
    $('#button-menu').on('click', function() {
        // Checks if the left column is active or not.
        if ($('#column-left').hasClass('active')) {
            localStorage.setItem('column-left', 'short');
            $('#button-menu i').replaceWith('<i class="fa fa-play-circle "></i>');

            $('#column-left').removeClass('active');
            $('#header').addClass('short');
            $('#header').removeClass('wide');


            $('#column-left #menu li span').hide();

            $('#column-left #menu > li > ul').removeClass('in collapse');
            $('#column-left #menu > li > ul').removeAttr('style');
            $('.footer-text').css('padding-left', '70px');

            $.ajax({
                url: 'index.php?route=common/menu/position&token=' + getURLVar('token')  + '&position=passive',
                dataType: 'json',
                success: function(json) {}
            });
        } else {
            localStorage.setItem('column-left', 'active');

            $('#button-menu i').replaceWith('<i class="fa fa-play-circle rotate-collapse"></i>');
            // Add the slide down to open menu items
            $('#column-left #menu li.open').has('ul').children('ul').addClass('collapse in');
            $('#column-left #menu li').not('.open').has('ul').children('ul').addClass('collapse');

            $('#column-left').addClass('active');
            $('#header').addClass('wide');
            $('#header').removeClass('short');

            $('.footer-text').css('padding-left', '220px');
            $.ajax({
                url: 'index.php?route=common/menu/position&token=' + getURLVar('token')  + '&position=active',
                dataType: 'json',
                success: function(json) {}
            });
        }
        menuFix();
        menu = $('#column-left #menu').height();
        if(menu > height.container) {
            $('#container').height(menu);
        }
    });

    $('#button-menu').on('click', function(e) {
        e.stopPropagation();
        if ($('#column-left').hasClass('active')) {
            setTimeout(function(){
                $('#column-left #menu li span').show();
            },100);
        }
    });

    // Menu
    $('#column-left #menu').find('li').has('ul').children('a').on('click', function() {
        runMenuFix = false;
        if ($('#column-left').hasClass('active') || !$(this).parent().parent().is('#column-left #menu')) {
            if ($(this).parent().hasClass('open')) {
                runMenuFix = true;
            }
            $(this).parent('li').toggleClass('open');
            if($(this).parent('li').children('ul.collapse').hasClass('in')) {
                $(this).parent('li').children('ul.collapse').removeClass('in');
            }
            else{
                $(this).parent('li').children('ul.collapse').addClass('in');
            }
            $(this).parent('li').siblings().removeClass('open').children('ul.collapse').removeClass('in');
        }
        if(runMenuFix){
            menuFix();
        } else {
            menu = $('#column-left #menu').height();


            if ((menu + height.header > height.window || height.container < height.window) || (menu + height.header > height.window && height.container > height.window)) {
                menuTop = parseInt($(window).scrollTop())+parseInt(height.header);
                columnLeft.css({
                    position: 'absolute'
                    //top: menuTop+'px'
                });
            }
        }
    });

    if (localStorage.getItem('column-right') == 'active' || (localStorage.getItem('column-right') == null && $( window ).width() > 768)) {
        $('#button-menu i').replaceWith('<i class="fa fa-play-circle rotate-collapse"></i>');
        $('#column-right').addClass('active');
        $('#header').addClass('wide');

        // Slide Down Menu
        $('#column-right #right-menu li.active').has('ul').children('ul').addClass('collapse in');
        $('#column-right #right-menu li').not('.active').has('ul').children('ul').addClass('collapse');

        if ($('#column-left').hasClass('right')) {
            $.ajax({
                url: 'index.php?route=common/menu/position&token=' + getURLVar('token')  + '&position=active',
                dataType: 'json',
                success: function(json) {}
            });
            $('.footer-text').css('padding-left', '20px');
            $('.footer-version').css('padding-right', '200px');
        }
    } else {
        $('#header').addClass('short');
        $('#column-right').removeClass('active');
        $('#button-menu i').replaceWith('<i class="fa fa-play-circle "></i>');

        $('#column-right #right-menu li span').hide();
        $('#column-right #right-menu > li > ul').removeClass('in collapse');
        $('#column-right #right-menu li li.active').has('ul').children('ul').addClass('collapse in');
        $('#column-right #right-menu li li').not('.active').has('ul').children('ul').addClass('collapse');

        if ($('#column-left').hasClass('right')) {
            $.ajax({
                url     : 'index.php?route=common/menu/position&token=' + getURLVar('token') + '&position=passive',
                dataType: 'json',
                success : function (json) {
                }
            });
            $('.footer-text').css('padding-left', '20px' );
            $('.footer-version').css('padding-right', '70px' );
        }
    }

    // right menu
    $('#settings-menu, .logo-image img').on('click', function() {
        left_menu_width = $('#column-left').width();

        if (left_menu_width > 0 && !$('#column-left').hasClass('right')) {
            $('#column-left').addClass('width-zero');
            $('#column-left #menu').css('margin-left', '-500px');
            $('#column-right').css('margin-right', '0px');
            $('#column-right').removeClass('width-zero');
            $('#column-right').addClass('right-active');

            show_menu = 'right';
            sessionStorage.setItem('active-menu', 'right');

            setTimeout( function() {
                $('#content .page-header .pull-right').removeAttr('style');

                if ($('#column-right').hasClass('active')) {
                    $('.footer-version').css('padding-right', '200px');
                } else {
                    $('.footer-version').css('padding-right', '70px');
                }

                $('.footer-text').css('padding-left', '20px');
            }, 80);

            setTimeout( function() {
                if ($('#column-right').hasClass('active')) {
                    $('#content .page-header .pull-right').addClass('wide-button')
                } else {
                    $('#content .page-header .pull-right').addClass('short-button');
                }
            }, 300);
        } else {
            $('#column-left').removeClass('right');
            $('#column-left').show();
            $('#column-right').addClass('width-zero');
            $('#column-right').removeClass('right-active');
            $('#column-left').removeClass('width-zero');

            $('#content .page-header .pull-right').removeClass('short-button');
            $('#content .page-header .pull-right').removeClass('wide-button');

            setTimeout( function() {
                $('#column-left #menu').css('margin-left', '0px');
            }, 200);

            setTimeout( function() {

                $('#content .page-header .pull-right').css('padding', '10px');
                $('#content .page-header .pull-right').css('position', 'fixed');
            }, 300);

            show_menu = 'left';
            sessionStorage.setItem('active-menu', 'left');

            setTimeout( function() {
                if ($('#column-left').hasClass('active')) {
                    $('.footer-text').css('padding-left', '220px');
                } else {
                    $('.footer-text').css('padding-left', '70px');
                }

                $('.footer-version').css('padding-right', '20px');
            }, 80);
        }

        $.ajax({
            url: 'index.php?route=common/menu/position&token=' + getURLVar('token')  + '&menu=' + show_menu,
            dataType: 'json',
            success: function(json) {}
        });
    });

    // Menu button
    $('#button-menu-right').on('click', function() {
        // Checks if the left column is active or not.
        if ($('#column-right').hasClass('active')) {
            localStorage.setItem('column-right', 'short');
            $('#button-menu-right i').replaceWith('<i class="fa fa-play-circle rotate-collapse"></i>');

            $('#column-right').removeClass('active');
            $('#header').addClass('short');
            $('#header').removeClass('wide');

            setTimeout( function() {
                $('#content .page-header .pull-right').addClass('short-button');
                $('#content .page-header .pull-right').removeClass('wide-button');
            }, 150);

            $('#column-right #right-menu li span').hide();


            $('#column-right #right-menu > li > ul').removeClass('in collapse');
            $('#column-right #right-menu > li > ul').removeAttr('style');

            $.ajax({
                url: 'index.php?route=common/menu/position&token=' + getURLVar('token')  + '&position=passive',
                dataType: 'json',
                success: function(json) {}
            });
            $('.footer-version').css('padding-right', '70px');
        } else {
            localStorage.setItem('column-right', 'active');

            $('#button-menu-right i').replaceWith('<i class="fa fa-play-circle"></i>');
            // Add the slide down to open menu items
            $('#column-right #right-menu li.open').has('ul').children('ul').addClass('collapse in');
            $('#column-right #right-menu li').not('.open').has('ul').children('ul').addClass('collapse');


            $('#column-right').addClass('active');
            $('#header').addClass('wide');
            $('#header').removeClass('short');

            setTimeout( function() {
                $('#content .page-header .pull-right').addClass('wide-button');
                $('#content .page-header .pull-right').removeClass('short-button');
            }, 150);

            $.ajax({
                url: 'index.php?route=common/menu/position&token=' + getURLVar('token')  + '&position=active',
                dataType: 'json',
                success: function(json) {}
            });
            $('.footer-version').css('padding-right', '200px');
        }
        //menuFix();
        menu = $('#column-right #right-menu').height();
        if(menu > height.container) {
            $('#container').height(menu);
        }
    });

    $('#button-menu-right').on('click', function(e) {
        e.stopPropagation();
        if ($('#column-right').hasClass('active')) {
            setTimeout(function(){
                $('#column-right #right-menu li span').show();
            },100);
        }
    });

    // Menu
    $('#column-right #right-menu').find('li').has('ul').children('a').on('click', function() {
        runMenuFix = false;
        if ($('#column-right').hasClass('active') || !$(this).parent().parent().is('#column-right #right-menu')) {
            if ($(this).parent().hasClass('open')) {
                runMenuFix = true;
            }
            $(this).parent('li').toggleClass('open');
            if($(this).parent('li').children('ul.collapse').hasClass('in')) {
                $(this).parent('li').children('ul.collapse').removeClass('in');
            }
            else{
                $(this).parent('li').children('ul.collapse').addClass('in');
            }
            $(this).parent('li').siblings().removeClass('open').children('ul.collapse').removeClass('in');
        }
        if(runMenuFix){
            menuFix();
        } else {
            menu = $('#column-right #right-menu').height();

            if ((menu + height.header > height.window || height.container < height.window) || (menu + height.header > height.window && height.container > height.window)) {
                menuTop = parseInt($(window).scrollTop())+parseInt(height.header);
                columnLeft.css({
                    position: 'absolute'
                    //top: menuTop+'px'
                });
            }
        }
    });

    if (sessionStorage.getItem('active-menu') == 'right' || $('#column-left').hasClass('right')) {
        if ($('#header').hasClass('short')) {
            $('#content .page-header .pull-right').addClass('short-button');
        } else {
            $('#content .page-header .pull-right').addClass('wide-button');
        }
    } else {
        $('#content .page-header .pull-right').css('padding', '10px');
        $('#content .page-header .pull-right').css('position', 'fixed');
    }

    // Image Manager
    $(document).delegate('a[data-toggle=\'image\']', 'click', function(e) {
        e.preventDefault();

        $('.popover').popover('hide', function() {
            $('.popover').remove();
        });

        var element = this;
        $(element).popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
            }
        });

        $(element).popover('toggle');

        $('#button-image').on('click', function() {
            $('#modal-image').remove();

            $.ajax({
                url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&target=' + $(element).parent().find('input').attr('id') + '&thumb=' + $(element).attr('id'),
                dataType: 'html',
                beforeSend: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-image').prop('disabled', true);
                },
                complete: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                    $('#button-image').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                    $('#modal-image').modal('show');
                }
            });
            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });

        $('#button-clear').on('click', function() {
            $(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));

            $(element).parent().find('input').attr('value', '');
            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });
    });

    // tooltips on hover
    $('[data-toggle=\'tooltip\']').tooltip({container: 'body', html: true});

    // Makes tooltips work on ajax generated content
    $(document).ajaxStop(function() {
        $('[data-toggle=\'tooltip\']').tooltip({container: 'body'});
    });

    $.event.special.remove = {
        remove: function(o) {
            if (o.handler) {
                o.handler.apply(this, arguments);
            }
        }
    }

    $('[data-toggle=\'tooltip\']').on('remove', function() {
        $(this).tooltip('destroy');
    });

    // Filter Show - Hide Start
    if (sessionStorage.getItem('showFilter') == 'false') {
        $("#showFilter").show();
        $("#hideFilter").hide();
    } else {
        $(".well").slideToggle();
        $("#hideFilter").show();
        $("#showFilter").hide();
    }
    //$(".well").hide();

    $('#hideFilter').click(function(){
        sessionStorage.setItem('showFilter', false);
        $(".well").slideToggle();
        $("#showFilter").show();
        $("#hideFilter").hide();
    });

    $('#showFilter').click(function(){
        sessionStorage.setItem('showFilter', true);
        $(".well").slideToggle();
        $("#hideFilter").show();
        $("#showFilter").hide();
    });
    // Filter Show - Hide End

    // New Admin Forms
    var route = getURLVar('route');
    var skip_routes = ['', undefined, 'common/login', 'common/dashboard', 'appearance/layout', 'module/brainyfilter', 'localisation/order_status/edit'];

    if ($.inArray(route, skip_routes) === -1) {
        $("select[name*='country_id']").attr("data-live-search", "true");
        $("select[name*='zone_id']").attr("data-live-search", "true");

        $('form select').removeClass('form-control');
        $('form select').addClass('selectpicker').selectpicker();

        $("select[name*='product_option']").selectpicker({
            width: 'auto'
        });

        $('form .selectpicker').selectpicker('refresh');

        $('select[name=\'country_id\']').on('change', function() {
            $('select[name=\'zone_id\']').selectpicker('refresh');
        });

        $("input:radio").each(function () {
            if ($(this).parent().hasClass('radio-inline')) {
                var r_name = $(this).attr("name");
                var r_value = $(this).attr("value");
                var r_text = $(this).text();

                if ($(':radio[name="'+r_name+'"]').length != 2) {
                    return;
                }

                if ((r_value != 0) && (r_value != 1)) {
                    return;
                }

                if ((r_text.localeCompare(text_yes) == 1) && (r_text.localeCompare(text_no) == 1)) {
                    return;
                }

                var r_checked = $(this).is(':checked');
                var r_div = $(this).parent().parent();

                var html = '';
                var a_class = 'btn-default';
                var d_class = 'btn-default';
                var a_checked = '';
                var d_checked = '';

                if (r_value == 1 && r_checked == true) {
                    a_class = 'btn-success active';
                    a_checked = 'checked="checked"';
                }
                else {
                    d_class = 'btn-danger active';
                    d_checked = 'checked="checked"';
                }

                html += '<div class="btn-group" data-toggle="buttons">';
                html += '   <label for="' + r_name + '1" class="btn ' + a_class + '">';
                html += '       <input type="radio" ' + a_checked + ' value="' + r_value + '" name="' + r_name + '" id="' + r_name + '1">';
                html += '       <span class="radiotext">' + text_yes + '</span>';
                html += '   </label>';
                html += '   <label for="' + r_name + '0" class="btn ' + d_class + '">';
                html += '       <input type="radio" ' + d_checked + ' value="0" name="' + r_name + '" id="' + r_name + '0">';
                html += '       <span class="radiotext">' + text_no + '</span>';
                html += '   </label>';
                html += '</div>';

                r_div.html(html);
            }
        });

        $(document).on('click', '.btn-group label:not(.active)', function (e) {
            var d_label = $(this);
            var d_input = $('#' + d_label.attr('for'));

            if (d_input.attr('type') != 'radio') {
                return;
            }

            if (!d_input.is(':checked')) {
                var a_input = $('input[name="' + d_input.attr('name') + '"]:checked');
                var a_label = a_input.parent();

                a_label.removeClass('btn-success active');
                a_label.removeClass('btn-danger active');
                a_input.removeAttr('checked');
                a_label.addClass('btn btn-default');

                d_label.removeClass('btn-default');

                if (d_input.val() == 0) {
                    d_label.addClass('btn-danger active');
                } else {
                    d_label.addClass('btn-success active');
                }

                d_input.attr('checked', 'checked');

                a_input.trigger('change');
                d_input.trigger('change');
            }
        });
    }

    // Basic mode
    $(document).on('click', '.ba-message .close', function() {
        $('.ba-message').remove();

        $.ajax({
            url: 'index.php?route=user/user/hide&basic_mode_message=hide&token=' +  getURLVar('token') + '&user_id=' + getURLVar('user_id'),
            dataType: 'json',
            success: function(json) {
                $('.ba-message').remove();
            }
        });
    });
});

// Autocomplete */
(function($) {
    $.fn.autocomplete = function(option) {
        return this.each(function() {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Focus
            $(this).on('focus', function() {
                this.request();
            });

            // Blur
            $(this).on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('keydown', function(event) {
                switch(event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function(event) {
                event.preventDefault();

                value = $(event.target).parent().attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            }

            // Show
            this.show = function() {
                var pos = $(this).position();

                $(this).siblings('ul.dropdown-menu').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });

                $(this).siblings('ul.dropdown-menu').show();
            }

            // Hide
            this.hide = function() {
                $(this).siblings('ul.dropdown-menu').hide();
            }

            // Request
            this.request = function() {
                clearTimeout(this.timer);

                this.timer = setTimeout(function(object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            }

            // Response
            this.response = function(json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['value']] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i]['category']) {
                            html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
                        }
                    }

                    // Get all the ones with a categories
                    var category = new Array();

                    for (i = 0; i < json.length; i++) {
                        if (json[i]['category']) {
                            if (!category[json[i]['category']]) {
                                category[json[i]['category']] = new Array();
                                category[json[i]['category']]['name'] = json[i]['category'];
                                category[json[i]['category']]['item'] = new Array();
                            }

                            category[json[i]['category']]['item'].push(json[i]);
                        }
                    }

                    for (i in category) {
                        if(category[i].constructor !== Array) {
                            break;
                        }

                        html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

                        for (j = 0; j < category[i]['item'].length; j++) {
                            html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu').html(html);
            }

            $(this).after('<ul class="dropdown-menu"></ul>');
            $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

        });
    }
})(window.jQuery);

var url = window.location.href;

$(document).ready(function() {
    $(document).on('click', '.ios-toggle', function() {
        theme = '&theme=advanced';

        if ( $( this ).is( ":checked" )) {
            theme = '&theme=basic';
        }

        location = removeURLParameter(url, 'theme')  + theme;
    });
    
    checkTheme = getURLVar('theme');
    route = getURLVar('route');
    
    if (checkTheme && route != 'appearance/theme/edit') {
        $.notify({
            // options
            icon: 'fa fa-refresh',
            message: theme_message,
        },{
            // settings
            element: 'body',
            position: null,
            type: "info",
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 60,
            spacing: 10,
            z_index: 1031,
            delay: 3000,
            timer: 1000,
            animate: {
                enter: 'animated',
                exit: 'animated'
            },
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-4 alert alert-{0}" role="alert">' +
            '<button type="button" aria-hidden="true" class="close" data-notify="dismiss"><i class="fa fa-times"></i></button>' +
            '<span data-notify="icon"></span> ' +
            '<span data-notify="title"> </span> ' +
            '<span data-notify="message">{2}</span>' +
            '<div class="progress" data-notify="progressbar">' +
            '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
            '</div>' +
            '<a href="{3}" target="{4}" data-notify="url"></a>' +
            '</div>'
        });
    }    
});
