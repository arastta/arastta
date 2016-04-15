<div class="row">
    <?php if ($shipping_required) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div class="<?php echo $class; ?>">
        <fieldset id="payment-address">
            <legend><?php echo $text_billing_address; ?></legend>
            <?php if ($addresses) { ?>
            <div class="radio">
                <label>
                    <input type="radio" name="payment_address" value="existing" checked="checked" />
                    <?php echo $text_address_existing; ?>
                </label>
            </div>
            <div id="payment-existing">
                <select name="payment_address_id" class="form-control">
                    <?php foreach ($addresses as $address) { ?>
                    <?php if ($address['address_id'] == $payment_address_id) { ?>
                    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="payment_address" value="new" />
                    <?php echo $text_address_new; ?>
                </label>
            </div>
            <br />
            <?php } ?>
            <div id="payment-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
                    <input type="text" name="payment_firstname" value="<?php echo $payment_firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-payment-firstname" class="form-control" required autocomplete="billing given-name" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
                    <input type="text" name="payment_lastname" value="<?php echo $payment_lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-payment-lastname" class="form-control" required autocomplete="billing family-name" />
                </div>
                <div class="form-group col-sm-12">
                    <label class="control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
                    <input type="text" name="payment_company" value="<?php echo $payment_company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-payment-company" class="form-control" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
                    <input type="text" name="payment_address_1" value="<?php echo $payment_address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-payment-address-1" class="form-control" required autocomplete="billing street-address" />
                </div>
                <div class="form-group col-sm-12">
                    <label class="control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
                    <input type="text" name="payment_address_2" value="<?php echo $payment_address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-payment-address-2" class="form-control" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
                    <input type="text" name="payment_city" value="<?php echo $payment_city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-payment-city" class="form-control" required autocomplete="billing locality" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
                    <input type="text" name="payment_postcode" value="<?php echo $payment_postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-payment-postcode" class="form-control" required autocomplete="billing postal-code" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
                    <select name="payment_country_id" id="input-payment-country" class="form-control" required autocomplete="billing country">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($countries as $country) { ?>
                        <?php if ($country['country_id'] == $payment_country_id) { ?>
                        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-payment-zone"><?php echo $entry_zone; ?></label>
                    <select name="payment_zone_id" id="input-payment-zone" class="form-control" required autocomplete="billing region">
                    </select>
                </div>
                <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'address') { ?>
                <?php if ($custom_field['type'] == 'select') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <select name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'radio') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label"><?php echo $custom_field['name']; ?></label>
                    <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'checkbox') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label"><?php echo $custom_field['name']; ?></label>
                    <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'text') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <input type="text" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'textarea') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <textarea name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'file') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label"><?php echo $custom_field['name']; ?></label>
                    <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                    <input type="hidden" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'date') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <div class="input-group date">
                        <input type="text" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <span class="input-group-btn">
                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'time') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <div class="input-group time">
                        <input type="text" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <span class="input-group-btn">
                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'datetime') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <div class="input-group datetime">
                        <input type="text" name="payment_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <span class="input-group-btn">
                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
            </div>
        </fieldset>
    </div>
    <?php if ($shipping_required) { ?>
    <div class="<?php echo $class; ?>">
        <fieldset id="shipping-address">
            <legend><?php echo $text_delivery_address; ?></legend>
            <?php if ($addresses) { ?>
            <div class="radio">
                <label>
                    <input type="radio" name="shipping_address" value="existing" checked="checked" />
                    <?php echo $text_address_existing; ?>
                </label>
            </div>
            <div id="shipping-existing">
                <select name="shipping_address_id" class="form-control">
                    <?php foreach ($addresses as $address) { ?>
                    <?php if ($address['address_id'] == $shipping_address_id) { ?>
                    <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <div class="radio">
                <label>
                    <input type="radio" name="shipping_address" value="new" />
                    <?php echo $text_address_new; ?>
                </label>
            </div>
            <br />
            <?php } ?>
            <div id="shipping-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-firstname"><?php echo $entry_firstname; ?></label>
                    <input type="text" name="shipping_firstname" value="<?php echo $shipping_firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-shipping-firstname" class="form-control" required autocomplete="shipping given-name" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-lastname"><?php echo $entry_lastname; ?></label>
                    <input type="text" name="shipping_lastname" value="<?php echo $shipping_lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-shipping-lastname" class="form-control" required autocomplete="shipping family-name" />
                </div>
                <div class="form-group col-sm-12">
                    <label class="control-label" for="input-shipping-company"><?php echo $entry_company; ?></label>
                    <input type="text" name="shipping_company" value="<?php echo $shipping_company; ?>" placeholder="<?php echo $entry_company; ?>" id="input-shipping-company" class="form-control" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-address-1"><?php echo $entry_address_1; ?></label>
                    <input type="text" name="shipping_address_1" value="<?php echo $shipping_address_1; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-shipping-address-1" class="form-control" required autocomplete="shipping street-address" />
                </div>
                <div class="form-group col-sm-12">
                    <label class="control-label" for="input-shipping-address-2"><?php echo $entry_address_2; ?></label>
                    <input type="text" name="shipping_address_2" value="<?php echo $shipping_address_2; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-shipping-address-2" class="form-control" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-city"><?php echo $entry_city; ?></label>
                    <input type="text" name="shipping_city" value="<?php echo $shipping_city; ?>" placeholder="<?php echo $entry_city; ?>" id="input-shipping-city" class="form-control" required autocomplete="shipping locality" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-postcode"><?php echo $entry_postcode; ?></label>
                    <input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-shipping-postcode" class="form-control" required autocomplete="shipping postal-code" />
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-country"><?php echo $entry_country; ?></label>
                    <select name="shipping_country_id" id="input-shipping-country" class="form-control" required autocomplete="shipping country">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($countries as $country) { ?>
                        <?php if ($country['country_id'] == $shipping_country_id) { ?>
                        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group col-sm-12 required">
                    <label class="control-label" for="input-shipping-zone"><?php echo $entry_zone; ?></label>
                    <select name="shipping_zone_id" id="input-shipping-zone" class="form-control" required autocomplete="shipping region">
                    </select>
                </div>
                <?php foreach ($custom_fields as $custom_field) { ?>
                <?php if ($custom_field['location'] == 'address') { ?>
                <?php if ($custom_field['type'] == 'select') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <select name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'radio') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label"><?php echo $custom_field['name']; ?></label>
                    <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <div class="radio">
                            <label>
                                <input type="radio" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'checkbox') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label"><?php echo $custom_field['name']; ?></label>
                    <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
                                <?php echo $custom_field_value['name']; ?></label>
                        </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'text') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <input type="text" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'textarea') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <textarea name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'file') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label"><?php echo $custom_field['name']; ?></label>
                    <button type="button" id="button-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                    <input type="hidden" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'date') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <div class="input-group date">
                        <input type="text" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <span class="input-group-btn">
                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'time') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <div class="input-group time">
                        <input type="text" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <span class="input-group-btn">
                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <?php } ?>
                <?php if ($custom_field['type'] == 'datetime') { ?>
                <div class="form-group col-sm-12<?php echo ($custom_field['required'] ? ' required' : ''); ?> custom-field" data-sort="<?php echo $custom_field['sort_order']; ?>">
                    <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
                    <div class="input-group datetime">
                        <input type="text" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
                          <span class="input-group-btn">
                          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                          </span>
                    </div>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>
            </div>
        </fieldset>
    </div>
    <?php } ?>
