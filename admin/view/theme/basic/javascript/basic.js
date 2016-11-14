$(document).ready(function() {
    $(document).on('click', '.panel-chevron', function(e) {
        e.preventDefault();

        var content =  $(this).parent().parent().parent().find('.panel-body');

        $(content).slideToggle();
        $(this).toggleClass('rotate');
    });

    $('input[type=\'checkbox\']').click (function() {
        var checkboxes = $('form[id^=\'form\'] input[type=\'checkbox\']').not(':first');
        var selected = 0;

        $.each(checkboxes, function( index, value ) {
            var thisCheck = $(value);

            if (thisCheck.is(':checked')) {
                selected = selected + 1;
            }
        });

        if (selected) {
            $('.bulk-caret').hide();
            $('.bulk-action').addClass('bulk-action-activate');
            $('.bulk-action-activate').removeClass('bulk-action');

            /*if ($('table tbody').hasClass('sortable-list') || $('table tbody td:first').hasClass('sortable')) {
                $('thead td:not(:nth-child(2))').hide();
            } else {
                $('thead td:not(:first)').hide();
            }

            $('.table.table-hover thead tr').append('<td id="td-selected"></td>');*/
            $('.item-selected').css('display', 'inline');
            $('.bulk-action-button').css('display', 'inline');
            $('.item-selected').html(selected + text_selected);
        } else {
            //$('#td-selected').remove();
            //$('thead td').show();
            $('.item-selected').css('display', 'none');
            $('.bulk-action-button').css('display', 'none');
            $('.bulk-caret').show();
            $('.bulk-action-activate').addClass('bulk-action');
            $('.bulk-action').removeClass('bulk-action-activate');
        }
    });

    $('.well .row .input-group').keypress(function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);

        if(code == 13) { //Enter keycode
            filter();
        }
    });

    $('.well .row .input-group select').on('change', function() {
        filter();
    });

    $(".sortable-list").sortable({
        update : function() {
            $.ajax({
                url: 'index.php?route=common/edit/sortOrder&token=' + getURLVar('token'),
                type: 'post',
                dataType: 'json',
                data: $("form[id^='form-']").serialize() + '&' + String(document.location).split('?')[1],
                beforeSend: function() {
                    var html = '<div class="spinner"><span class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></span></div>';

                    $('.panel.panel-default').append(html);
                },
                success: function(json) {
                    $('.spinner').remove();

                    $('.alert-success, .alert-danger').remove();

                    if (json['error']) {
                        $('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        $('.panel.panel-default').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');
                    }
                }
            });
        },
        cancel: ".ui-state-disabled"
    });

    $(".sortable-list tr").disableSelection();

    $(document).on('click', '#sort-order-list', function(e) {
        e.preventDefault();

        var query = String(document.location).split('?');
        var table_body = $(this).parent().parent().parent().parent().find('tbody').hasClass('sortable-list');

        if (table_body) {
            var url = 'index.php?' + query[1].replace('&order=ASC&sortable=active', '');

            location = url;
        } else {
            var url = 'index.php?' + query[1] + '&order=ASC&sortable=active';

            location = url;
        }
    });
});

function confirmItem(title, message) {
    var html  = '<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">';
        html += '  <div class="modal-dialog">';
        html += '      <div class="modal-content">';
        html += '          <div class="modal-header">';
        html += '              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        html += '              <h4 class="modal-title" id="confirmModalLabel">' + title + '</h4>';
        html += '          </div>';
        html += '          <div class="modal-body">';
        html += '              <p>' + message + '</p>';
        html += '              <p></p>';
        html += '          </div>';
        html += '          <div class="modal-footer">';
        html += '              <button type="button" class="btn btn-default" data-dismiss="modal">' + button_cancel + '</button>';
        html += '              <button type="button" class="btn btn-danger" onclick="$(\'form[id^=\\\'form-\\\']\').submit();">' + button_delete + '</button>';
        html += '          </div>';
        html += '      </div>';
        html += '  </div>';
        html += '</div>';

    $('body').append(html);
    $('#confirm-modal').modal('show');
}

function confirmItemSetLink(title, message, link) {
    var html  = '<div class="modal fade" id="confirm-modal" tabindex="-1" role="dialog" aria-labelledby="confirmModalLabel" aria-hidden="true">';
        html += '  <div class="modal-dialog">';
        html += '      <div class="modal-content">';
        html += '          <div class="modal-header">';
        html += '              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
        html += '              <h4 class="modal-title" id="confirmModalLabel">' + title + '</h4>';
        html += '          </div>';
        html += '          <div class="modal-body">';
        html += '              <p>' + message + '</p>';
        html += '              <p></p>';
        html += '          </div>';
        html += '          <div class="modal-footer">';
        html += '              <button type="button" class="btn btn-default" data-dismiss="modal">' + button_cancel + '</button>';
        html += '              <a href="' + link + '" id="confirm-modal-delete" class="btn btn-danger">' + button_delete + '</a>';
        html += '          </div>';
        html += '      </div>';
        html += '  </div>';
        html += '</div>';

    $('body').append(html);
    $('#confirm-modal').modal('show');
}

function changeFilterType(text, filter_type) {
    $('.filter-type').text(text);

    $('.filter').addClass('hidden');
    $('input[name=\'' + filter_type + '\']').removeClass('hidden');
    $('select[name=\'' + filter_type + '\']').removeClass('hidden');

    if (filter_type == 'filter_date_added' || filter_type == 'filter_date_modified' || filter_type == 'filter_date_start' || filter_type == 'filter_date_end' || filter_type == 'filter_order_date' || filter_type == 'filter_invoice_date'){
        $('.well .input-group-btn .' + filter_type).removeClass('hidden');
        $('.well .input-group .' + filter_type).removeClass('hidden');
    }
}

function save(type) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
}

function removeFilter(filter_tag, filter_selector) {
    $('input[name=\'' + filter_selector  + '\']').val('');
    $('select[name=\'' + filter_selector  + '\'] option:selected').removeAttr('selected');
    $(filter_tag).parent().remove();
    filter();
}

var BasicImage = function() {

    var start = function() {
        BasicImage.handleDraggable();
    };

    return {
        handleDraggable : function() {
            $('.file-preview-frame').draggable({
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

            $('.images .col-sm-3').droppable({
                activeClass: 'activeDroppable',
                hoverClass: 'hoverDroppable',
                tolerance: 'pointer',
                forceHelperSize: false,
                forcePlaceholderSize: false,
                accept: '.file-preview-frame',
                cancel: '.btn-remove, .btn-edit',
                drop: function (event, ev) {
                    var image_full_path = $(ev['draggable']).children('img').prop('src');
                    var image = $(ev['draggable']).children('img').attr('data-code');

                    var html  = '<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail">';
                    html += '   <img src="' + image_full_path + '" alt="" title="" data-placeholder="' + image_full_path + '" />';
                    html += '</a>';
                    html += '<input type="hidden" name="image" value="' + image + '" id="input-image" />';

                    $(this).html(html);
                }
            }).disableSelection();
        },
        'manage': function() {
            $('#modal-image').remove();

            var element = this;

            $.ajax({
                url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&mode=basic',
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
        },
        init : function() {
            start();
        }
    };
}();

function removeBasicImage(image) {
    image.parent().parent().parent().parent().remove();
}