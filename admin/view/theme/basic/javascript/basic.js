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

            $('thead td:not(:first)').hide();
            $('.table.table-hover thead tr').append('<td id="td-selected"></td>');
            $('.item-selected').css('display', 'inline');
            $('.bulk-action-button').css('display', 'inline');
            $('.item-selected').html(selected + text_selected);
        } else {
            $('#td-selected').remove();
            $('thead td').show();
            $('.item-selected').css('display', 'none');
            $('.bulk-action-button').css('display', 'none');
            $('.bulk-caret').show();
            $('.bulk-action-activate').addClass('bulk-action');
            $('.bulk-action').removeClass('bulk-action-activate');
        }
    });
});

function save(type) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
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

        init : function() {
            start();
        }
    };
}();