</div>
<?php if ($shipping_required) { ?>
<div class="checkbox">
    <label>
        <input type="checkbox" name="same_address" value="1" checked="checked" />
        <?php echo $entry_shipping; ?>
    </label>
</div>
<?php } ?>
<br/>
<p><strong><?php echo $text_comments; ?></strong></p>
<p><textarea name="comment" rows="3" class="form-control"><?php echo $comment; ?></textarea></p>
<div class="buttons clearfix">
    <div class="pull-right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-address" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
    </div>
</div>

<script type="text/javascript"><!--
$('#ar-right-1 input').keydown(function(e) {
    if (e.keyCode == 13) {
        $('#button-address').click();
    }
});
//--></script>

<script type="text/javascript"><!--
$('input[name=\'payment_address\']').on('change', function() {
    if (this.value == 'new') {
        $('#payment-existing').hide();
        $('#payment-new').show();
    } else {
        $('#payment-existing').show();
        $('#payment-new').hide();
    }
});

<?php if ($shipping_required) { ?>
    $('input[name=\'shipping_address\']').on('change', function() {
        if (this.value == 'new') {
            $('#shipping-existing').hide();
            $('#shipping-new').show();
        } else {
            $('#shipping-existing').show();
            $('#shipping-new').hide();
        }
    });

    $('input[name=\'same_address\']').on('change', function() {
        var same_address = $(this).is(':checked');

        if (same_address) {
            $('#payment-address legend').html('<?php echo $text_billing_delivery_address; ?>');

            $('#payment-address').parent().removeClass('col-sm-6');
            $('#payment-address').parent().addClass('col-sm-12');
            $('#shipping-address').parent().removeClass('col-sm-6');
            $('#shipping-address').parent().hide();
        }
        else {
            $('#payment-address legend').html('<?php echo $text_billing_address; ?>');

            $('#payment-address').parent().removeClass('col-sm-12');
            $('#payment-address').parent().addClass('col-sm-6');
            $('#shipping-address').parent().addClass('col-sm-6');
            $('#shipping-address').parent().show();

            $('#shipping-address select[name=\'shipping_country_id\']').trigger('change');
        }
    });

    $('input[name=\'same_address\']').trigger('change');

    $('#shipping-address select[name=\'shipping_address_id\']').on('change', function() {
        if (!$('input[name=\'same_address\']').is(':checked')) {
            loadShippingMethodsByAddressId(this.value);
        }
    });

    <?php } ?>
