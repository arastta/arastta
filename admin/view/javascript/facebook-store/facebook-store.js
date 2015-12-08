var token = getURLVar('token');

var FaceBook = function() {

    var start = function() {
        FaceBook.handleDraggable();
    };

    return {
        handleDraggable : function() {
            $('.module-block').draggable({
                appendTo: document['body'],
                helper: 'clone',
                cursor: 'move',
                zIndex: 9999,
                cancel: '.btn-remove, .btn-edit',
                distance: 2,
                cursorAt: {
                    left: 10,
                    top: 10
                }
            });

            $('.dashed').droppable({
                activeClass: 'activeDroppable',
                hoverClass: 'hoverDroppable',
                tolerance: 'pointer',
                forceHelperSize: false,
                forcePlaceholderSize: false,
                accept: '.module-block',
                cancel: '.btn-remove, .btn-edit',
                drop: function (event, ev) {

                    var data_code = $(ev['draggable']).attr('data-code');
                    var sort_order_value = 0;

                    sort_order_value = $(this).find('.mblock').length;

                    var html  = '<div class="mblock ui-draggable ui-draggable-handle" data-code="' + data_code + '">';
                    html += ' 	<div class="mblock-header">';
                    html += ' 		<div class="mblock-header-title"><i class="fa fa-arrows-alt"></i><span class="module-name">' + ev['draggable']['text']() + '</span></div>';
                    html += '	</div>';
                    html += '	<div class="mblock-control-menu ui-sortable-handle">';
                    html += '		<div class="mblock-action pull-right">';
                    html += '			<a class="btn btn-xs btn-remove" onclick="confirm(\'' + confirm_text + '\')  ? removeFeed($(this)) : false;"><i class="fa fa-trash-o"></i></a>';
                    html += '		</div>';
                    html += '	</div>';
                    html += '	<input type="hidden" name="facebook_store_feed[]" value="' + data_code + '"/>';
                    html += '</div>';

                    $(this).append(html);
                }
            }).sortable({
                appendTo: document['body'],
                helper: 'clone',
                placeholder: 'hoverDroppable',
                zIndex: 99999,
                dropOnEmpty: true,
                connectWith: '.dashed',
                items: '.mblock',
                cancel: '.btn-edit, .btn-remove',
                update: function (allBindingsAccessor, stopHere) {
                    var counter = 0;

                    $('.mblock.ui-draggable').each(function( index ) {
                        $(this).children('.sort').attr('value', counter);
                        counter = counter + 1;
                    });
                }
            }).disableSelection();
        },

        init : function() {
            start();
        }
    };
}();


$(document).ready(function() {
    // Search
    $('#search input[name=\'search\']').parent().find('button').on('click', function() {
        url = $('base').attr('href') + 'index.php?route=catalog/product/autocomplete&token=' + token;

        var value = $('header input[name=\'search\']').val();

        if (value) {
            url += '&filter_name=' + encodeURIComponent(value);
        }

        location = url;
    });

    $('#search input[name=\'search\']').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('header input[name=\'search\']').parent().find('button').trigger('click');
        }
    });

    // Live Search
    $.fn.liveSearch = function(option) {
        return this.each(function() {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Blur

            $(this).on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('input', function(event) {
                this.request();
            });

            // Show
            this.show = function() {
                var pos = $(this).position();

                $(this).siblings('ul.dropdown-menu').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left,
                    width: '100%'
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
                    var count = json.length;

                    if(count >= 5) {
                        count = 5;
                    }

                    for (i = 0; i < count; i++) {
                        html += '<li data-value="' + json[i]['value'] + '">';
                        html += '   <a onclick="addProduct(' + json[i]['value'] + ');">';
                        html += '       <div class="ajaxadvance row">';
                        html += '           <div class="image col-sm-2">';
                        html += '               <img title="' + json[i]['value'] + '" src="' + json[i]['image'] + '"/>';
                        html += '           </div>';
                        html += '           <div class="content col-sm-8">';
                        html += '               <div class="name">' + json[i]['label'] + '</div>';
                        html += '               <div class="price">' + json[i]['price'] + '</div>';
                        html += '           </div>';
                        html += '       </div>';
                        html += '   </a>';
                        html += '</li>';
                    }

                    if(count == 5) {
                        html += '<li data-value="' + json[i]['value'] + '">';
                        html += '   <a href="' + json[i]['searchall'] + '">';
                        html += '       <div class="ajaxadvance">';
                        html += '        -- View All -- ';
                        html += '       </div>'
                        html += '   </a>'
                        html += '</li>'
                    }

                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu').html(html);
            }

            $(this).after('<ul class="dropdown-menu" style="padding:2px 2px 2px 2px;"></ul>');
            $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

        });
    };

    $('input[name=\'search\']').liveSearch({
        'source': function(request, response) {
            if(request != '' && request.length > 2) {
                $.ajax({
                    url: 'index.php?route=catalog/product/autocomplete&filter_name=' +  encodeURIComponent(request) + '&token=' + token,
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                label: item.name,
                                value: item.product_id,
                                image: item.image,
                                price: item.price,
                                href: item.href,
                                searchall: item.searchall
                            }
                        }));
                    }
                });
            } else {
                $('#search > .dropdown-menu').hide();
            }
        }
    });
});

function addProduct(product_id) {
    $.ajax({
        url: 'index.php?route=feed/facebook_store/getProduct&product_id=' + product_id + '&token=' + token,
        dataType: 'json',
        success: function(json) {
            var sort_order_value = 0;
            sort_order_value = $(this).find('.mblock').length;

            var html  = '<div class="mblock ui-draggable ui-draggable-handle" data-code="product-' + json['product_id'] + '">';
            html += ' 	<div class="mblock-header">';
            html += ' 		<div class="mblock-header-title"><i class="fa fa-arrows-alt"></i><span class="module-name">' + json['name'] + '</span></div>';
            html += '	</div>';
            html += '	<div class="mblock-control-menu ui-sortable-handle">';
            html += '		<div class="mblock-action pull-right">';
            html += '			<a class="btn btn-xs btn-remove" onclick="confirm(\'' + confirm_text + '\')  ? removeFeed($(this)) : false;"><i class="fa fa-trash-o"></i></a>';
            html += '		</div>';
            html += '	</div>';
            html += '	<input type="hidden" name="facebook_store_feed[]" value="product-' + json['product_id'] + '"/>';
            html += '</div>';

            $('.dashed').append(html);

        }
    });
}

function removeFeed(remove_button) {
    $(remove_button).parent().parent().parent().remove();
}
