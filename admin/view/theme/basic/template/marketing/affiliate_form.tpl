<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-affiliate" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-affiliate" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-affiliate" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-affiliate" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-firstname"><?php echo $entry_firstname; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
                                        <?php if ($error_firstname) { ?>
                                        <div class="text-danger"><?php echo $error_firstname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-lastname"><?php echo $entry_lastname; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
                                        <?php if ($error_lastname) { ?>
                                        <div class="text-danger"><?php echo $error_lastname; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-email"><?php echo $entry_email; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                        <?php if ($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-telephone"><?php echo $entry_telephone; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                                        <?php if ($error_telephone) { ?>
                                        <div class="text-danger"><?php echo $error_telephone; ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-fax"><?php echo $entry_fax; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-company"><?php echo $entry_company; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="company" value="<?php echo $company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-website"><?php echo $entry_website; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="website" value="<?php echo $website; ?>" placeholder="<?php echo $entry_website; ?>" id="input-website" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-address-1"><?php echo $entry_address_1; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="address_1" value="<?php echo $address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1" class="form-control" />
                                        <?php if ($error_address_1) { ?>
                                        <div class="text-danger"><?php echo $error_address_1; ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-address-2"><?php echo $entry_address_2; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="address_2" value="<?php echo $address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-city"><?php echo $entry_city; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="city" value="<?php echo $city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city" class="form-control" />
                                        <?php if ($error_city) { ?>
                                        <div class="text-danger"><?php echo $error_city ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-postcode"><?php echo $entry_postcode; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode" class="form-control" />
                                        <?php if ($error_postcode) { ?>
                                        <div class="text-danger"><?php echo $error_postcode ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-country"><?php echo $entry_country; ?></label>
                                    <div class="col-sm-12">
                                        <select name="country_id" id="input-country" class="form-control">
                                            <option value=""><?php echo $text_select; ?></option>
                                            <?php foreach ($countries as $country) { ?>
                                            <?php if ($country['country_id'] == $country_id) { ?>
                                            <option value="<?php echo $country['country_id']; ?>" selected="selected"> <?php echo $country['name']; ?> </option>
                                            <?php } else { ?>
                                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <?php if ($error_country) { ?>
                                        <div class="text-danger"><?php echo $error_country; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-zone"><?php echo $entry_zone; ?></label>
                                    <div class="col-sm-12">
                                        <select name="zone_id" id="input-zone" class="form-control">
                                        </select>
                                        <?php if ($error_zone) { ?>
                                        <div class="text-danger"><?php echo $error_zone; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-code"><span data-toggle="tooltip" title="<?php echo $help_code; ?>"><?php echo $entry_code; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
                                        <?php if ($error_code) { ?>
                                        <div class="text-danger"><?php echo $error_code; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-password"><?php echo $entry_password; ?></label>
                                    <div class="col-sm-12">
                                        <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" autocomplete="off" id="input-password" class="form-control"  />
                                        <?php if ($error_password) { ?>
                                        <div class="text-danger"><?php echo $error_password; ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-confirm"><?php echo $entry_confirm; ?></label>
                                    <div class="col-sm-12">
                                        <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" autocomplete="off" id="input-confirm" class="form-control" />
                                        <?php if ($error_confirm) { ?>
                                        <div class="text-danger"><?php echo $error_confirm; ?></div>
                                        <?php  } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-status"><?php echo $entry_status; ?></label>
                                    <div class="col-sm-12">
                                        <select name="status" id="input-status" class="form-control">
                                            <?php if ($status) { ?>
                                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                            <option value="0"><?php echo $text_disabled; ?></option>
                                            <?php } else { ?>
                                            <option value="1"><?php echo $text_enabled; ?></option>
                                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if ($affiliate_id) { ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_commission; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="commission">
                                <div id="commission"></div>
                                <br />
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-description"><?php echo $entry_description; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-amount"><?php echo $entry_amount; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" class="form-control" />
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" id="button-commission" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_commission_add; ?></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <div class="right-col col-sm-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $text_publish; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="publish">
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $text_enabled; ?></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($status) { ?>
                                            <input type="radio" name="status" value="1" checked="checked" />
                                            <?php echo $text_enabled; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="status" value="1" />
                                            <?php echo $text_enabled; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$status) { ?>
                                            <input type="radio" name="status" value="0" checked="checked" />
                                            <?php echo $text_disabled; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="status" value="0" />
                                            <?php echo $text_disabled; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_payment; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="payment-detail">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-commission"><span data-toggle="tooltip" title="<?php echo $help_commission; ?>"><?php echo $entry_commission; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="commission" value="<?php echo $commission; ?>" placeholder="<?php echo $entry_commission; ?>" id="input-commission" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-tax"><?php echo $entry_tax; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="tax" value="<?php echo $tax; ?>" placeholder="<?php echo $entry_tax; ?>" id="input-tax" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $entry_payment; ?></label>
                                    <div class="col-sm-12">
                                        <div class="radio">
                                            <label>
                                                <?php if ($payment == 'cheque') { ?>
                                                <input type="radio" name="payment" value="cheque" checked="checked" />
                                                <?php } else { ?>
                                                <input type="radio" name="payment" value="cheque" />
                                                <?php } ?>
                                                <?php echo $text_cheque; ?></label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <?php if ($payment == 'paypal') { ?>
                                                <input type="radio" name="payment" value="paypal" checked="checked" />
                                                <?php } else { ?>
                                                <input type="radio" name="payment" value="paypal" />
                                                <?php } ?>
                                                <?php echo $text_paypal; ?></label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <?php if ($payment == 'bank') { ?>
                                                <input type="radio" name="payment" value="bank" checked="checked" />
                                                <?php } else { ?>
                                                <input type="radio" name="payment" value="bank" />
                                                <?php } ?>
                                                <?php echo $text_bank; ?></label>
                                        </div>
                                    </div>
                                </div>
                                <div id="payment-cheque" class="payment">
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-cheque"><?php echo $entry_cheque; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="cheque" value="<?php echo $cheque; ?>" placeholder="<?php echo $entry_cheque; ?>" id="input-cheque" class="form-control" />
                                            <?php if ($error_cheque) { ?>
                                            <div class="text-danger"><?php echo $error_cheque; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="payment-paypal" class="payment">
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-paypal"><?php echo $entry_paypal; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="paypal" value="<?php echo $paypal; ?>" placeholder="<?php echo $entry_paypal; ?>" id="input-paypal" class="form-control" />
                                            <?php if ($error_paypal) { ?>
                                            <div class="text-danger"><?php echo $error_paypal; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div id="payment-bank" class="payment">
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-bank-name"><?php echo $entry_bank_name; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-bank-name" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-bank-branch-number"><?php echo $entry_bank_branch_number; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" placeholder="<?php echo $entry_bank_branch_number; ?>" id="input-bank-branch-number" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-bank-swift-code"><?php echo $entry_bank_swift_code; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" placeholder="<?php echo $entry_bank_swift_code; ?>" id="input-bank-swift-code" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-bank-account-name"><?php echo $entry_bank_account_name; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" placeholder="<?php echo $entry_bank_account_name; ?>" id="input-bank-account-name" class="form-control" />
                                            <?php if ($error_bank_account_name) { ?>
                                            <div class="text-danger"><?php echo $error_bank_account_name; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-bank-account-number"><?php echo $entry_bank_account_number; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" placeholder="<?php echo $entry_bank_account_number; ?>" id="input-bank-account-number" class="form-control" />
                                            <?php if ($error_bank_account_number) { ?>
                                            <div class="text-danger"><?php echo $error_bank_account_number; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript"><!--
    $('select[name=\'country_id\']').on('change', function() {
        $.ajax({
            url: 'index.php?route=marketing/affiliate/country&token=<?php echo $token; ?>&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('input[name=\'postcode\']').parent().parent().addClass('required');
                } else {
                    $('input[name=\'postcode\']').parent().parent().removeClass('required');
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] && json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('select[name=\'zone_id\']').html(html);
                $('select[name=\'zone_id\']').selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'country_id\']').trigger('change');
    //--></script>
    <script type="text/javascript"><!--
    $('input[name=\'payment\']').on('change', function() {
        $('.payment').hide();

        $('#payment-' + this.value).show();
    });

    $('input[name=\'payment\']:checked').trigger('change');
    //--></script>
    <script type="text/javascript"><!--
    $('#commission').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#commission').load(this.href);
    });

    $('#commission').load('index.php?route=marketing/affiliate/commission&token=<?php echo $token; ?>&affiliate_id=<?php echo $affiliate_id; ?>');

    $('#button-commission').on('click', function() {
        $.ajax({
            url: 'index.php?route=marketing/affiliate/commission&token=<?php echo $token; ?>&affiliate_id=<?php echo $affiliate_id; ?>',
            type: 'post',
            dataType: 'html',
            data: 'description=' + encodeURIComponent($('#tab-commission input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-commission input[name=\'amount\']').val()),
            beforeSend: function() {
                $('#button-commission').button('loading');
            },
            complete: function() {
                $('#button-commission').button('reset');
            },
            success: function(html) {
                $('.alert').remove();

                $('#commission').html(html);

                $('#tab-commission input[name=\'amount\']').val('');
                $('#tab-commission input[name=\'description\']').val('');
            }
        });
    });
    //--></script></div>
<?php echo $footer; ?>