//--></script>

<script type="text/javascript"><!--
$('#payment-address select[name=\'payment_address_id\']').on('change', function() {
    <?php if ($shipping_required) { ?>
        if ($('input[name=\'same_address\']').is(':checked')) {
        loadShippingMethodsByAddressId(this.value);
    }
        <?php } else { ?>
        loadTotalsByAddressId(this.value);
        <?php } ?>
    });

$('#payment-address select[name=\'payment_address_id\']').trigger('change');

$('select[name=\'payment_zone_id\']').on('change', function() {
    <?php if ($shipping_required) { ?>
        if ($('input[name=\'same_address\']').is(':checked')) {
        reloadShippingMethods();
    }
        <?php } else { ?>
        reloadTotals();
        <?php } ?>
    });
//--></script>

<script type="text/javascript"><!--
$('#payment-address .form-group[data-sort]').detach().each(function() {
    if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#payment-address .form-group').length) {
        $('#payment-address .form-group').eq($(this).attr('data-sort')).before(this);
    }

    if ($(this).attr('data-sort') > $('#payment-address .form-group').length) {
        $('#payment-address .form-group:last').after(this);
    }

    if ($(this).attr('data-sort') < -$('#payment-address .form-group').length) {
        $('#payment-address .form-group:first').before(this);
    }
});

<?php if ($shipping_required) { ?>
    $('#shipping-address .form-group[data-sort]').detach().each(function() {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#shipping-address .form-group').length) {
            $('#shipping-address .form-group').eq($(this).attr('data-sort')).before(this);
        }

        if ($(this).attr('data-sort') > $('#shipping-address .form-group').length) {
            $('#shipping-address .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('#shipping-address .form-group').length) {
            $('#shipping-address .form-group:first').before(this);
        }
    });
    <?php } ?>
//--></script>

