$(document).ready(function() {
    $(document).on('click', '.panel-chevron', function(e) {
        e.preventDefault();
				
		var content =  $(this).parent().parent().parent().find('.panel-body');
		
		$(content).slideToggle();
		$(this).toggleClass('rotate');
    });
});

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

                    var html  = '<a href="" id="thumb-image" data-toggle="image" class="img-thumbnail" style="display: inherit;">';
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
