$(document).ready(function() {
	var tmpl = $.summernote.renderer.getTemplate();
	var editor = $.summernote.eventHandler.getModule('editor');

	$.summernote.addPlugin({
		name: 'readmore',

		buttons: {
			readmore: function (lang) {
				return tmpl.iconButton('arastta-readmore', {
					event : 'readmore',
					title: lang.readmore.title,
					hide: true
				});
			}
		},

		events: {
			readmore: function (event, editor, layoutInfo, value) {
				var $editable = layoutInfo.editable();

				var html  = '<p></p>';
					html += '<img src="view/image/read-more.png" title="Read More" alt="" data-readmore="more" data-readmore-text="" data-mce-resize="false" data-mce-placeholder="1" data-mce-selected="1">';
					html += '<p></p>';

				layoutInfo.holder().summernote("pasteHTML", html);
			}
		},
		
		langs: {
		  'en-US': {
			readmore: {
			  title: 'Insert read more tag'
			}
		  }
		}
	});

	$('form textarea').bind('summernote.submit', function() {
		$.each( $('form textarea'), function( key, value ) {
			editor_content = value.id;

			if ($('#' + editor_content).code() != '' && $('#' + editor_content).code() != undefined){
				var content = $('#' + editor_content).code();

				content = content.replace(/<img[^>]+>/g, function( image ) {
					if ( image.indexOf( 'data-readmore="more"' ) !== -1 ) {
						return '<p><!--readmore--></p>';
					} else {
                        return image;
                    }
				});

				value.value = content;
			}
		});
	});

	$('form textarea').bind('summernote.init', function() {
		$.each( $('textarea'), function( key, value ) {
			editor_content = value.id;

			if ($('#' + editor_content).code() != '' && $('#' + editor_content).code() != undefined){
				var content = $('#' + editor_content).code();

				if ( content.indexOf( '<!--readmore' ) !== -1 ) {
					content = content.replace( /<!--readmore(.*?)-->/g, function( match, moretext ) {
						return '<img src="view/image/read-more.png" title="Read More" alt="" data-readmore="more" data-readmore-text="" data-mce-resize="false" data-mce-placeholder="1" data-mce-selected="1">';
					});
				}
				$('#' + editor_content).code(content);
			}
		});
	});
});