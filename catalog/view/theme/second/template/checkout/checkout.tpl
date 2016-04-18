<?php echo $header; ?>
<?php if($top) : ?>
<div id="top-block">
    <?php echo $top; ?>
</div>
<?php endif; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>

        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <div class="lead text-center row checkout-wizard">
                <div id="ar-step-account" class="col-xs-4 checkout-step" data-checkout-step="1">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <div class="fa-stack">
                        <i class="fa fa-circle fa-stack-2x checkout-header-circle"></i>
                        <i class="fa fa-user fa-stack-1x fa-inverse checkout-header-icon"></i>
                    </div>
                    <div class="fa-title">
                        <span><?php echo $text_account; ?></span>
                    </div>
                </div>
                <div id="ar-step-address" class="col-xs-4 checkout-step" data-checkout-step="2">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <div class="fa-stack">
                        <i class="fa fa-circle fa-stack-2x checkout-header-circle"></i>
                        <i class="fa fa-truck fa-stack-1x fa-inverse checkout-header-icon"></i>
                    </div>
                    <div class="fa-title">
                        <span><?php echo $text_address; ?></span>
                    </div>
                </div>
                <div id="ar-step-payment" class="col-xs-4 checkout-step" data-checkout-step="3">
                    <div class="progress"><div class="progress-bar"></div></div>
                    <div class="fa-stack">
                        <i class="fa fa-circle fa-stack-2x checkout-header-circle"></i>
                        <i class="fa fa-credit-card fa-stack-1x fa-inverse checkout-header-icon"></i>
                    </div>
                    <div class="fa-title">
                        <span><?php echo $text_payment; ?></span>
                    </div>
                </div>
            </div>

            <div class="col-sm-4 panel-np-left">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_checkout_option; ?></h4>
                    </div>
                    <div id="ar-account-select">
                        <div class="panel-body">
                            <div class="radio">
                                <label>
                                    <input type="radio" id="ar-account-login" value="login" name="ar-account-name">
                                    <?php echo $text_login; ?>
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" id="ar-account-register" value="register" name="ar-account-name" checked="checked">
                                    <?php echo $text_register; ?>
                                </label>
                            </div>
                            <?php if ($checkout_guest) { ?>
                            <div class="radio">
                                <label>
                                    <input type="radio" id="ar-account-guest" value="guest" name="ar-account-name">
                                    <?php echo $text_guest; ?>
                                </label>
                            </div>
                            <p><?php echo $text_register_account; ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_checkout_shipping_method; ?></h4>
                    </div>
                    <div id="ar-left-1">
                        <div class="panel-body"></div>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"><?php echo $text_totals; ?></h4>
                    </div>
                    <div id="ar-left-2">
                        <div class="panel-body"></div>
                    </div>
                </div>
            </div>

            <div class="col-sm-8 panel-np-right">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title"></h4>
                    </div>
                    <div id="ar-right-1">
                        <div class="panel-body"></div>
                    </div>
                </div>
            </div>

        </div>
        <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>

<script type="text/javascript"><!--
// Account
$(document).on('change', 'input[name=\'ar-account-name\']:checked', function() {
    var checkout_select = $(this).attr('value');

    var ajax_url = 'index.php?route=checkout/'+checkout_select;

    if (checkout_select == 'register') {
        $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_account_details; ?>');
    } else if (checkout_select == 'guest') {
        $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_personal_details; ?>');
    }
    else if (checkout_select == 'login') {
        $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_login; ?>');
    }

    $.ajax({
        url: ajax_url,
        type: 'post',
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-right-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-right-1 .panel-body').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Login save
$(document).on('click', '#button-login', function() {
    $.ajax({
        url: 'index.php?route=checkout/login/save',
        type: 'post',
        data: $('#ar-right-1 :input'),
        dataType: 'json',
        beforeSend: function() {
            $('#button-login').button('loading');
        },
        complete: function() {
            $('#button-login').button('reset');
        },
        success: function(json) {
            $('.alert, .text-danger').remove();
            $('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                $('#ar-right-1 .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                // Highlight any found errors
                $('input[name=\'email\']').parent().addClass('has-error');
                $('input[name=\'password\']').parent().addClass('has-error');
            }
            else {
                $('#ar-step-account').find('.fa-stack').addClass('checkout-pointer');
                $('#ar-step-account').find('.fa-title').addClass('checkout-pointer');
                $('#ar-step-account').removeClass('text-primary');
                $('#ar-step-account').addClass('text-success');
                $('#ar-step-address').removeClass('text-muted');
                $('#ar-step-address').addClass('text-primary');

                $('#ar-account-select').parent().hide();

                <?php if ($shipping_required) { ?>
                $('#ar-left-1').parent().show();
                <?php } ?>

            $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_address; ?>');

            loadAddress();

            $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// Register save
$(document).on('click', '#button-register', function() {
    $.ajax({
        url: 'index.php?route=checkout/register/save',
        type: 'post',
        data: $('#ar-right-1 input[type=\'text\'], #ar-right-1 input[type=\'date\'], #ar-right-1 input[type=\'datetime-local\'], #ar-right-1 input[type=\'time\'], #ar-right-1 input[type=\'password\'], #ar-right-1 input[type=\'hidden\'], #ar-right-1 input[type=\'checkbox\']:checked, #ar-right-1 input[type=\'radio\']:checked, #ar-right-1 textarea, #ar-right-1 select'),
        dataType: 'json',
        beforeSend: function() {
            $('#button-register').button('loading');
        },
        complete: function() {
            $('#button-register').button('reset');
        },
        success: function(json) {
            $('.alert, .text-danger').remove();
            $('.form-group').removeClass('has-error');

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#ar-right-1 .panel-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                for (i in json['error']) {
                    var element = $('#input-register-' + i.replace(/_/g, '-'));

                    if ($(element).parent().hasClass('input-group')) {
                        $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                    } else {
                        $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                    }
                }

                // Highlight any found errors
                $('.text-danger').parent().addClass('has-error');
                $('.form-group.col-sm-6').css('margin-bottom', '30px');
            } else {
                $('#ar-step-account').find('.fa-stack').addClass('checkout-pointer');
                $('#ar-step-account').find('.fa-title').addClass('checkout-pointer');
                $('#ar-step-account').removeClass('text-primary');
                $('#ar-step-account').addClass('text-success');
                $('#ar-step-address').removeClass('text-muted');
                $('#ar-step-address').addClass('text-primary');

                $('#ar-account-select').parent().hide();

                <?php if ($shipping_required) { ?>
                $('#ar-left-1').parent().show();
                <?php } ?>

            $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_address; ?>');

            loadAddress();

            $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// Guest save
$(document).on('click', '#button-guest', function() {
    $.ajax({
        url: 'index.php?route=checkout/guest/save',
        type: 'post',
        data: $('#ar-right-1 input[type=\'text\'], #ar-right-1 input[type=\'date\'], #ar-right-1 input[type=\'datetime-local\'], #ar-right-1 input[type=\'time\'], #ar-right-1 input[type=\'checkbox\']:checked, #ar-right-1 input[type=\'radio\']:checked, #ar-right-1 input[type=\'hidden\'], #ar-right-1 textarea, #ar-right-1 select'),
        dataType: 'json',
        beforeSend: function() {
            $('#button-guest').button('loading');
        },
        complete: function() {
            $('#button-guest').button('reset');
        },
        success: function(json) {
            $('.alert, .text-danger').remove();

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#ar-right-1 .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                for (i in json['error']) {
                    var element = $('#input-guest-' + i.replace(/_/g, '-'));

                    if ($(element).parent().hasClass('input-group')) {
                        $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                    } else {
                        $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                    }
                }

                // Highlight any found errors
                $('.text-danger').parent().addClass('has-error');
                $('.form-group.col-sm-6').css('margin-bottom', '30px');
            } else {
                $('#ar-step-account').find('.fa-stack').addClass('checkout-pointer');
                $('#ar-step-account').find('.fa-title').addClass('checkout-pointer');
                $('#ar-step-account').removeClass('text-primary');
                $('#ar-step-account').addClass('text-success');
                $('#ar-step-address').removeClass('text-muted');
                $('#ar-step-address').addClass('text-primary');

                $('#ar-account-select').parent().hide();

                <?php if ($shipping_required) { ?>
                $('#ar-left-1').parent().show();
                <?php } ?>

            $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_address; ?>');

            loadAddress();

            $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// Address save
$(document).on('click', '#button-address', function() {
    $.ajax({
        url: 'index.php?route=checkout/address/save',
        type: 'post',
        data: $('#ar-left-1 input[type=\'radio\']:checked, #ar-right-1 input[type=\'text\'], #ar-right-1 input[type=\'date\'], #ar-right-1 input[type=\'datetime-local\'], #ar-right-1 input[type=\'time\'], #ar-right-1 input[type=\'password\'], #ar-right-1 input[type=\'checkbox\']:checked, #ar-right-1 input[type=\'radio\']:checked, #ar-right-1 input[type=\'hidden\'], #ar-right-1 textarea, #ar-right-1 select'),
        dataType: 'json',
        beforeSend: function() {
            $('#button-address').button('loading');
        },
        complete: function() {
            $('#button-address').button('reset');
        },
        success: function(json) {
            $('.alert, .text-danger').remove();

            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#ar-right-1 .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                for (i in json['error']) {
                    var element = $('#input-' + i.replace(/_/g, '-'));

                    if ($(element).parent().hasClass('input-group')) {
                        $(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
                    } else {
                        $(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
                    }
                }

                // Highlight any found errors
                $('.text-danger').parent().addClass('has-error');
            } else {
                $('#ar-step-address').find('.fa-stack').addClass('checkout-pointer');
                $('#ar-step-address').find('.fa-title').addClass('checkout-pointer');
                $('#ar-step-account').addClass('text-success');
                $('#ar-step-address').addClass('text-success');
                $('#ar-step-payment').addClass('text-primary');

                $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_confirm; ?>');
                $('#ar-left-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_checkout_payment_method; ?>');

                $('#ar-right-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');

                $('#ar-left-1').parent().show();

                <?php if ($shipping_required) { ?>
                $('#ar-left-2').parent().find('.panel-heading .panel-title').html('<?php echo $text_delivery_address; ?>');
                $('#ar-left-2 .panel-body').html(json['address']);
                <?php } else { ?>
                $('#ar-left-2').parent().hide();
                <?php } ?>

            loadPaymentMethodsByAddressId($('select[name=\'address_id\']').val());

            $('html, body').animate({ scrollTop: 0 }, 'slow');
      }
    },
    error: function(xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });
});

// ToS control
<?php if (!empty($error_agree)) { ?>
    $(document).on('submit', '#ar-right-1 form', function(event) {
        if (!checkTerms()) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });

    $(document).on('click', '#button-confirm', function(event) {
        if (!checkTerms()) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        }
    });
    <?php } ?>

// Steps
$(document).on('click', '.checkout-pointer', function() {
    var checkout_step = $(this).parent().attr('id');

    if (checkout_step == 'ar-step-account') {
        $('#ar-step-payment').removeClass('text-primary');
        $('#ar-step-payment').addClass('text-muted');
        $('#ar-step-address').removeClass('text-success');
        $('#ar-step-address').removeClass('text-primary');
        $('#ar-step-address').addClass('text-muted');
        $('#ar-step-address').find('.fa-stack').removeClass('checkout-pointer');
        $('#ar-step-address').find('.fa-title').removeClass('checkout-pointer');
        $('#ar-step-account').removeClass('text-success');
        $('#ar-step-account').addClass('text-primary');
        $('#ar-step-account').find('.fa-stack').removeClass('checkout-pointer');
        $('#ar-step-account').find('.fa-title').removeClass('checkout-pointer');

        $('#ar-account-select').parent().show();
        $('#ar-left-1').parent().hide();
        $('#ar-left-2').parent().find('.panel-heading .panel-title').html('<?php echo $text_totals; ?>');

        $('input[name=\'ar-account-name\']:checked').trigger('change');

        loadTotals();
    }
    else if (checkout_step == 'ar-step-address') {
        $('#ar-step-address').find('.fa-stack').removeClass('checkout-pointer');
        $('#ar-step-address').find('.fa-title').removeClass('checkout-pointer');
        $('#ar-step-address').removeClass('text-success');
        $('#ar-step-address').addClass('text-primary');
        $('#ar-step-payment').removeClass('text-primary');
        $('#ar-step-payment').addClass('text-muted');

        $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_address; ?>');

        <?php if (!$shipping_required) { ?>
        $('#ar-left-1').parent().hide();
        <?php } ?>

    $('#ar-left-2').parent().find('.panel-heading .panel-title').html('<?php echo $text_totals; ?>');
    $('#ar-left-2').parent().show();

    loadAddress();
  }
});

// Check ToS box
function checkTerms() {
    <?php if (empty($error_agree)) { ?>
    return true;
    <?php } ?>

if (!$('input[name=\'agree\']').is(':checked')) {
    if(!$('.alert').length) {
      $('#ar-right-1 .panel-body').prepend('<div class="alert alert-danger"><?php echo str_replace('&amp;', '&', htmlspecialchars($error_agree, ENT_QUOTES)); ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>');
}

return false;
}
else {
    $('.alert').remove();

    return true;
}
}

// Load totals
function loadTotals() {
    $.ajax({
        url: 'index.php?route=checkout/totals',
        type: 'post',
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-left-2 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-left-2 .panel-body').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Load totals by address id
function loadTotalsByAddressId(address_id) {
    $.ajax({
        url: 'index.php?route=checkout/totals',
        type: 'post',
        data: {address_id: address_id},
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-left-2 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-left-2 .panel-body').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Load totals by new address fields
function loadTotalsByAddressFields(address_type, address_country_id, address_zone_id, address_city, address_postcode) {
    $.ajax({
        url: 'index.php?route=checkout/totals',
        type: 'post',
        data: {address_type: address_type, address_country_id: address_country_id, address_zone_id: address_zone_id, address_city: address_city, address_postcode: address_postcode},
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-left-2 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-left-2 .panel-body').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Load address
function loadAddress() {
    $.ajax({
        url: 'index.php?route=checkout/address',
        type: 'post',
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-right-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-right-1 .panel-body').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Methods related with shipping
<?php if ($shipping_required) { ?>

// Load shipping methods
    function loadShippingMethods() {
        $.ajax({
            url: 'index.php?route=checkout/shipping_method',
            type: 'post',
            dataType: 'html',
            cache: false,
            beforeSend: function() {
                $('#ar-left-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
            },
            success: function(html) {
                $('#ar-left-1 .panel-body').html(html);
                saveShippingMethod();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

// Load shipping methods by addresss id
    function loadShippingMethodsByAddressId(address_id) {
        $.ajax({
            url: 'index.php?route=checkout/shipping_method',
            type: 'post',
            data: {address_id: address_id},
            dataType: 'html',
            cache: false,
            beforeSend: function() {
                $('#ar-left-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
            },
            success: function(html) {
                $('#ar-left-1 .panel-body').html(html);
                saveShippingMethod();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

// Load shipping methods by new address fields
    function loadShippingMethodsByAddressFields(address_type, address_country_id, address_zone_id, address_city, address_postcode) {
        $.ajax({
            url: 'index.php?route=checkout/shipping_method',
            type: 'post',
            data: {address_type: address_type, address_country_id: address_country_id, address_zone_id: address_zone_id, address_city: address_city, address_postcode: address_postcode},
            dataType: 'html',
            cache: false,
            beforeSend: function() {
                $('#ar-left-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
            },
            success: function(html) {
                $('#ar-left-1 .panel-body').html(html);
                saveShippingMethod();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

// Save the selected shipping method
    function saveShippingMethod() {
        var shipping_method = $('input[name=\'shipping_method\']:checked').attr('value');

        if (shipping_method == undefined) {
            shipping_method = 0;
        }

        $.ajax({
            url: 'index.php?route=checkout/shipping_method/save',
            type: 'post',
            data: {shipping_method: shipping_method},
            dataType: 'html',
            cache: false,
            success: function(json) {
                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    if (json['error']['warning']) {
                        $('#ar-left-1 .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                    }
                } else {
                    loadTotals();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
    <?php } ?>

// Load payment methods
function loadPaymentMethods() {
    $.ajax({
        url: 'index.php?route=checkout/payment_method',
        type: 'post',
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-left-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-left-1 .panel-body').html(html);
            savePaymentMethod();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Load payment methods by addresss id
function loadPaymentMethodsByAddressId(address_id) {
    $.ajax({
        url: 'index.php?route=checkout/payment_method',
        type: 'post',
        data: {address_id: address_id},
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-left-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(html) {
            $('#ar-left-1 .panel-body').html(html);
            savePaymentMethod();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Save the selected payment method
function savePaymentMethod() {
    var payment_method = $('input[name=\'payment_method\']:checked').attr('value');

    if (payment_method == undefined) {
        payment_method = 0;
    }

    $.ajax({
        url: 'index.php?route=checkout/payment_method/save',
        type: 'post',
        data: {payment_method: payment_method},
        dataType: 'html',
        cache: false,
        beforeSend: function() {
            $('#ar-right-1 .panel-body').html('<div class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
        },
        success: function(json) {
            if (json['redirect']) {
                location = json['redirect'];
            } else if (json['error']) {
                if (json['error']['warning']) {
                    $('#ar-left-1 .panel-body').prepend('<div class="alert alert-warning">' + json['error']['warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }
            } else {
                loadConfirm();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Load confirm page
function loadConfirm() {
    $.ajax({
        url: 'index.php?route=checkout/confirm',
        type: 'post',
        dataType: 'html',
        cache: false,
        success: function(html) {
            $('#ar-right-1 .panel-body').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// Initial actions
<?php if (!$logged) { ?>
    $('#ar-left-1').parent().hide();

    $('#ar-step-account').addClass('text-primary');
    $('#ar-step-address').addClass('text-muted');
    $('#ar-step-payment').addClass('text-muted');

    $('input[name=\'ar-account-name\']:checked').trigger('change');

    loadTotals();

    <?php } else { ?>
    $('#ar-step-account').find('.fa-stack').addClass('checkout-pointer');
    $('#ar-step-account').find('.fa-title').addClass('checkout-pointer');
    $('#ar-step-account').addClass('text-success');
    $('#ar-step-address').addClass('text-primary');
    $('#ar-step-payment').addClass('text-muted');

    $('#ar-account-select').parent().hide();
    $('#ar-right-1').parent().find('.panel-heading .panel-title').html('<?php echo $text_address; ?>');

    <?php if (!$shipping_required) { ?>
    $('#ar-left-1').parent().hide();
    <?php } ?>

loadAddress();

<?php } ?>
//--></script>
<?php if($bottom_a) : ?>
<div id="bottom-a-block">
    <div class="container">
        <?php echo $bottom_a; ?>
    </div>
</div>
<?php endif; ?>
<?php if($bottom_b) : ?>
<div id="bottom-b-block">
    <div class="container">
        <?php echo $bottom_b; ?>
    </div>
</div>
<?php endif; ?>
<?php if($bottom_c) : ?>
<div id="bottom-c-block">
    <div class="container">
        <?php echo $bottom_c; ?>
    </div>
</div>
<?php endif; ?>
<?php echo $footer; ?>
