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

Marketplace.loadweb = function(url) {
	if ('' == url) { return false; }

	url += '&list='+(Marketplace.apps.list ? 'list' : 'grid')+'&version='+apps_version+'&token='+token;

	$('#marketplace-loading')
		.css("top", $('.panel-body').position().top - $(window).scrollTop())
		.css("left", $('.panel-body').position().left - $(window).scrollLeft())
		.css("width", $('.panel-body').width())
		.css("height", $('.panel-body').height());
	$.event.trigger("ajaxStart");

	$.ajax({
		url: url,
		dataType: 'jsonp',
		cache: true,
		jsonpCallback: "arapi_jsonpcallback",
		beforeSend: function () {
			$('#customizer-preview').html('<div id="customizer-loading" class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
		},
		success: function (response) {
			$('#web-loader').hide();
			if (response.error) {
				$('.panel.panel-default').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> '+response.error.warning+'<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			} else {
				$('.panel-body').html(response.html);
			}
			if ($('.panel-body').length) {
				$.event.trigger("ajaxStop");
			}
		},
		fail: function() {
			$('#web-loader').hide();
			$('#web-loader-error').show();
			if ($('.panel-body').length) {
				$.event.trigger("ajaxStop");
			}
		},
		complete: function() {
			if ($('.panel-body').length) {
				$.event.trigger("ajaxStop");
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	return true;
};

var step = new Array();
Marketplace.installfromweb = function(install_url, product_id, product_name, product_version, obj) {
	if ('' == install_url) {
		alert("This extension cannot be installed via the web. Please visit the developer's website to purchase/download.");
		return false;
	}
	$.ajax({
		url: 'index.php?route=extension/installer/install&token=' + token,
		dataType: 'json',
		type: 'post',
		data: { install_url: install_url},
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
				next(install_url, product_id, product_name, product_version);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
	return true;
};

Marketplace.apps.initialize = function() {
	Marketplace.apps.loaded = 1;

	Marketplace.loadweb(baseUrl+'index.php?route=extension/marketplace/api&api=api/marketplace');
};

Marketplace.cart = {
	'add': function(product_id, quantity) {
		$.ajax({
			url: baseUrl+'index.php?route=extension/marketplace/api&api=api/cart/add&version='+apps_version+'&token='+token+'&store='+store,
			type: 'post',
			data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
			dataType: 'json',
			beforeSend: function() {
				$('#cart > button').button('loading');
			},
			complete: function() {
				$('#cart > button').button('reset');
			},
			success: function(json) {
				$('.alert, .text-danger').remove();

				if (json['redirect']) {
					location = json['redirect'];
				}

				if (json['success']) {
					//$('#marketplace-content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					// Need to set timeout otherwise it wont update the total
					setTimeout(function () {
						$('#cart > a').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
					}, 100);

					$('html, body').animate({ scrollTop: 0 }, 'slow');

				}
			},
			error: function(request, status, error) {
				if (request.responseText) {
					$('#web-loader-error').html(request.responseText);
				}
				$('#web-loader').hide();
				$('#web-loader-error').show();
				if ($('.panel-body').length) {
					$.event.trigger("ajaxStop");
				}
			}
		});
	}
}

$(document).on('keypress', '#marketplace-search-input', function() {
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

function next(install_url, product_id, product_name, product_version) {
	data = step.shift();
	if (data) {
		$.ajax({
			url: data.url,
			type: 'post',
			dataType: 'json',
			data: { path: data.path, install_url: install_url, product_id: product_id, product_name: product_name, product_version: product_version },
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
					next(install_url, product_id, product_name, product_version);
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
		url: 'index.php?route=extension/installer/uninstall&product_id=' + $(this).attr('data-product-id') + '&token=' + token,
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
		url: 'index.php?route=extension/installer/uninstall&product_id=' + $(this).attr('data-product-id') + '&token=' + token,
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
$(document).on('click', '#marketplace-menu a[onclick]', function() {
	sessionStorage.setItem('marketplace-menu', $(this).attr('onclick'));
});

$(document).ready(function() {
});