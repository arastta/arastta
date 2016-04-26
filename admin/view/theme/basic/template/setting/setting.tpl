<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
            <div class="row">
                <div class="col-sm-5 left-col">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-name"><?php echo $entry_name; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_name" value="<?php echo $config_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_name) { ?>
                                        <div class="text-danger"><?php echo $error_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-owner"><?php echo $entry_owner; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_owner" value="<?php echo $config_owner; ?>" placeholder="<?php echo $entry_owner; ?>" id="input-owner" class="form-control" />
                                        <?php if ($error_owner) { ?>
                                        <div class="text-danger"><?php echo $error_owner; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-address"><?php echo $entry_address; ?></label>
                                    <div class="col-sm-12">
                                        <textarea name="config_address" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $config_address; ?></textarea>
                                        <?php if ($error_address) { ?>
                                        <div class="text-danger"><?php echo $error_address; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-geocode"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_geocode; ?>"><?php echo $entry_geocode; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_geocode" value="<?php echo $config_geocode; ?>" placeholder="<?php echo $entry_geocode; ?>" id="input-geocode" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-email"><?php echo $entry_email; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_email" value="<?php echo $config_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                        <?php if ($error_email) { ?>
                                        <div class="text-danger"><?php echo $error_email; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-telephone"><?php echo $entry_telephone; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_telephone" value="<?php echo $config_telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
                                        <?php if ($error_telephone) { ?>
                                        <div class="text-danger"><?php echo $error_telephone; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-fax"><?php echo $entry_fax; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_fax" value="<?php echo $config_fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-image"><?php echo $entry_image; ?></label>
                                    <div class="col-sm-12"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_image" value="<?php echo $config_image; ?>" id="input-image" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-open"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_open; ?>"><?php echo $entry_open; ?></span></label>
                                    <div class="col-sm-12">
                                        <textarea name="config_open" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open" class="form-control"><?php echo $config_open; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-comment"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_comment; ?>"><?php echo $entry_comment; ?></span></label>
                                    <div class="col-sm-12">
                                        <textarea name="config_comment" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $config_comment; ?></textarea>
                                    </div>
                                </div>
                                <?php if ($locations) { ?>
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_location; ?>"><?php echo $entry_location; ?></span></label>
                                    <div class="col-sm-12">
                                        <?php foreach ($locations as $location) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($location['location_id'], $config_location)) { ?>
                                                <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" checked="checked" />
                                                <?php echo $location['name']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="config_location[]" value="<?php echo $location['location_id']; ?>" />
                                                <?php echo $location['name']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 right-col">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_local; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="local">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-country"><?php echo $entry_country; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_country_id" id="input-country" class="form-control">
                                            <?php foreach ($countries as $country) { ?>
                                            <?php if ($country['country_id'] == $config_country_id) { ?>
                                            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-zone"><?php echo $entry_zone; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_zone_id" id="input-zone" class="form-control">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-language"><?php echo $entry_language; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_language" id="input-language" class="form-control">
                                            <?php foreach ($languages as $language) { ?>
                                            <?php if ($language['code'] == $config_language) { ?>
                                            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-admin-language"><?php echo $entry_admin_language; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_admin_language" id="input-admin-language" class="form-control">
                                            <?php foreach ($languages as $language) { ?>
                                            <?php if ($language['code'] == $config_admin_language) { ?>
                                            <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-currency"><span data-toggle="tooltip" title="<?php echo $help_currency; ?>"><?php echo $entry_currency; ?></span></label>
                                    <div class="col-sm-12">
                                        <select name="config_currency" id="input-currency" class="form-control">
                                            <?php foreach ($currencies as $currency) { ?>
                                            <?php if ($currency['code'] == $config_currency) { ?>
                                            <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_currency_auto; ?>"><?php echo $entry_currency_auto; ?></span></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_currency_auto) { ?>
                                            <input type="radio" name="config_currency_auto" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_currency_auto" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_currency_auto) { ?>
                                            <input type="radio" name="config_currency_auto" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_currency_auto" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-length-class"><?php echo $entry_length_class; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_length_class_id" id="input-length-class" class="form-control">
                                            <?php foreach ($length_classes as $length_class) { ?>
                                            <?php if ($length_class['length_class_id'] == $config_length_class_id) { ?>
                                            <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-weight-class"><?php echo $entry_weight_class; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_weight_class_id" id="input-weight-class" class="form-control">
                                            <?php foreach ($weight_classes as $weight_class) { ?>
                                            <?php if ($weight_class['weight_class_id'] == $config_weight_class_id) { ?>
                                            <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_design; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="design">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-admin-template"><?php echo $entry_admin_template; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_admin_template" id="input-admin-template" class="form-control">
                                            <?php foreach ($admin_templates as $admin_template) { ?>
                                            <?php if ($admin_template['theme'] == $config_admin_template) { ?>
                                            <option value="<?php echo $admin_template['theme']; ?>" selected="selected"><?php echo $admin_template['text']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $admin_template['theme']; ?>"><?php echo $admin_template['text']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group hidden">
                                    <label class="col-sm-12" for="input-admin-template"><?php echo $entry_admin_template_message; ?></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_admin_template_message) { ?>
                                            <input type="radio" name="config_admin_template_message" value="show" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_admin_template_message" value="show" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_admin_template_message) { ?>
                                            <input type="radio" name="config_admin_template_message" value="hide" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_admin_template_message" value="hide" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-template"><?php echo $entry_template; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_template" id="input-template" class="form-control">
                                            <?php foreach ($templates as $template) { ?>
                                            <?php if ($template == $config_template) { ?>
                                            <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                        <br />
                                        <img src="" alt="" id="template" class="img-thumbnail" /></div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-layout"><?php echo $entry_layout; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_layout_id" id="input-layout" class="form-control">
                                            <?php foreach ($layouts as $layout) { ?>
                                            <?php if ($layout['layout_id'] == $config_layout_id) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_option; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="option">
                                <fieldset>
                                    <legend><?php echo $text_common; ?></legend>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-text-editor"><span data-toggle="tooltip" title="<?php echo $help_text_editor; ?>"><?php echo $entry_text_editor; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_text_editor" id="input-text-editor" class="form-control">
                                                <?php foreach ($editors as $editor) { ?>
                                                <?php  if ($editor['value'] == $config_text_editor) { ?>
                                                <option value="<?php echo $editor['value']; ?>" selected="selected"><?php echo $editor['text']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $editor['value']; ?>"><?php echo $editor['text']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_product; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_product_count; ?>"><?php echo $entry_product_count; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_product_count) { ?>
                                                <input type="radio" name="config_product_count" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_product_count" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_product_count) { ?>
                                                <input type="radio" name="config_product_count" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_product_count" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_product_limit; ?>"><?php echo $entry_product_limit; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_product_limit" value="<?php echo $config_product_limit; ?>" placeholder="<?php echo $entry_product_limit; ?>" id="input-catalog-limit" class="form-control" />
                                            <?php if ($error_product_limit) { ?>
                                            <div class="text-danger"><?php echo $error_product_limit; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-list-description-limit"><span data-toggle="tooltip" title="<?php echo $help_product_description_length; ?>"><?php echo $entry_product_description_length; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_product_description_length" value="<?php echo $config_product_description_length; ?>" placeholder="<?php echo $entry_product_description_length; ?>" id="input-list-description-limit" class="form-control" />
                                            <?php if ($error_product_description_length) { ?>
                                            <div class="text-danger"><?php echo $error_product_description_length; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_limit_admin; ?>"><?php echo $entry_limit_admin; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_limit_admin" value="<?php echo $config_limit_admin; ?>" placeholder="<?php echo $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
                                            <?php if ($error_limit_admin) { ?>
                                            <div class="text-danger"><?php echo $error_limit_admin; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_review; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_review; ?>"><?php echo $entry_review; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_review_status) { ?>
                                                <input type="radio" name="config_review_status" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_review_status" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_review_status) { ?>
                                                <input type="radio" name="config_review_status" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_review_status" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_review_guest; ?>"><?php echo $entry_review_guest; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_review_guest) { ?>
                                                <input type="radio" name="config_review_guest" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_review_guest" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_review_guest) { ?>
                                                <input type="radio" name="config_review_guest" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_review_guest" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_review_mail; ?>"><?php echo $entry_review_mail; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_review_mail) { ?>
                                                <input type="radio" name="config_review_mail" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_review_mail" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_review_mail) { ?>
                                                <input type="radio" name="config_review_mail" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_review_mail" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_voucher; ?></legend>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-voucher-min"><span data-toggle="tooltip" title="<?php echo $help_voucher_min; ?>"><?php echo $entry_voucher_min; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_voucher_min" value="<?php echo $config_voucher_min; ?>" placeholder="<?php echo $entry_voucher_min; ?>" id="input-voucher-min" class="form-control" />
                                            <?php if ($error_voucher_min) { ?>
                                            <div class="text-danger"><?php echo $error_voucher_min; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-voucher-max"><span data-toggle="tooltip" title="<?php echo $help_voucher_max; ?>"><?php echo $entry_voucher_max; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_voucher_max" value="<?php echo $config_voucher_max; ?>" placeholder="<?php echo $entry_voucher_max; ?>" id="input-voucher-max" class="form-control" />
                                            <?php if ($error_voucher_max) { ?>
                                            <div class="text-danger"><?php echo $error_voucher_max; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_tax; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><?php echo $entry_tax; ?></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_tax) { ?>
                                                <input type="radio" name="config_tax" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_tax" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_tax) { ?>
                                                <input type="radio" name="config_tax" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_tax" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-tax-default"><span data-toggle="tooltip" title="<?php echo $help_tax_default; ?>"><?php echo $entry_tax_default; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_tax_default" id="input-tax-default" class="form-control">
                                                <option value=""><?php echo $text_none; ?></option>
                                                <?php  if ($config_tax_default == 'shipping') { ?>
                                                <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                                                <?php } else { ?>
                                                <option value="shipping"><?php echo $text_shipping; ?></option>
                                                <?php } ?>
                                                <?php  if ($config_tax_default == 'payment') { ?>
                                                <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                                                <?php } else { ?>
                                                <option value="payment"><?php echo $text_payment; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-tax-customer"><span data-toggle="tooltip" title="<?php echo $help_tax_customer; ?>"><?php echo $entry_tax_customer; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_tax_customer" id="input-tax-customer" class="form-control">
                                                <option value=""><?php echo $text_none; ?></option>
                                                <?php  if ($config_tax_customer == 'shipping') { ?>
                                                <option value="shipping" selected="selected"><?php echo $text_shipping; ?></option>
                                                <?php } else { ?>
                                                <option value="shipping"><?php echo $text_shipping; ?></option>
                                                <?php } ?>
                                                <?php  if ($config_tax_customer == 'payment') { ?>
                                                <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                                                <?php } else { ?>
                                                <option value="payment"><?php echo $text_payment; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_account; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_customer_online; ?>"><?php echo $entry_customer_online; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_customer_online) { ?>
                                                <input type="radio" name="config_customer_online" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_customer_online" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_customer_online) { ?>
                                                <input type="radio" name="config_customer_online" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_customer_online" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_customer_group_id" id="input-customer-group" class="form-control">
                                                <?php foreach ($customer_groups as $customer_group) { ?>
                                                <?php if ($customer_group['customer_group_id'] == $config_customer_group_id) { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_customer_group_display; ?>"><?php echo $entry_customer_group_display; ?></span></label>
                                        <div class="col-sm-12">
                                            <?php foreach ($customer_groups as $customer_group) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php if (in_array($customer_group['customer_group_id'], $config_customer_group_display)) { ?>
                                                    <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                                                    <?php echo $customer_group['name']; ?>
                                                    <?php } else { ?>
                                                    <input type="checkbox" name="config_customer_group_display[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                                                    <?php echo $customer_group['name']; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                            <?php } ?>
                                            <?php if ($error_customer_group_display) { ?>
                                            <div class="text-danger"><?php echo $error_customer_group_display; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_customer_price; ?>"><?php echo $entry_customer_price; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_customer_price) { ?>
                                                <input type="radio" name="config_customer_price" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_customer_price" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_customer_price) { ?>
                                                <input type="radio" name="config_customer_price" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_customer_price" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-login-attempts"><span data-toggle="tooltip" title="<?php echo $help_login_attempts; ?>"><?php echo $entry_login_attempts; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_login_attempts" value="<?php echo $config_login_attempts; ?>" placeholder="<?php echo $entry_login_attempts; ?>" id="input-login-attempts" class="form-control" />
                                            <?php if ($error_login_attempts) { ?>
                                            <div class="text-danger"><?php echo $error_login_attempts; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-account"><span data-toggle="tooltip" title="<?php echo $help_account; ?>"><?php echo $entry_account; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_account_id" id="input-account" class="form-control">
                                                <option value="0"><?php echo $text_none; ?></option>
                                                <?php foreach ($informations as $information) { ?>
                                                <?php if ($information['information_id'] == $config_account_id) { ?>
                                                <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_account_mail; ?>"><?php echo $entry_account_mail; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_account_mail) { ?>
                                                <input type="radio" name="config_account_mail" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_account_mail" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_account_mail) { ?>
                                                <input type="radio" name="config_account_mail" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_account_mail" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_checkout; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-invoice-prefix"><span data-toggle="tooltip" title="<?php echo $help_invoice_prefix; ?>"><?php echo $entry_invoice_prefix; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_invoice_prefix" value="<?php echo $config_invoice_prefix; ?>" placeholder="<?php echo $entry_invoice_prefix; ?>" id="input-invoice-prefix" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-api"><span data-toggle="tooltip" title="<?php echo $help_api; ?>"><?php echo $entry_api; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_api_id" id="input-api" class="form-control">
                                                <option value="0"><?php echo $text_none; ?></option>
                                                <?php foreach ($apis as $api) { ?>
                                                <?php if ($api['api_id'] == $config_api_id) { ?>
                                                <option value="<?php echo $api['api_id']; ?>" selected="selected"><?php echo $api['username']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $api['api_id']; ?>"><?php echo $api['username']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_cart_weight; ?>"><?php echo $entry_cart_weight; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_cart_weight) { ?>
                                                <input type="radio" name="config_cart_weight" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_cart_weight" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_cart_weight) { ?>
                                                <input type="radio" name="config_cart_weight" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_cart_weight" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_checkout_guest; ?>"><?php echo $entry_checkout_guest; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_checkout_guest) { ?>
                                                <input type="radio" name="config_checkout_guest" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_checkout_guest" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_checkout_guest) { ?>
                                                <input type="radio" name="config_checkout_guest" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_checkout_guest" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-checkout"><span data-toggle="tooltip" title="<?php echo $help_checkout; ?>"><?php echo $entry_checkout; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_checkout_id" id="input-checkout" class="form-control">
                                                <option value="0"><?php echo $text_none; ?></option>
                                                <?php foreach ($informations as $information) { ?>
                                                <?php if ($information['information_id'] == $config_checkout_id) { ?>
                                                <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_order_status_id" id="input-order-status" class="form-control">
                                                <?php foreach ($order_statuses as $order_status) { ?>
                                                <?php if ($order_status['order_status_id'] == $config_order_status_id) { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-process-status"><span data-toggle="tooltip" title="<?php echo $help_processing_status; ?>"><?php echo $entry_processing_status; ?></span></label>
                                        <div class="col-sm-12">
                                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                                                <?php foreach ($order_statuses as $order_status) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array($order_status['order_status_id'], $config_processing_status)) { ?>
                                                        <input type="checkbox" name="config_processing_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                        <?php echo $order_status['name']; ?>
                                                        <?php } else { ?>
                                                        <input type="checkbox" name="config_processing_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                        <?php echo $order_status['name']; ?>
                                                        <?php } ?>
                                                    </label>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($error_processing_status) { ?>
                                            <div class="text-danger"><?php echo $error_processing_status; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-complete-status"><span data-toggle="tooltip" title="<?php echo $help_complete_status; ?>"><?php echo $entry_complete_status; ?></span></label>
                                        <div class="col-sm-12">
                                            <div class="well well-sm" style="height: 150px; overflow: auto;">
                                                <?php foreach ($order_statuses as $order_status) { ?>
                                                <div class="checkbox">
                                                    <label>
                                                        <?php if (in_array($order_status['order_status_id'], $config_complete_status)) { ?>
                                                        <input type="checkbox" name="config_complete_status[]" value="<?php echo $order_status['order_status_id']; ?>" checked="checked" />
                                                        <?php echo $order_status['name']; ?>
                                                        <?php } else { ?>
                                                        <input type="checkbox" name="config_complete_status[]" value="<?php echo $order_status['order_status_id']; ?>" />
                                                        <?php echo $order_status['name']; ?>
                                                        <?php } ?>
                                                    </label>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php if ($error_complete_status) { ?>
                                            <div class="text-danger"><?php echo $error_complete_status; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_order_mail; ?>"><?php echo $entry_order_mail; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_order_mail) { ?>
                                                <input type="radio" name="config_order_mail" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_order_mail" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_order_mail) { ?>
                                                <input type="radio" name="config_order_mail" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_order_mail" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_stock; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_stock_display; ?>"><?php echo $entry_stock_display; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_stock_display) { ?>
                                                <input type="radio" name="config_stock_display" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_stock_display" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_stock_display) { ?>
                                                <input type="radio" name="config_stock_display" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_stock_display" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_stock_warning; ?>"><?php echo $entry_stock_warning; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_stock_warning) { ?>
                                                <input type="radio" name="config_stock_warning" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_stock_warning" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_stock_warning) { ?>
                                                <input type="radio" name="config_stock_warning" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_stock_warning" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_stock_checkout; ?>"><?php echo $entry_stock_checkout; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_stock_checkout) { ?>
                                                <input type="radio" name="config_stock_checkout" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_stock_checkout" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_stock_checkout) { ?>
                                                <input type="radio" name="config_stock_checkout" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_stock_checkout" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_affiliate; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_affiliate_approval; ?>"><?php echo $entry_affiliate_approval; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_affiliate_approval) { ?>
                                                <input type="radio" name="config_affiliate_approval" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_affiliate_approval" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_affiliate_approval) { ?>
                                                <input type="radio" name="config_affiliate_approval" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_affiliate_approval" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_affiliate_auto; ?>"><?php echo $entry_affiliate_auto; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_affiliate_auto) { ?>
                                                <input type="radio" name="config_affiliate_auto" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_affiliate_auto" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_affiliate_auto) { ?>
                                                <input type="radio" name="config_affiliate_auto" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_affiliate_auto" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-affiliate-commission"><span data-toggle="tooltip" title="<?php echo $help_affiliate_commission; ?>"><?php echo $entry_affiliate_commission; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_affiliate_commission" value="<?php echo $config_affiliate_commission; ?>" placeholder="<?php echo $entry_affiliate_commission; ?>" id="input-affiliate-commission" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-affiliate"><span data-toggle="tooltip" title="<?php echo $help_affiliate; ?>"><?php echo $entry_affiliate; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_affiliate_id" id="input-affiliate" class="form-control">
                                                <option value="0"><?php echo $text_none; ?></option>
                                                <?php foreach ($informations as $information) { ?>
                                                <?php if ($information['information_id'] == $config_affiliate_id) { ?>
                                                <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_affiliate_mail; ?>"><?php echo $entry_affiliate_mail; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_affiliate_mail) { ?>
                                                <input type="radio" name="config_affiliate_mail" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_affiliate_mail" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_affiliate_mail) { ?>
                                                <input type="radio" name="config_affiliate_mail" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_affiliate_mail" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_return; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-return"><span data-toggle="tooltip" title="<?php echo $help_return; ?>"><?php echo $entry_return; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_return_id" id="input-return" class="form-control">
                                                <option value="0"><?php echo $text_none; ?></option>
                                                <?php foreach ($informations as $information) { ?>
                                                <?php if ($information['information_id'] == $config_return_id) { ?>
                                                <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-return-status"><span data-toggle="tooltip" title="<?php echo $help_return_status; ?>"><?php echo $entry_return_status; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_return_status_id" id="input-return-status" class="form-control">
                                                <?php foreach ($return_statuses as $return_status) { ?>
                                                <?php if ($return_status['return_status_id'] == $config_return_status_id) { ?>
                                                <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_return_mail; ?>"><?php echo $entry_return_mail; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_return_mail) { ?>
                                                <input type="radio" name="config_return_mail" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_return_mail" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_return_mail) { ?>
                                                <input type="radio" name="config_return_mail" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_return_mail" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_image; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="image">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-logo"><?php echo $entry_logo; ?></label>
                                    <div class="col-sm-12"><a href="" id="thumb-logo" data-toggle="image" class="img-thumbnail"><img src="<?php echo $logo; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_logo" value="<?php echo $config_logo; ?>" id="input-logo" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-icon"><?php echo $entry_icon; ?></label>
                                    <div class="col-sm-12"><a href="" id="thumb-icon" data-toggle="image" class="img-thumbnail"><img src="<?php echo $icon; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="config_icon" value="<?php echo $config_icon; ?>" id="input-icon" />
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_category_width" value="<?php echo $config_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_category_height" value="<?php echo $config_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_category) { ?>
                                        <div class="text-danger"><?php echo $error_image_category; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-thumb-width"><?php echo $entry_image_thumb; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_thumb_width" value="<?php echo $config_image_thumb_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-thumb-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_thumb_height" value="<?php echo $config_image_thumb_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_thumb) { ?>
                                        <div class="text-danger"><?php echo $error_image_thumb; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-popup-width"><?php echo $entry_image_popup; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_popup_width" value="<?php echo $config_image_popup_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-popup-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_popup_height" value="<?php echo $config_image_popup_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_popup) { ?>
                                        <div class="text-danger"><?php echo $error_image_popup; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-product-width"><?php echo $entry_image_product; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_product_width" value="<?php echo $config_image_product_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-product-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_product_height" value="<?php echo $config_image_product_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_product) { ?>
                                        <div class="text-danger"><?php echo $error_image_product; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-additional-width"><?php echo $entry_image_additional; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_additional_width" value="<?php echo $config_image_additional_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-additional-width" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_additional_height" value="<?php echo $config_image_additional_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_additional) { ?>
                                        <div class="text-danger"><?php echo $error_image_additional; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-related"><?php echo $entry_image_related; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_related_width" value="<?php echo $config_image_related_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-related" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_related_height" value="<?php echo $config_image_related_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_related) { ?>
                                        <div class="text-danger"><?php echo $error_image_related; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-compare"><?php echo $entry_image_compare; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_compare_width" value="<?php echo $config_image_compare_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-compare" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_compare_height" value="<?php echo $config_image_compare_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_compare) { ?>
                                        <div class="text-danger"><?php echo $error_image_compare; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-wishlist"><?php echo $entry_image_wishlist; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_wishlist_width" value="<?php echo $config_image_wishlist_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-wishlist" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_wishlist_height" value="<?php echo $config_image_wishlist_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_wishlist) { ?>
                                        <div class="text-danger"><?php echo $error_image_wishlist; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-cart"><?php echo $entry_image_cart; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_cart_width" value="<?php echo $config_image_cart_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-cart" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_cart_height" value="<?php echo $config_image_cart_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_cart) { ?>
                                        <div class="text-danger"><?php echo $error_image_cart; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-image-location"><?php echo $entry_image_location; ?></label>
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_location_width" value="<?php echo $config_image_location_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-location" class="form-control" />
                                            </div>
                                            <div class="col-sm-6">
                                                <input type="text" name="config_image_location_height" value="<?php echo $config_image_location_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                                            </div>
                                        </div>
                                        <?php if ($error_image_location) { ?>
                                        <div class="text-danger"><?php echo $error_image_location; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_mail; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="mail">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-mail-protocol"><span data-toggle="tooltip" title="<?php echo $help_mail_protocol; ?>"><?php echo $entry_mail_protocol; ?></span></label>
                                    <div class="col-sm-12">
                                        <select name="config_mail[protocol]" id="input-mail-protocol" class="form-control">
                                            <?php if ($config_mail_protocol == 'phpmail') { ?>
                                            <option value="phpmail" selected="selected"><?php echo $text_phpmail; ?></option>
                                            <?php } else { ?>
                                            <option value="phpmail"><?php echo $text_phpmail; ?></option>
                                            <?php } ?>
                                            <?php if ($config_mail_protocol == 'sendmail') { ?>
                                            <option value="sendmail" selected="selected"><?php echo $text_sendmail; ?></option>
                                            <?php } else { ?>
                                            <option value="sendmail"><?php echo $text_sendmail; ?></option>
                                            <?php } ?>
                                            <?php if ($config_mail_protocol == 'smtp') { ?>
                                            <option value="smtp" selected="selected"><?php echo $text_smtp; ?></option>
                                            <?php } else { ?>
                                            <option value="smtp"><?php echo $text_smtp; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-mail-sendmail_path"><span data-toggle="tooltip" title="<?php echo $help_mail_sendmail_path; ?>"><?php echo $entry_mail_sendmail_path; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_mail[sendmail_path]" value="<?php echo $config_mail_sendmail_path; ?>" <?php echo ($config_mail_protocol == 'sendmail') ? '' : 'disabled="disabled"' ?> placeholder="<?php echo $entry_mail_sendmail_path; ?>" id="input-mail-sendmail_path" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-smtp-hostname"><?php echo $entry_smtp_hostname; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_mail[smtp_hostname]" value="<?php echo $config_smtp_hostname; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'disabled="disabled"' ?> placeholder="<?php echo $entry_smtp_hostname; ?>" id="input-smtp-hostname" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-smtp-username"><?php echo $entry_smtp_username; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_mail[smtp_username]" value="<?php echo $config_smtp_username; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'disabled="disabled"' ?> placeholder="<?php echo $entry_smtp_username; ?>" id="input-smtp-username" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-smtp-password"><?php echo $entry_smtp_password; ?></label>
                                    <div class="col-sm-12">
                                        <input type="password" name="config_mail[smtp_password]" value="<?php echo $config_smtp_password; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'disabled="disabled"' ?> placeholder="<?php echo $entry_smtp_password; ?>" id="input-smtp-password" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-smtp-port"><?php echo $entry_smtp_port; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_mail[smtp_port]" value="<?php echo $config_smtp_port; ?>" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'disabled="disabled"' ?> placeholder="<?php echo $entry_smtp_port; ?>" id="input-smtp-port" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-smtp-encryption"><span data-toggle="tooltip" title="<?php echo $help_mail_smtp_encryption; ?>"><?php echo $entry_smtp_encryption; ?></span></label>
                                    <div class="col-sm-12">
                                        <select name="config_mail[smtp_encryption]" <?php echo ($config_mail_protocol == 'smtp') ? '' : 'disabled="disabled"' ?> id="input-mail-smtp-encryption" class="form-control">
                                        <?php if ($config_smtp_encryption == 'none') { ?>
                                        <option value="none" selected="selected"><?php echo $text_smtp_encryption_n; ?></option>
                                        <?php } else { ?>
                                        <option value="none"><?php echo $text_smtp_encryption_n; ?></option>
                                        <?php } ?>
                                        <?php if ($config_smtp_encryption == 'ssl') { ?>
                                        <option value="ssl" selected="selected"><?php echo $text_smtp_encryption_s; ?></option>
                                        <?php } else { ?>
                                        <option value="ssl"><?php echo $text_smtp_encryption_s; ?></option>
                                        <?php } ?>
                                        <?php if ($config_smtp_encryption == 'tls') { ?>
                                        <option value="tls" selected="selected"><?php echo $text_smtp_encryption_t; ?></option>
                                        <?php } else { ?>
                                        <option value="tls"><?php echo $text_smtp_encryption_t; ?></option>
                                        <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-alert-email"><span data-toggle="tooltip" title="<?php echo $help_mail_alert; ?>"><?php echo $entry_mail_alert; ?></span></label>
                                    <div class="col-sm-12">
                                        <textarea name="config_mail_alert" rows="5" placeholder="<?php echo $entry_mail_alert; ?>" id="input-alert-email" class="form-control"><?php echo $config_mail_alert; ?></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_seo; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="seo">
                                <fieldset>
                                    <legend><?php echo $text_seo_urls; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <?php if ($config_seo_url) { ?>
                                                <input type="radio" name="config_seo_url" value="1" checked="checked" />
                                                <?php echo $text_yes; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_seo_url" value="1" />
                                                <?php echo $text_yes; ?>
                                                <?php } ?>
                                            </label>
                                            <label class="radio-inline">
                                                <?php if (!$config_seo_url) { ?>
                                                <input type="radio" name="config_seo_url" value="0" checked="checked" />
                                                <?php echo $text_no; ?>
                                                <?php } else { ?>
                                                <input type="radio" name="config_seo_url" value="0" />
                                                <?php echo $text_no; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_rewrite; ?>"><?php echo $entry_seo_rewrite; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_rewrite" value="1" <?php echo ($config_seo_rewrite) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_rewrite" value="0" <?php echo (!$config_seo_rewrite) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_suffix; ?>"><?php echo $entry_seo_suffix; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_suffix" value="1" <?php echo ($config_seo_suffix) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_suffix" value="0" <?php echo (!$config_seo_suffix) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-seo-category"><span data-toggle="tooltip" title="<?php echo $help_seo_category; ?>"><?php echo $entry_seo_category; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_seo_category" id="input-seo-category" class="form-control">
                                                <option value="0" <?php echo ($config_seo_category == '0') ? 'selected="selected"' : ''; ?> ><?php echo $text_no; ?></option>
                                                <option value="last" <?php echo ($config_seo_category == 'last') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_category_last; ?></option>
                                                <option value="all" <?php echo ($config_seo_category == 'all') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_category_all; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_translate; ?>"><?php echo $entry_seo_translate; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_translate" value="1" <?php echo ($config_seo_translate) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_translate" value="0" <?php echo (!$config_seo_translate) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_lang_code; ?>"><?php echo $entry_seo_lang_code; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_lang_code" value="1" <?php echo ($config_seo_lang_code) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_lang_code" value="0" <?php echo (!$config_seo_lang_code) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_canonical; ?>"><?php echo $entry_seo_canonical; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_canonical" value="1" <?php echo ($config_seo_canonical) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_canonical" value="0" <?php echo (!$config_seo_canonical) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-seo-www-red"><span data-toggle="tooltip" title="<?php echo $help_seo_www_red; ?>"><?php echo $entry_seo_www_red; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_seo_www_red" id="input-seo-www-red" class="form-control">
                                                <option value="0" <?php echo ($config_seo_www_red == '0') ? 'selected="selected"' : ''; ?> ><?php echo $text_no; ?></option>
                                                <option value="with" <?php echo ($config_seo_www_red == 'with') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_www_red_with; ?></option>
                                                <option value="non" <?php echo ($config_seo_www_red == 'non') ? 'selected="selected"' : ''; ?> ><?php echo $text_seo_www_red_non; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_nonseo_red; ?>"><?php echo $entry_seo_nonseo_red; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_nonseo_red" value="1" <?php echo ($config_seo_nonseo_red) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_seo_nonseo_red" value="0" <?php echo (!$config_seo_nonseo_red) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_metadata; ?></legend>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_meta_title" value="<?php echo $config_meta_title; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                                            <?php if ($error_meta_title) { ?>
                                            <div class="text-danger"><?php echo $error_meta_title; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-meta-title-add"><span data-toggle="tooltip" title="<?php echo $help_meta_title_add; ?>"><?php echo $entry_meta_title_add; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_meta_title_add" id="input-meta-title-add" class="form-control">
                                                <option value="0" <?php echo ($config_meta_title_add == '0') ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                                <option value="pre" <?php echo ($config_meta_title_add == 'pre') ? 'selected="selected"' : ''; ?>><?php echo $text_meta_title_add_pre; ?></option>
                                                <option value="post" <?php echo ($config_meta_title_add == 'post') ? 'selected="selected"' : ''; ?>><?php echo $text_meta_title_add_post; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo $config_meta_description; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_meta_keyword" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo $config_meta_keyword; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-meta-generator"><span data-toggle="tooltip" title="<?php echo $help_meta_generator; ?>"><?php echo $entry_meta_generator; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_meta_generator" value="<?php echo $config_meta_generator; ?>" placeholder="<?php echo $entry_meta_generator; ?>" id="input-meta-generator" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-meta-googlekey"><span data-toggle="tooltip" title="<?php echo $help_meta_googlekey; ?>"><?php echo $entry_meta_googlekey; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_meta_googlekey" value="<?php echo $config_meta_googlekey; ?>" placeholder="<?php echo $entry_meta_googlekey; ?>" id="input-meta-googlekey" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-meta-alexakey"><span data-toggle="tooltip" title="<?php echo $help_meta_alexakey; ?>"><?php echo $entry_meta_alexakey; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_meta_alexakey" value="<?php echo $config_meta_alexakey; ?>" placeholder="<?php echo $entry_meta_alexakey; ?>" id="input-meta-alexakey" class="form-control" />
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_seo_sitemap; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-sitemap-all"><span data-toggle="tooltip" title="<?php echo $help_sitemap_all; ?>"><?php echo $entry_sitemap_all; ?></span></label>
                                        <div class="col-sm-12" style="margin-top: 9px;">
                                            <a href="<?php echo $config_sitemap_all; ?>" target="_blank"><?php echo $config_sitemap_all; ?></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-sitemap-products"><span data-toggle="tooltip" title="<?php echo $help_sitemap_products; ?>"><?php echo $entry_sitemap_products; ?></span></label>
                                        <div class="col-sm-12" style="margin-top: 9px;">
                                            <a href="<?php echo $config_sitemap_products; ?>" target="_blank"><?php echo $config_sitemap_products; ?></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-sitemap-categories"><span data-toggle="tooltip" title="<?php echo $help_sitemap_categories; ?>"><?php echo $entry_sitemap_categories; ?></span></label>
                                        <div class="col-sm-12" style="margin-top: 9px;">
                                            <a href="<?php echo $config_sitemap_categories; ?>" target="_blank"><?php echo $config_sitemap_categories; ?></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-sitemap-manufacturers"><span data-toggle="tooltip" title="<?php echo $help_sitemap_manufacturers; ?>"><?php echo $entry_sitemap_manufacturers; ?></span></label>
                                        <div class="col-sm-12" style="margin-top: 9px;">
                                            <a href="<?php echo $config_sitemap_manufacturers; ?>" target="_blank"><?php echo $config_sitemap_manufacturers; ?></a>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_google_analytics; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-google-analytics"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_google_analytics); ?>"><?php echo $entry_google_analytics; ?></span></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_google_analytics" rows="5" placeholder="<?php echo $entry_google_analytics; ?>" id="input-google-analytics" class="form-control"><?php echo $config_google_analytics; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-google-analytics-status"><?php echo $entry_status; ?></label>
                                        <div class="col-sm-12">
                                            <select name="config_google_analytics_status" id="input-google-analytics-status" class="form-control">
                                                <?php if ($config_google_analytics_status) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled; ?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_cache; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="cache">
                                <fieldset>
                                    <legend><?php echo $text_common; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-cache-storage"><span data-toggle="tooltip" title="<?php echo $help_cache_storage; ?>"><?php echo $entry_cache_storage; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_cache_storage" id="input-cache-storage" class="form-control">
                                                <option value="apc" <?php echo ($config_cache_storage == 'apc') ? 'selected="selected"' : ''; ?> >Apc</option>
                                                <option value="file" <?php echo ($config_cache_storage == 'file') ? 'selected="selected"' : ''; ?> ><?php echo $text_cache_storage_file; ?></option>
                                                <option value="memcached" <?php echo ($config_cache_storage == 'memcached') ? 'selected="selected"' : ''; ?> >Memcached</option>
                                                <option value="redis" <?php echo ($config_cache_storage == 'redis') ? 'selected="selected"' : ''; ?> >Redis</option>
                                                <option value="wincache" <?php echo ($config_cache_storage == 'wincache') ? 'selected="selected"' : ''; ?> >Wincache</option>
                                                <option value="xcache" <?php echo ($config_cache_storage == 'xcache') ? 'selected="selected"' : ''; ?> >XCache</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="memcache-servers" class="form-group required <?php echo (($config_cache_storage != 'memcache') && ($config_cache_storage != 'memcached')) ? 'hidden' : ''; ?>">
                                        <label class="col-sm-12" for="input-cache-memcache-servers"><span data-toggle="tooltip" title="<?php echo $help_cache_memcache_servers; ?>"><?php echo $entry_cache_memcache_servers; ?></span></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_cache_memcache_servers" rows="3" placeholder="<?php echo $entry_cache_memcache_servers; ?>" id="input-cache-memcache-servers" class="form-control"><?php echo $config_cache_memcache_servers; ?></textarea>
                                            <?php if ($error_cache_memcache_servers) { ?>
                                            <div class="text-danger"><?php echo $error_cache_memcache_servers; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div id="redis-server" class="form-group required <?php echo ($config_cache_storage != 'redis') ? 'hidden' : ''; ?>">
                                        <label class="col-sm-12" for="input-cache-redis-server"><span data-toggle="tooltip" title="<?php echo $help_cache_redis_server; ?>"><?php echo $entry_cache_redis_server; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_cache_redis_server" value="<?php echo $config_cache_redis_server; ?>" placeholder="<?php echo $entry_cache_redis_server; ?>" id="input-cache-redis-server" class="form-control" />
                                            <?php if ($error_cache_redis_server) { ?>
                                            <div class="text-danger"><?php echo $error_cache_redis_server; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group required">
                                        <label class="col-sm-12" for="input-cache-lifetime"><span data-toggle="tooltip" title="<?php echo $help_cache_lifetime; ?>"><?php echo $entry_cache_lifetime; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_cache_lifetime" value="<?php echo $config_cache_lifetime; ?>" placeholder="<?php echo $entry_cache_lifetime; ?>" id="input-cache-lifetime" class="form-control" />
                                            <?php if ($error_cache_lifetime) { ?>
                                            <div class="text-danger"><?php echo $error_cache_lifetime; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_cache_clear; ?>"><?php echo $entry_cache_clear; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_cache_clear" value="1" <?php echo ($config_cache_clear) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_cache_clear" value="0" <?php echo (!$config_cache_clear) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_pagecache; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_pagecache; ?>"><?php echo $entry_pagecache; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_pagecache" value="1" <?php echo ($config_pagecache) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_pagecache" value="0" <?php echo (!$config_pagecache) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-pagecache-exlude"><span data-toggle="tooltip" title="<?php echo $help_pagecache_exclude; ?>"><?php echo $entry_pagecache_exclude; ?></span></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_pagecache_exclude" rows="5" placeholder="<?php echo $entry_pagecache_exclude; ?>" id="input-pagecache-exlude" class="form-control"><?php echo $config_pagecache_exclude; ?></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_cache_clear; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="button-clear"></label>
                                        <div class="col-sm-12">
                                            <button type="button" id="button-clear" class="btn btn-warning"><i class="fa fa-trash-o"></i>&nbsp;&nbsp;<?php echo $text_cache_clear; ?></button>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_security; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="security">
                                <fieldset>
                                    <legend><?php echo $text_common; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_secure; ?>"><?php echo $entry_secure; ?></span></label>
                                        <div class="col-sm-12">
                                            <select name="config_secure" id="input-secure" class="form-control">
                                                <option value="0" <?php echo ($config_secure == '0') ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                                <option value="1" <?php echo ($config_secure == '1') ? 'selected="selected"' : ''; ?>><?php echo $text_secure_checkout; ?></option>
                                                <option value="2" <?php echo ($config_secure == '2') ? 'selected="selected"' : ''; ?>><?php echo $text_secure_catalog; ?></option>
                                                <option value="3" <?php echo ($config_secure == '3') ? 'selected="selected"' : ''; ?>><?php echo $text_secure_all; ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-encryption"><span data-toggle="tooltip" title="<?php echo $help_encryption; ?>"><?php echo $entry_encryption; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_encryption" value="<?php echo $config_encryption; ?>" placeholder="<?php echo $entry_encryption; ?>" id="input-encryption" class="form-control" />
                                            <?php if ($error_encryption) { ?>
                                            <div class="text-danger"><?php echo $error_encryption; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-sec-admin-login"><span data-toggle="tooltip" title="<?php echo $help_sec_admin_login; ?>"><?php echo $entry_sec_admin_login; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_sec_admin_login" value="<?php echo $config_sec_admin_login; ?>" placeholder="<?php echo $entry_sec_admin_login; ?>" id="input-sec-admin-login" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-sec-admin-keyword"><span data-toggle="tooltip" title="<?php echo $help_sec_admin_keyword; ?>"><?php echo $entry_sec_admin_keyword; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_sec_admin_keyword" value="<?php echo $config_sec_admin_keyword; ?>" placeholder="<?php echo $entry_sec_admin_keyword; ?>" id="input-sec-admin-keyword" class="form-control" />
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_firewall; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_sec_lfi; ?>"><?php echo $entry_sec_lfi; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_lfi[]" value="get" <?php echo (in_array('get', $config_sec_lfi)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_get; ?>
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_lfi[]" value="post" <?php echo (in_array('post', $config_sec_lfi)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_post; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_sec_rfi; ?>"><?php echo $entry_sec_rfi; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_rfi[]" value="get" <?php echo (in_array('get', $config_sec_rfi)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_get; ?>
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_rfi[]" value="post" <?php echo (in_array('post', $config_sec_rfi)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_post; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_sec_sql; ?>"><?php echo $entry_sec_sql; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_sql[]" value="get" <?php echo (in_array('get', $config_sec_sql)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_get; ?>
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_sql[]" value="post" <?php echo (in_array('post', $config_sec_sql)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_post; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_sec_xss; ?>"><?php echo $entry_sec_xss; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_xss[]" value="get" <?php echo (in_array('get', $config_sec_xss)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_get; ?>
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="config_sec_xss[]" value="post" <?php echo (in_array('post', $config_sec_xss)) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_sec_post; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_sec_htmlpurifier; ?>"><?php echo $entry_sec_htmlpurifier; ?></span></label>
                                        <div class="col-sm-12">
                                            <label class="radio-inline">
                                                <input type="radio" name="config_sec_htmlpurifier" value="1" <?php echo ($config_sec_htmlpurifier) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_yes; ?>
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="config_sec_htmlpurifier" value="0" <?php echo (!$config_sec_htmlpurifier) ? 'checked="checked"' : ''; ?>/>
                                                <?php echo $text_no; ?>
                                            </label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_upload; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-file-max-size"><span data-toggle="tooltip" title="<?php echo $help_file_max_size; ?>"><?php echo $entry_file_max_size; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_file_max_size" value="<?php echo $config_file_max_size; ?>" placeholder="<?php echo $entry_file_max_size; ?>" id="input-file-max-size" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-file-ext-allowed"><span data-toggle="tooltip" title="<?php echo $help_file_ext_allowed; ?>"><?php echo $entry_file_ext_allowed; ?></span></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_file_ext_allowed" rows="5" placeholder="<?php echo $entry_file_ext_allowed; ?>" id="input-file-ext-allowed" class="form-control"><?php echo $config_file_ext_allowed; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-file-mime-allowed"><span data-toggle="tooltip" title="<?php echo $help_file_mime_allowed; ?>"><?php echo $entry_file_mime_allowed; ?></span></label>
                                        <div class="col-sm-12">
                                            <textarea name="config_file_mime_allowed" cols="60" rows="5" placeholder="<?php echo $entry_file_mime_allowed; ?>" id="input-file-mime-allowed" class="form-control"><?php echo $config_file_mime_allowed; ?></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset>
                                    <legend><?php echo $text_google_captcha; ?></legend>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-google-captcha-public"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_google_captcha); ?>"><?php echo $entry_google_captcha_public; ?></span></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_google_captcha_public" value="<?php echo $config_google_captcha_public; ?>" placeholder="<?php echo $entry_google_captcha_public; ?>" id="input-google-captcha-public" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-google-captcha-secret"><?php echo $entry_google_captcha_secret; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="config_google_captcha_secret" value="<?php echo $config_google_captcha_secret; ?>" placeholder="<?php echo $entry_google_captcha_secret; ?>" id="input-google-captcha-secret" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-12" for="input-google-captcha-status"><?php echo $entry_status; ?></label>
                                        <div class="col-sm-12">
                                            <select name="config_google_captcha_status" id="input-google-captcha-status" class="form-control">
                                                <?php if ($config_google_captcha_status) { ?>
                                                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                                                <option value="0"><?php echo $text_disabled; ?></option>
                                                <?php } else { ?>
                                                <option value="1"><?php echo $text_enabled; ?></option>
                                                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_fraud; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="fraud">
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" data-html="true" data-trigger="click" title="<?php echo htmlspecialchars($help_fraud_detection); ?>"><?php echo $entry_fraud_detection; ?></span></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_fraud_detection) { ?>
                                            <input type="radio" name="config_fraud_detection" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_fraud_detection" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_fraud_detection) { ?>
                                            <input type="radio" name="config_fraud_detection" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_fraud_detection" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-fraud-key"><?php echo $entry_fraud_key; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_fraud_key" value="<?php echo $config_fraud_key; ?>" placeholder="<?php echo $entry_fraud_key; ?>" id="input-fraud-key" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-fraud-score"><span data-toggle="tooltip" title="<?php echo $help_fraud_score; ?>"><?php echo $entry_fraud_score; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_fraud_score" value="<?php echo $config_fraud_score; ?>" placeholder="<?php echo $entry_fraud_score; ?>" id="input-fraud-score" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-fraud-status"><span data-toggle="tooltip" title="<?php echo $help_fraud_status; ?>"><?php echo $entry_fraud_status; ?></span></label>
                                    <div class="col-sm-12">
                                        <select name="config_fraud_status_id" id="input-fraud-status" class="form-control">
                                            <?php foreach ($order_statuses as $order_status) { ?>
                                            <?php if ($order_status['order_status_id'] == $config_fraud_status_id) { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_server; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="server">
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_timezone; ?>"><?php echo $entry_timezone; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_timezone" id="input-timezone" class="form-control" data-live-search="true">
                                            <option value="UTC">UTC</option>
                                            <?php foreach ($timezones as $tz_gname => $tz_gzones) { ?>
                                            <optgroup label="<?php echo $tz_gname; ?>">
                                                <?php foreach ($tz_gzones as $tz_zone => $tz_locale) { ?>
                                                <option value="<?php echo $tz_zone; ?>" <?php echo ($config_timezone == $tz_zone) ? 'selected="selected"' : ''; ?>><?php echo $tz_locale; ?></option>
                                                <?php } ?>
                                            </optgroup>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_shared; ?>"><?php echo $entry_shared; ?></span></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_shared) { ?>
                                            <input type="radio" name="config_shared" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_shared" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_shared) { ?>
                                            <input type="radio" name="config_shared" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_shared" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-robots"><span data-toggle="tooltip" title="<?php echo $help_robots; ?>"><?php echo $entry_robots; ?></span></label>
                                    <div class="col-sm-12">
                                        <textarea name="config_robots" rows="5" placeholder="<?php echo $entry_robots; ?>" id="input-robots" class="form-control"><?php echo $config_robots; ?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_maintenance; ?>"><?php echo $entry_maintenance; ?></span></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_maintenance) { ?>
                                            <input type="radio" name="config_maintenance" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_maintenance" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_maintenance) { ?>
                                            <input type="radio" name="config_maintenance" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_maintenance" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_password; ?>"><?php echo $entry_password; ?></span></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_password) { ?>
                                            <input type="radio" name="config_password" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_password" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_password) { ?>
                                            <input type="radio" name="config_password" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_password" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-compression"><span data-toggle="tooltip" title="<?php echo $help_compression; ?>"><?php echo $entry_compression; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_compression" value="<?php echo $config_compression; ?>" placeholder="<?php echo $entry_compression; ?>" id="input-compression" class="form-control" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_debug_system; ?>"><?php echo $entry_debug_system; ?></span></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <input type="radio" name="config_debug_system" value="1" <?php echo ($config_debug_system) ? 'checked="checked"' : ''; ?>/>
                                            <?php echo $text_yes; ?>
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="config_debug_system" value="0" <?php echo (!$config_debug_system) ? 'checked="checked"' : ''; ?>/>
                                            <?php echo $text_no; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $entry_error_display; ?></label>
                                    <div class="col-sm-12">
                                        <select name="config_error_display" id="input-secure" class="form-control">
                                            <option value="0" <?php echo ($config_error_display == '0') ? 'selected="selected"' : ''; ?>><?php echo $text_no; ?></option>
                                            <option value="1" <?php echo ($config_error_display == '1') ? 'selected="selected"' : ''; ?>><?php echo $text_error_basic; ?></option>
                                            <option value="2" <?php echo ($config_error_display == '2') ? 'selected="selected"' : ''; ?>><?php echo $text_error_advanced; ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $entry_error_log; ?></label>
                                    <div class="col-sm-12">
                                        <label class="radio-inline">
                                            <?php if ($config_error_log) { ?>
                                            <input type="radio" name="config_error_log" value="1" checked="checked" />
                                            <?php echo $text_yes; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_error_log" value="1" />
                                            <?php echo $text_yes; ?>
                                            <?php } ?>
                                        </label>
                                        <label class="radio-inline">
                                            <?php if (!$config_error_log) { ?>
                                            <input type="radio" name="config_error_log" value="0" checked="checked" />
                                            <?php echo $text_no; ?>
                                            <?php } else { ?>
                                            <input type="radio" name="config_error_log" value="0" />
                                            <?php echo $text_no; ?>
                                            <?php } ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-error-filename"><?php echo $entry_error_filename; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="config_error_filename" value="<?php echo $config_error_filename; ?>" placeholder="<?php echo $entry_error_filename; ?>" id="input-error-filename" class="form-control" />
                                        <?php if ($error_error_filename) { ?>
                                        <div class="text-danger"><?php echo $error_error_filename; ?></div>
                                        <?php } ?>
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
    $(document).ready(function() {
        $('.panel-chevron').trigger('click');
    });
    
    $('select[name=\'config_template\']').on('change', function() {
        $.ajax({
            url: 'index.php?route=setting/setting/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value),
            dataType: 'html',
            beforeSend: function() {
                $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(html) {
                $('#template').attr('src', html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'config_template\']').trigger('change');

    $('select[name=\'config_mail[protocol]\']').on('change', function() {
        var configMail = $(this).val();

        if (configMail == 'phpmail') {
            $("input[name=\'config_mail[sendmail_path]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_hostname]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_username]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_password]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_port]\']").prop('disabled', true);
            $("select[name=\'config_mail[smtp_encryption]\']").prop('disabled', true);
        }
        else if(configMail == 'sendmail') {
            $("input[name=\'config_mail[sendmail_path]\']").prop('disabled', false);
            $("input[name=\'config_mail[smtp_hostname]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_username]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_password]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_port]\']").prop('disabled', true);
            $("select[name=\'config_mail[smtp_encryption]\']").prop('disabled', true);
        }
        else if (configMail == 'smtp') {
            $("input[name=\'config_mail[sendmail_path]\']").prop('disabled', true);
            $("input[name=\'config_mail[smtp_hostname]\']").prop('disabled', false);
            $("input[name=\'config_mail[smtp_username]\']").prop('disabled', false);
            $("input[name=\'config_mail[smtp_password]\']").prop('disabled', false);
            $("input[name=\'config_mail[smtp_port]\']").prop('disabled', false);
            $("select[name=\'config_mail[smtp_encryption]\']").prop('disabled', false);
        }

        $('select[name=\'config_mail[smtp_encryption]\']').removeClass('form-control');
        $('select[name=\'config_mail[smtp_encryption]\']').selectpicker('refresh');
    });

    $('select[name=\'config_mail[protocol]\']').trigger('change');
    //--></script>
    <script type="text/javascript"><!--
    $('select[name=\'config_country_id\']').on('change', function() {
        $.ajax({
            url: 'index.php?route=setting/setting/country&token=<?php echo $token; ?>&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'config_country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
            },
            success: function(json) {
                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] && json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $config_zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('select[name=\'config_zone_id\']').html(html);

                $('select[name=\'config_zone_id\']').selectpicker('refresh');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'config_country_id\']').trigger('change');

    $('select[name=\'config_cache_storage\']').on('change', function() {
        var cache_storage = $('select[name=\'config_cache_storage\']').val();

        if ((cache_storage == 'memcache') || (cache_storage == 'memcached')) {
            $("#memcache-servers").removeClass('hidden');
            $("#redis-server").addClass('hidden');
        } else if (cache_storage == 'redis') {
            $("#redis-server").removeClass('hidden');
            $("#memcache-servers").addClass('hidden');
        } else {
            $("#memcache-servers").addClass('hidden');
            $("#redis-server").addClass('hidden');
        }
    });
    //--></script>
</div>
<script type="text/javascript"><!--
$('#button-clear').on('click', function() {
    $.ajax({
        url: 'index.php?route=setting/setting/clearcache&token=<?php echo $token; ?>',
        dataType: 'json',
        beforeSend: function() {
            $('#button-clear').button('loading');
        },
        success: function(json) {
            $('#button-clear').removeClass('btn-warning');

            if (json['message']) {
                $('#button-clear').addClass('btn-success');
                $('#button-clear').html('<i class="fa fa-check-circle"></i>&nbsp;&nbsp;'+json['message']);
            }
            else {
                $('#button-clear').addClass('btn-danger');
                $('#button-clear').html('<i class="fa fa-times-circle"></i>&nbsp;&nbsp;'+json['error']);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
//--></script>
<?php echo $footer; ?>