<script type="text/javascript"><!--
$('#payment-address button[id^=\'button-payment-custom-field\']').on('click', function() {
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
                        $(node).parent().find('input[name^=\'payment_custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $(node).parent().find('input[name^=\'payment_custom_field\']').attr('value', json['code']);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});

<?php if ($shipping_required) { ?>
    $('#shipping-address-address button[id^=\'button-shipping-custom-field\']').on('click', function() {
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
                            $(node).parent().find('input[name^=\'shipping_custom_field\']').after('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $(node).parent().find('input[name^=\'shipping_custom_field\']').attr('value', json['code']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
    <?php } ?>
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

<script type="text/javascript"><!--
$('#payment-address select[name=\'payment_country_id\']').on('change', function() {
    $.ajax({
        url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $('#payment-address select[name=\'payment_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
        },
        complete: function() {
            $('.fa-spin').remove();
        },
        success: function(json) {
            if (json['postcode_required'] == '1') {
                $('#payment-address input[name=\'payment_postcode\']').parent().addClass('required');
            } else {
                $('#payment-address input[name=\'payment_postcode\']').parent().removeClass('required');
            }

            html = '<option value=""><?php echo $text_select; ?></option>';

            if (json['zone'] && json['zone'] != '') {
                for (i = 0; i < json['zone'].length; i++) {
                    html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                    if (json['zone'][i]['zone_id'] == '<?php echo $payment_zone_id; ?>') {
                        html += ' selected="selected"';
                    }

                    html += '>' + json['zone'][i]['name'] + '</option>';
                }
            } else {
                html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
            }

            $('#payment-address select[name=\'payment_zone_id\']').html(html);

            <?php if ($shipping_required) { ?>
                if ($('input[name=\'same_address\']').is(':checked') && $('select[name=\'payment_zone_id\']').val() != '') {
                reloadShippingMethods();
            }
                <?php } else { ?>
                if ($('select[name=\'payment_zone_id\']').val() != '') {
                reloadTotals();
            }
                <?php } ?>
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
            });
            });

$('#payment-address select[name=\'payment_country_id\']').ready(function() {
    // address available, customer logged in
    var address_id = $('#payment-address select[name=\'payment_address_id\']').val();
    if (address_id != '' && address_id != undefined) {
        return;
    }

    if (this.value != '') {
        $('#payment-address select[name=\'payment_country_id\']').trigger('change');
    }
    else {
        loadTotals();
    }
});

<?php if ($shipping_required) { ?>
    $('#shipping-address select[name=\'shipping_country_id\']').on('change', function() {
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#shipping-address select[name=\'shipping_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-address input[name=\'shipping_postcode\']').parent().addClass('required');
                } else {
                    $('#shipping-address input[name=\'shipping_postcode\']').parent().removeClass('required');
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] && json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $shipping_zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('#shipping-address select[name=\'shipping_zone_id\']').html(html);

                if ($('select[name=\'shipping_zone_id\']').val() != '') {
                    reloadShippingMethods();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'shipping_zone_id\']').on('change', function() {
        reloadShippingMethods();
    });

    function reloadShippingMethods() {
        if ($('input[name=\'same_address\']').is(':checked')) {
            var address_type = 'payment';
            var address_country_id = $('select[name=\'payment_country_id\']').val();
            var address_zone_id = $('select[name=\'payment_zone_id\']').val();
            var address_city = $('input[name=\'payment_city\']').val();
            var address_postcode = $('input[name=\'payment_postcode\']').val();
        }
        else {
            var address_type = 'shipping';
            var address_country_id = $('select[name=\'shipping_country_id\']').val();
            var address_zone_id = $('select[name=\'shipping_zone_id\']').val();
            var address_city = $('input[name=\'shipping_city\']').val();
            var address_postcode = $('input[name=\'shipping_postcode\']').val();
        }

        if (address_country_id == undefined) {
            address_country_id = 0;
        }

        if (address_zone_id == undefined) {
            address_zone_id = 0;
        }

        if (address_city == undefined) {
            address_city = '';
        }

        if (address_postcode == undefined) {
            address_postcode = '';
        }

        loadShippingMethodsByAddressFields(address_type, address_country_id, address_zone_id, address_city, address_postcode);
    }
    <?php } ?>

function reloadTotals() {
    var address_type = 'payment';
    var address_country_id = $('select[name=\'payment_country_id\']').val();
    var address_zone_id = $('select[name=\'payment_zone_id\']').val();
    var address_city = $('input[name=\'payment_city\']').val();
    var address_postcode = $('input[name=\'payment_postcode\']').val();

    if (address_country_id == undefined) {
        address_country_id = 0;
    }

    if (address_zone_id == undefined) {
        address_zone_id = 0;
    }

    if (address_city == undefined) {
        address_city = '';
    }

    if (address_postcode == undefined) {
        address_postcode = '';
    }

    loadTotalsByAddressFields(address_type, address_country_id, address_zone_id, address_city, address_postcode);
}
//--></script>
