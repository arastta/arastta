$(document).ready(function() {
	var tmpl = $.summernote.renderer.getTemplate();
	var editor = $.summernote.eventHandler.getModule('editor');

	$.summernote.addPlugin({
		name: 'readmore',

		buttons: {
			readmore: function (lang) {
				return tmpl.iconButton('fa fa-indent', {
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
					html += '<img src="view/image/read-more.png" alt="Read More" title="Read More" />';
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
});