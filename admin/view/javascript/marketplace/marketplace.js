if (typeof(Marketplace) === 'undefined') {
    var Marketplace = {};
}

Marketplace.apps = {
    view: "dashboard",
    id: 0,
    ordering: "",
    cssfiles: [],
    jsfiles: [],
    list: 0,
    loaded: 0,
    update: false
};

Marketplace.loadweb = function(url, data) {
    if ('' == url) { return false; }

    url += '&list='+(Marketplace.apps.list ? 'list' : 'grid')+'&version='+apps_version+'&token='+token;

    $('#marketplace-loading')
        .css("top", $('.panel-body').position().top - $(window).scrollTop())
        .css("left", $('.panel-body').position().left - $(window).scrollLeft())
        .css("width", $('.panel-body').outerWidth())
        .css("height", $('.panel-body').outerHeight());

    $.ajax({
        url: url,
        dataType: 'jsonp',
        method: 'post',
        data: data,
        cache: true,
        jsonpCallback: "arapi_jsonpcallback",
        beforeSend: function () {
            $('#marketplace-loading').remove();
            $('.panel-body').css({"position": "relative"});
            elem = $('<div id="marketplace-loading" class="text-center"><div class="loading-wrap"><i class="fa fa-spinner fa-spin checkout-spin"></i></div></div>')
                .css("background", "50% 15% no-repeat rgba(224, 224, 224, 0.8)")
                .css("top", 0)
                .css("left", 0)
                .css("width", "100%")
                .css("height", "100%")
                .css("position", "absolute")
                .css("z-index", "8")
                .css("opacity", "0.80")
                .css("-ms-filter", "progid:DXImageTransform.Microsoft.Alpha(Opacity = 80)")
                .css("filter", "alpha(opacity = 80)")
                .appendTo('.panel-body');
        },
        success: function (response) {
            if (response.message) {
                $('.alert').remove();
                $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-exclamation-circle"></i> '+response.message.success+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }
            
            if (response.error) {
                $('.alert').remove();
                $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+response.error.warning+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                $('#marketplace-loading').remove();
            } else if (response.redirect) {
                Marketplace.loadweb(baseUrl + response.redirect);
            } else {
                $('.panel-body').html(response.html);
                uri = decodeURIComponent(ArrayToURL(URLToArray(url)));
                uri = uri.replace('route=extension/marketplace/api', 'route=extension/marketplace');
                window.history.pushState({"html":response.html}, "", window.location.origin + window.location.pathname + '?' + uri);
            }
        },
        fail: function() {
            $('#marketplace-loading').hide();
            $('#web-loader-error').show();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            $('#marketplace-loading').hide();
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
    return true;
};

function URLToArray(url) {
    var request = {};
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
        if(!pairs[i])
            continue;
        var pair = pairs[i].split('=');
        if(pair[0] == 'list' || pair[0] == 'version' || pair[0] == 'length')
            continue;
        request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
    return request;
}

function ArrayToURL(array) {
    var pairs = [];
    for (var key in array)
        if (array.hasOwnProperty(key))

            pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(array[key]));
    return pairs.join('&');
}

var step = new Array();
Marketplace.installfromweb = function (product_id, product_name, product_version, obj) {
    $.ajax({
        url: 'index.php?route=extension/installer/install&token=' + token,
        dataType: 'json',
        type: 'post',
        data   : {product_id: product_id, store: store},
        beforeSend: function () {
            $(obj).html('<div class="install-loading" class="text-center"><i class="fa fa-spinner fa-spin"></i></div>');
            $(obj).attr('disabled', 'disabled');
        },
        success: function(json) {
            if (json['error']) {
                $('html, body').animate({ scrollTop: 0 }, 'slow');
                $('.alert, .text-danger').remove();
                $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['step']) {
                step = json['step'];
                next(product_id, product_name, product_version);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
    return true;
};

Marketplace.apps.initialize = function(url_data) {
    Marketplace.apps.loaded = 1;
    if ($.param(url_data).search('api=api%2F') == -1) {
        url_data = 'api=api/marketplace&' + $.param(url_data);
    } else {
        url_data = $.param(url_data);
    }
    Marketplace.loadweb(baseUrl+'index.php?route=extension/marketplace/api&' + decodeURIComponent(url_data));
};

$(document).on('keypress', '#marketplace-search-input', function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if(keycode == '13'){
        var search = $(this).val();
        var searchFilter = $('#marketplace-search-option').val();

        var store_name = '&store=extensions';
        if(store != '') {
            store_name = '&store=' + store;
        }

        Marketplace.loadweb( baseUrl + 'index.php?route=extension/marketplace/api&api=api/search&search=' + search + '&type=' + searchFilter + store_name );
    }
});

function searchFilter (store_id, store_name) {
    $('#marketplace-search-option').val(store_id);
    store = store_name.toLowerCase();
}

function next(product_id, product_name, product_version) {
    data = step.shift();
    if (data) {
        $.ajax({
            url: data.url,
            type: 'post',
            dataType: 'json',
            data   : {path: data.path, store: store, product_id: product_id, product_name: product_name, product_version: product_version},
            success: function(json) {
                $('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                if (json['success']) {
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                    $('.install-loading').html('<i class="fa fa-check"></i>');
                    $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="success">&times;</button></div>');
                }

                if (!json['error'] && !json['success']) {
                    next(product_id, product_name, product_version);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

$(document).on('click', '.uninstall-button', function(e) {
    $.ajax({
        url: 'index.php?route=extension/marketplace/uninstall&product_id=' + $(this).attr('data-product-id') + '&token=' + token,
        dataType: 'json',
        beforeSend: function() {
            if(!confirm('Are you sure ?')) {
                return false;
            }
            $(e.target).children('.fa').addClass('fa-spinner fa-spin');
            $(e.target).children('.fa').removeClass('fa-minus');
            $(e.target).attr('disabled', 'disabled');
        },
        success: function(json) {
            $('.alert-success, .alert-danger').remove();
            $('html, body').animate({ scrollTop: 0 }, 'slow');

            if (json['error']) {
                $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['success']) {
                $(e.target).children('.fa').removeClass('fa-spinner fa-spin');
                $(e.target).children('.fa').addClass('fa-plus');
                $('.install-loading').html('<i class="fa fa-check"></i>');
                $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="success">&times;</button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).on('click', '.uninstall-button-product', function() {
    clicked_button = $('.uninstall-button-product');
    $.ajax({
        url: 'index.php?route=extension/marketplace/uninstall&product_id=' + $(this).attr('data-product-id') + '&token=' + token,
        dataType: 'json',
        beforeSend: function() {
            if(!confirm('Are you sure ?')) {
                return false;
            }
            $(clicked_button).children('.fa').addClass('fa-spinner fa-spin');
            $(clicked_button).children('.fa').removeClass('fa-minus');
            $(clicked_button).attr('disabled', 'disabled');
        },
        success: function(json) {
            $('.alert-success, .alert-danger').remove();
            $('html, body').animate({ scrollTop: 0 }, 'slow');

            if (json['error']) {
                $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['success']) {
                $(clicked_button).html('<i class="fa fa-plus"></i> Install');
                $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="success">&times;</button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Set last page opened on the menu
$(document).on('click', '.panel-body a[onclick]', function() {
    url = $(this).attr('onclick').replace("Marketplace.loadweb(baseUrl + '", "");
    url = $(this).attr('onclick').replace("')", "");
    uri = decodeURIComponent(ArrayToURL(URLToArray(url)));
    sessionStorage.setItem('marketplace-menu', uri);
});
