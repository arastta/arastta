<div class="row">
    <div class="col-sm-12">
        <fieldset id="account">
            <legend><?php echo $text_guest_details; ?></legend>
            <div class="form-group col-sm-6" style="display: <?php echo (count($customer_groups) > 1 ? 'block' : 'none'); ?>;">
                <label class="control-label"><?php echo $entry_customer_group; ?></label>
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                <div class="radio">
                    <label>
                        <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                        <?php echo $customer_group['name']; ?></label>
                </div>
                <?php } else { ?>
                <div class="radio">
                    <label>
                        <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" />
                        <?php echo $customer_group['name']; ?></label>
                </div>
                <?php } ?>
                <?php } ?>
            </div>
            <div class="form-group col-sm-6 required">
                <label class="control-label" for="input-guest-firstname"><?php echo $entry_firstname; ?></label>
                <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-guest-firstname" class="form-control" required autocomplete="given-name" />
            </div>
            <div class="form-group col-sm-6 required">
                <label class="control-label" for="input-guest-lastname"><?php echo $entry_lastname; ?></label>
                <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-guest-lastname" class="form-control" required autocomplete="family-name" />
            </div>
            <div class="form-group col-sm-6 required">
                <label class="control-label" for="input-guest-email"><?php echo $entry_email; ?></label>
                <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-guest-email" class="form-control" required autocomplete="email" />
            </div>
            <div class="form-group col-sm-6 required">
                <label class="control-label" for="input-guest-telephone"><?php echo $entry_telephone; ?></label>
                <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-guest-telephone" class="form-control" required autocomplete="tel" />
            </div>
            <div class="form-group col-sm-6">
                <label class="control-label" for="input-guest-fax"><?php echo $entry_fax; ?></label>
                <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-guest-fax" class="form-control" />
            </div>
            <?php foreach ($custom_fields as $custom_field) { ?>
            <?php if ($custom_field['location'] == 'account') { ?>
            <?php if ($custom_field['type'] == 'select') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label" for="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>" selected="selected"><?php echo $custom_field_value['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'radio') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label"><?php echo $custom_field['name']; ?></label>
                <div id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="radio">
                        <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && $custom_field_value['custom_field_value_id'] == $guest_custom_field[$custom_field['custom_field_id']]) { ?>
                        <label>
                            <input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                            <?php echo $custom_field_value['name']; ?></label>
                        <?php } else { ?>
                        <label>
                            <input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                            <?php echo $custom_field_value['name']; ?></label>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'checkbox') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label"><?php echo $custom_field['name']; ?></label>
                <div id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                    <div class="checkbox">
                        <?php if (isset($guest_custom_field[$custom_field['custom_field_id']]) && in_array($custom_field_value['custom_field_value_id'], $guest_custom_field[$custom_field['custom_field_id']])) { ?>
                        <label>
                            <input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" checked="checked" />
                            <?php echo $custom_field_value['name']; ?></label>
                        <?php } else { ?>
                        <label>
                            <input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                            <?php echo $custom_field_value['name']; ?></label>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'text') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label" for="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'textarea') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label" for="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?></textarea>
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'file') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label"><?php echo $custom_field['name']; ?></label>
                <br />
                <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                <input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : ''); ?>" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'date') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label" for="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="input-group date">
                    <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
      <span class="input-group-btn">
      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
      </span></div>
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'time') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label" for="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="input-group time">
                    <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
      <span class="input-group-btn">
      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
      </span></div>
            </div>
            <?php } ?>
            <?php if ($custom_field['type'] == 'datetime') { ?>
            <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group col-sm-6 custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                <label class="control-label" for="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                <div class="input-group datetime">
                    <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo (isset($guest_custom_field[$custom_field['custom_field_id']]) ? $guest_custom_field[$custom_field['custom_field_id']] : $custom_field['value']); ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-guest-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
      <span class="input-group-btn">
      <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
      </span></div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php } ?>
        </fieldset>
        <div class="buttons col-sm-12">
            <div class="pull-right">
                <input type="button" value="<?php echo $button_continue; ?>" id="button-guest" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
$('#ar-right-1 input').keydown(function(e) {
    if (e.keyCode == 13) {
        $('#button-guest').click();
    }
});
//--></script>

<script type="text/javascript"><!--
// Sort the custom fields
$('#account .form-group[data-sort]').detach().each(function() {
    if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
        $('#account .form-group').eq($(this).attr('data-sort')).before(this);
    }

    if ($(this).attr('data-sort') > $('#account .form-group').length) {
        $('#account .form-group:last').after(this);
    }

    if ($(this).attr('data-sort') < -$('#account .form-group').length) {
        $('#account .form-group:first').before(this);
    }
});

$('#ar-right-1 input[name=\'customer_group_id\']').on('change', function() {
    $.ajax({
        url: 'index.php?route=checkout/checkout/customfield&customer_group_id=' + this.value,
        dataType: 'json',
        success: function(json) {
            $('#ar-right-1 .custom-field').hide();
            $('#ar-right-1 .custom-field').removeClass('required');

            for (i = 0; i < json.length; i++) {
                custom_field = json[i];

                $('#payment-custom-field' + custom_field['custom_field_id']).show();

                if (custom_field['required']) {
                    $('#payment-custom-field' + custom_field['custom_field_id']).addClass('required');
                } else {
                    $('#payment-custom-field' + custom_field['custom_field_id']).removeClass('required');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#ar-right-1 input[name=\'customer_group_id\']:checked').trigger('change');
//--></script>
<script type="text/javascript"><!--
$('#ar-right-1 button[id^=\'button-payment-custom-field\']').on('click', function() {
    var node = this;

    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

    $('#form-upload input[name=\'file\']').trigger('click');

    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }

    timer = setInterval(function() {
        if ($('#form-upload input[name=\'file\']').val() != '') {
            clearInterval(timer);

            $.ajax({
                url: 'index.php?route=tool/upload',
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(node).button('loading');
                },
                complete: function() {
                    $(node).button('reset');
                },
                success: function(json) {
                    $(node).parent().find('.text-danger').remove();

                    if (json['error']) {
                        $(node).parent().find('input[name^=\'custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $(node).parent().find('input[name^=\'custom_field\']').attr('value', json['code']);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});
//--></script>
<script type="text/javascript"><!--
$('.date').datetimepicker({
    pickTime: false
});

$('.time').datetimepicker({
    pickDate: false
});

$('.datetime').datetimepicker({
    pickDate: true,
    pickTime: true
});
//--></script>
