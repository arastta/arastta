var Layout = function() {
    var token = getURLVar('token');
    var confirm_text = 'Are you sure?';
    var module_list_title = 'Edit module';
    var refresh = function() {
        Layout.handleAccordion();
        Layout.handleDraggable();

        modulesBarHeight = parseInt($('.accordion-content-drop').height());

        $('.module_list').height(modulesBarHeight - 20);

        $('.dashed').bind('sortupdate', function(dataAndEvents, deepDataAndEvents) {
            var options = 0;

            $('.dashed>.mblock').each(function() {
                options += 1;
                var collection = $(this).find('input.sort');
                collection.attr('value', options);
            });

        });

    };

    return {
        refresh_module_list : function() {
            $.ajax({
                url : 'index.php?route=appearance/layout/getModuleList&token=' + token,
                dataType : 'html',
                success : function(textStatus) {
                    var el = $('.module_accordion').html(textStatus);
                    el.find('.btn-edit').click(btn_edit_click);
                    Layout['handleAccordion']();
                    Layout['handleDraggable']();
                }
            });
        },
        handleAccordion : function() {
            $('.accordion>.accordion-content').css('display', 'none');

            $('.accordion>h4,.accordion>.accordion-heading').click(function() {
                if (!$(this).hasClass('active')) {
                    $(this).addClass('active').siblings('h4,.accordion-heading').removeClass('active');

                    var statsTemplate = $(this).next('.accordion-content');

                    $(statsTemplate).slideDown(350).siblings('.accordion-content').slideUp(350);
                }
            });

            $('.accordion h4:first-child,.accordion .accordion-heading:first-child').each(function(dataAndEvents, deepDataAndEvents) {
                $(this).click();
            });
        },
        handleDraggable : function() {
            $('.module-block').draggable({
                appendTo : document['body'],
                helper   : 'clone',
                cursor   : 'move',
                zIndex   : 9999,
                cancel   : '.btn-remove, .btn-edit',
                distance : 2,
                cursorAt : {
                    left : 10,
                    top  : 10
                }
            });

            $('.dashed').droppable({
                activeClass             : 'activeDroppable',
                hoverClass              : 'hoverDroppable',
                tolerance               : 'pointer',
                forceHelperSize         : false,
                forcePlaceholderSize    : false,
                accept                  : '.module-block',
                cancel                  : '.btn-remove, .btn-edit',
                drop                    : function(event, ev) {
                    var data_code = $(ev['draggable']).attr('data-code');
                    var module_settnig_link = $(ev['draggable']).find('a').attr('href');
                    var data_position = $(this).attr('data-position');
                    var cDigit = $('#data_index').attr('data-index');
                    var confirm_text = $('#module_list').attr('data-text-confirm');
                    var module_list_title = $('#module_list').attr('data-text-edit');
                    var sort_order_value = 0;

                    sort_order_value = $(this).find('.mblock').length;

                    var html  = '<div class="mblock ui-draggable ui-draggable-handle" data-code="' + data_code + '">';
                        html += '   <div class="mblock-header">';
                        html += '         <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i><span class="module-name">' + ev['draggable']['text']() + '</span></div>';
                        html += '   </div>';
                        html += '    <div class="mblock-control-menu ui-sortable-handle">';
                        html += '        <div class="mblock-action">';
                        html += '            <a class="btn btn-xs btn-edit" onclick="moduleSetting(\'' + module_settnig_link + '\')" data-toggle="tooltip" title="' + module_list_title + '"> <i class="fa fa-cog"></i></a>';
                        html += '        </div>';
                        html += '        <div class="mblock-action pull-right">';
                        html += '            <a class="btn btn-xs btn-remove" onclick="confirm(\'' + confirm_text + '\') ? removeLayoutModule(\'<?php echo $layout_id; ?>\', \'<?php echo $module_id; ?>\', $(this)):false;"><i class="fa fa-trash-o"></i></a>';
                        html += '        </div>';
                        html += '    </div>';
                        html += '    <input type="hidden" name="layout_module[' + cDigit + '][code]" value="' + data_code + '"/>';
                        html += '    <input type="hidden" name="layout_module[' + cDigit + '][position]" class="layout_position" value="' + data_position + '"/>';
                        html += '    <input type="hidden" name="layout_module[' + cDigit + '][sort_order]" value="' + sort_order_value + '" class="sort"/>';
                        html += '</div>';

                    $('.accordion-content-drop').find(ev['draggable']).remove();

                    $(this).append(html);

                    $('#data_index').attr('data-index', parseInt(cDigit) + 1);

                    var moduleListHeigth = $('.row.colsliders .col-md-6').height() - 12;

                    var moduleCol = moduleListHeigth - 100;

                    $('.row.colsliders .col-md-3.sidebar_column').attr('style','min-height:' + moduleListHeigth + 'px !important;');
                    $('.row.colsliders .col-md-3.sidebar_column .dashed').attr('style','min-height:' + moduleCol + 'px !important;');
                }
            }).sortable({
                appendTo    : document['body'],
                helper      : 'clone',
                placeholder : 'hoverDroppable',
                zIndex      : 99999,
                dropOnEmpty : true,
                connectWith : '.dashed',
                items       : '.mblock',
                cancel      : '.btn-edit, .btn-remove',
                update      : function(allBindingsAccessor, stopHere) {
                    $(this).find('.layout_position').attr('value', $(this).attr('data-position'));
                }
            }).disableSelection();
        },
        addModule : function(module, array, moduleInstance, ext) {
            var module_data = $('#data_index').attr('data-index');
            var confirm_text = $('#module_list').attr('data-text-confirm');
            var module_list_title = $('#module_list').attr('data-text-edit');

            var html  = '<div class="mblock ui-draggable ui-draggable-handle" data-code="' + module + '">';
                html += '   <div class="mblock-header">';
                html += '         <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i><span class="module-name">' + moduleInstance + '</span></div>';
                html += '   </div>';
                html += '    <div class="mblock-control-menu ui-sortable-handle">';
                html += '        <div class="mblock-action">';
                html += '            <a class="btn btn-xs btn-edit" onclick="moduleSetting(\'' + ext + '\')" data-toggle="tooltip" title="' + module_list_title + '"> <i class="fa fa-cog"></i></a>';
                html += '        </div>';
                html += '        <div class="mblock-action pull-right">';
                html += '            <a class="btn btn-xs btn-remove" onclick="confirm(\'' + confirm_text + '\') ? removeLayoutModule(\'<?php echo $layout_id; ?>\', \'<?php echo $module_id; ?>\', $(this)) : false;"><i class="fa fa-trash-o"></i></a>';
                html += '        </div>';
                html += '    </div>';
                html += '    <input type="hidden" name="layout_module[' + module_data + '][code]" value="' + module + '"/>';
                html += '    <input type="hidden" name="layout_module[' + module_data + '][position]" class="layout_position" value="' + array + '"/>';
                html += '    <input type="hidden" name="layout_module[' + module_data + '][sort_order]" value="999" class="sort"/>';
                html += '</div>';

            $("div[data-position='" + array + "']").append(html);
        },
        init : function() {
            refresh();
        }
    };
}();

