$(document).ready(function() {
	tinymce.PluginManager.add('readmore', function(editor, url) {
		editor.addButton('readmore', {
			text: '',
			tooltip: "Read More",
			icon: 'readmore',
			onclick: function() {
				var html  = '<p></p>';
					html += '<!--readmore-->';
					html += '<p></p>';

				editor.insertContent(html)
			}
		});

		editor.addMenuItem('readmore', {
			text: 'Read More',
			icon: 'readmore',
			context: 'tools',
			onclick: function() {
				var html  = '<p></p>';
					html += '<!--readmore-->';
					html += '<p></p>';

				editor.insertContent(html)
			}
		});

		editor.on('BeforeSetContent', function( event ) {
			if ( event.content ) {
				if ( event.content.indexOf( '<!--readmore' ) !== -1 ) {
					event.content = event.content.replace( /<!--readmore(.*?)-->/g, function( match, moretext ) {
						return '<img src="view/image/read-more.png" title="Read More" alt="" data-readmore="more" data-readmore-text="" data-mce-resize="false" data-mce-placeholder="1" data-mce-selected="1">';
					});
				}

				// Remove spaces from empty paragraphs.
				event.content = event.content.replace( /<p>(?:&nbsp;|\u00a0|\uFEFF|\s)+<\/p>/gi, '<p><br /></p>' );
			}
		});

		// Replace images with tags
		editor.on( 'PostProcess', function( e ) {
			if ( e.get ) {
				e.content = e.content.replace(/<img[^>]+>/g, function( image ) {
					var match, moretext = '';

					if ( image.indexOf( 'data-readmore="more"' ) !== -1 ) {
						if ( match = image.match( /data-readmore-text="([^"]+)"/ ) ) {
							moretext = match[1];
						}

						image = '<!--readmore' + moretext + '-->';
					}

					return image;
				});
			}
		});
	});
});