function moduleSetting (url) {
    var data_href = url;
    
    $('#model-large').attr('src',data_href);
    $('#module-modal').modal('show');
}

function refresh_layout() {
    $.ajax({
        url: 'index.php?route=appearance/layout/getLayoutList&token=' + token,
        type: 'post',
        dataType: 'html',
        cache: false,
        success: function(html) {
            $('#change_layouts').after(html);
            $('#change_layouts').remove();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function btn_edit_click(event) {
    event.preventDefault();

    var data_href = $(this).attr('href');

    $('#model-large').attr('src',data_href);
    $('#module-modal').modal('show');
}

$(document).ready(function() {
    $(document).on('hide.bs.modal','.modal-box', function () {
        $('body').removeClass('modal-open');

        parent.$('iframe').removeClass('loading');
    });

    $('.btn-edit').on('click', btn_edit_click);

    $('#model-large').on('load', function(event) {
        event.preventDefault();

        var iframe = $('#model-large');
        var current_url = document.getElementById("model-large").contentWindow.location.href;

        iframe.contents().find('.page-header [href]').on('click', function(event) {
            $('#module-modal-loading').addClass('loading_iframe');
        });

        iframe.contents().find('form').on('submit', function(event) {
            $('#module-modal-loading').addClass('loading_iframe');
        });

        if (current_url.indexOf('extension/extension') > -1) {
            setTimeout(function(){
                $('#module-modal').modal('hide');
                $('body').removeClass('modal-open');
                Layout.refresh_module_list();
            }, 500);

            iframe.removeClass('loading');
        } else {
            iframe.contents().find('html,body').css({
                height: 'auto'
            });

            iframe.contents().find('#header, #content .page-header .breadcrumb, #column-left, #column-right, #footer').remove();
            iframe.contents().find('.pull-right.wide-button').removeClass('wide-button');
            iframe.contents().find('.pull-right.short-button').removeClass('short-button');
            iframe.contents().find('#content').css({marginLeft: '0px'});
            iframe.contents().find('#content').css({padding: '10px 0 0 0'});

            $('#module-modal-loading').removeClass('loading_iframe');
        }
    });

    $('.add-layout').on('click', function(event) {
        event.preventDefault();

        var data_href = $(this).attr('href');

        $('#layout-add-iframe').attr('src',data_href);
        $('#layout-add').modal('show');
    });

    $('#layout-add-iframe').on('load', function(event) {
        event.preventDefault();

        var iframe = $('#layout-add-iframe');
        var current_url = document.getElementById("layout-add-iframe").contentWindow.location.href;

        iframe.contents().find('.page-header [href]').on('click', function(event) {
            $('#layout-add-loading').addClass('loading_iframe');
        });

        iframe.contents().find('form').on('submit', function(event) {
            $('#layout-add-loading').addClass('loading_iframe');
        });

        if (current_url.indexOf('appearance/layout/add') < 0) {
            $('#layout-add').modal('hide');
        } else if (current_url.indexOf('appearance/layout/add') > -1) {
            iframe.contents().find('html,body').css({
                height: 'auto'
            });

            iframe.contents().find('#header, #content .page-header .breadcrumb, #column-left, #column-right, #footer, #module').remove();
            iframe.contents().find('.pull-right.wide-button').removeClass('wide-button');
            iframe.contents().find('.pull-right.short-button').removeClass('short-button');
            iframe.contents().find('#content').css({marginLeft: '0px'});
            iframe.contents().find('#content').css({padding: '10px 0 0 0'});

            $('#layout-add-loading').removeClass('loading_iframe');
        }
    });

    $('.edit-layout').on('click', function(event) {
        event.preventDefault();

        var data_href = $(this).attr('href');

        $('#layout-edit-iframe').attr('src',data_href);
        $('#layout-edit').modal('show');
    });

    $('#layout-edit-iframe').on('load', function(event) {
        event.preventDefault();

        var iframe = $('#layout-edit-iframe');
        var current_url = document.getElementById("layout-edit-iframe").contentWindow.location.href;

        iframe.contents().find('.page-header [href]').on('click', function(event) {
            $('#layout-edit-loading').addClass('loading_iframe');
        });

        iframe.contents().find('form').on('submit', function(event) {
            $('#layout-edit-loading').addClass('loading_iframe');
        });

        if (current_url.indexOf('appearance/layout/edit') < 0) {
            $('#layout-edit').modal('hide');
        } else if (current_url.indexOf('appearance/layout/edit') > -1) {
            iframe.contents().find('html,body').css({
                height: 'auto'
            });

            iframe.contents().find('#header, #content .page-header .breadcrumb, #column-left, #column-right, #footer, #module').remove();
            iframe.contents().find('.pull-right.wide-button').removeClass('wide-button');
            iframe.contents().find('.pull-right.short-button').removeClass('short-button');
            iframe.contents().find('#content').css({marginLeft: '0px'});
            iframe.contents().find('#content').css({padding: '10px 0 0 0'});

            $('#layout-edit-loading').removeClass('loading_iframe');
        }
    });

    $(document).delegate('.modalbox', 'click', function(e) {
        e.preventDefault();

        var element = this;

        var href  = $(element).attr('href') + '&with_iframe=true';
        var title = $(element).attr('data-title');

        if (title == '' || title == null) {
            title = $(element).text();
        }

        var data_id = 'modal-box';

        if ($(element).attr('data-id') != undefined) {
            data_id = $(element).attr('data-id');
        } else {
            data_id = 'modal-box';
        }

        var type = 'model-large';

        if ($(element).attr('data-size') != undefined) {
            size = $(element).attr('data-size');
        } else {
            size = 'model-large';
        }

        var type = 'html';

        if ($(element).attr('data-type') != undefined) {
            type = $(element).attr('data-type');
        } else if ($(element).hasClass('modalbox')) {
            type = 'html';
        } else {
            type = 'iframe';
        }

        if ($(element).attr('data-backdrop') != undefined) {
            $('body').addClass('hidden-backdrop');
        } else {
            $('body').removeClass('hidden-backdrop');
        }

        if (type == 'iframe') {

            $('#' + data_id).remove();

            html  = '<div id="' + data_id + '" class="modal-box modal fade">';
            html += '  <div class="modal-dialog ' + size + '">';
            html += '    <div class="modal-content">';
            html += '      <div class="modal-header">';
            html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html += '        <h4 class="modal-title">' + title + '</h4>';
            html += '      </div>';
            html += '      <div class="modal-body model-large"><iframe id="model-large" frameborder="0" src="' + href + '"></iframe></div>';
            html += '    </div';
            html += '  </div>';
            html += '</div>';

            $('body').append(html);
            $('#modal-box').modal('show');
        } else {
            $('#'+data_id).remove();

            $.ajax({
                url:href,
                type: 'get',
                dataType: 'html',
                success: function(data) {
                    html  = '<div id="' + data_id + '" class="modal-box modal fade">';
                    html += '  <div class="modal-dialog ' + size + '">';
                    html += '    <div class="modal-content">';
                    html += '      <div class="modal-header">';
                    html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
                    html += '        <h4 class="modal-title">' + title + '</h4>';
                    html += '      </div>';
                    html += '      <div class="modal-body modal-html">' + data + '</div>';
                    html += '    </div';
                    html += '  </div>';
                    html += '</div>';

                    $('body').append(html);
                    $('#' + data_id).modal('show');
                }
            });
        }

        $('#model-large').on('load', function(event) {
            event.preventDefault();

            var iframe = $('#model-large');
            var current_url = document.getElementById("model-large").contentWindow.location.href;

            iframe.contents().find('.page-header [href]').on('click', function(event) {
                iframe.addClass('loading');
            });

            iframe.contents().find('form').on('submit', function(event) {
                iframe.addClass('loading');
            });

            if (current_url.indexOf('extension/extension') > -1) {
                setTimeout(function(){
                    $('#modal-box').modal('hide');
                    $('body').removeClass('modal-open');
                }, 500);

                iframe.removeClass('loading');
            } else {
                iframe.contents().find('html,body').css({
                    height: 'auto'
                });

                iframe.contents().find('#header, #content .page-header .breadcrumb, #column-left, #column-right, #footer').remove();
                iframe.contents().find('.pull-right.wide-button').removeClass('wide-button');
                iframe.contents().find('.pull-right.short-button').removeClass('short-button');
                iframe.contents().find('#content').css({padding: '10px 0 0 0'});
                iframe.removeClass('loading');

                $('#modal-box').modal('show');
            }
        });
    });
});
