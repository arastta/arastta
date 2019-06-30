<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-product" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <ul class="nav nav-tabs" id="language">
                                    <?php foreach ($languages as $language) { ?>
                                    <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach ($languages as $language) { ?>
                                    <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                        <div class="form-group required">
                                            <label class="col-sm-12" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                                            <div class="col-sm-12">
                                                <input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
                                                <?php if (isset($error_name[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php if (!empty($seo_url[$language['language_id']])) { ?>
                                        <div class="form-group">
                                            <label class="col-sm-12"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                                            <div class="col-sm-12" style="padding-top: 5px;">
                                                <span>
                                                <?php $link = str_replace(basename($preview[$language['language_id']]), '', $preview[$language['language_id']]);
                                                    echo $link; ?><span class="seo-url" data-lang="<?php echo $language['language_id']; ?>"><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
                                                </span>
                                                <div class="pull-right">
                                                    <a href="<?php echo $preview[$language['language_id']]; ?>" data-toggle="tooltip" title="<?php echo $text_preview; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <div class="form-group">
                                            <label class="col-sm-12" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                                            <div class="col-sm-12">
                                                <textarea name="product_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['description'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
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
                            <div class="images">
                                <div class="form-group">
                                    <div class="col-sm-3">
                                        <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail" style="display: block;">
                                            <img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                                    </div>
                                    <div class="col-sm-9">
                                        <input name="file[]" value="" type="file" multiple=true class="file-loading" id="input-image-addon" />
                                        <button type="button" id="image-manage" onclick="BasicImage.manage();" data-toggle="tooltip" title="<?php echo 'Manage'; ?>" class="btn btn-success"><i class="fa fa-folder"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_data; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="data">
                                <fieldset>
                                    <legend><?php echo $entry_pricing; ?></legend>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label class="col-sm-12" for="input-price"><?php echo $entry_price; ?></label>
                                            <div class="col-sm-12">
                                                <div class="input-group price">
                                                    <input type="text" name="price" value="<?php echo $price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control" />
                                                    <select name="tax_class_id" id="input-tax-class" class="form-control">
                                                        <option value="0"><?php echo $text_none; ?></option>
                                                        <?php foreach ($tax_classes as $tax_class) { ?>
                                                        <?php if ($tax_class['tax_class_id'] == $tax_class_id) { ?>
                                                        <option value="<?php echo $tax_class['tax_class_id']; ?>" selected="selected"><?php echo $tax_class['title']; ?></option>
                                                        <?php } else { ?>
                                                        <option value="<?php echo $tax_class['tax_class_id']; ?>"><?php echo $tax_class['title']; ?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12" for="input-quantity"><?php echo $entry_quantity; ?></label>
                                            <div class="col-sm-12">
                                                <input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="data-fieldset">
                                    <legend><?php echo $entry_inventory; ?></legend>
                                    <div class="form-group required">
                                        <div class="col-sm-6">
                                            <label class="col-sm-12" for="input-model"><?php echo $entry_model; ?></label>
                                            <div class="col-sm-12">
                                                <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
                                                <?php if ($error_model) { ?>
                                                <div class="text-danger"><?php echo $error_model; ?></div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
                                            <div class="col-sm-12">
                                                <select name="stock_status_id" id="input-stock-status" class="form-control">
                                                    <?php foreach ($stock_statuses as $stock_status) { ?>
                                                    <?php if ($stock_status['stock_status_id'] == $stock_status_id) { ?>
                                                    <option value="<?php echo $stock_status['stock_status_id']; ?>" selected="selected"><?php echo $stock_status['name']; ?></option>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $stock_status['stock_status_id']; ?>"><?php echo $stock_status['name']; ?></option>
                                                    <?php } ?>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="data-fieldset">
                                    <legend><?php echo $entry_shipping_group; ?></legend>
                                    <div class="form-group">
                                        <div class="col-sm-6">
                                            <label class="col-sm-12" for="input-weight"><?php echo $entry_weight; ?></label>
                                            <div class="col-sm-12">
                                                <div class="input-group price">
                                                    <input type="text" name="weight" value="<?php echo $weight; ?>" placeholder="<?php echo $entry_weight; ?>" id="input-weight" class="form-control" />
                                                    <select name="weight_class_id" id="input-weight-class" class="form-control">
                                                        <?php foreach ($weight_classes as $weight_class) { ?>
                                                        <?php if ($weight_class['weight_class_id'] == $weight_class_id) { ?>
                                                        <option value="<?php echo $weight_class['weight_class_id']; ?>" selected="selected"><?php echo $weight_class['title']; ?></option>
                                                        <?php } else { ?>
                                                        <option value="<?php echo $weight_class['weight_class_id']; ?>"><?php echo $weight_class['title']; ?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="col-sm-12"><?php echo $entry_shipping; ?></label>
                                            <div class="col-sm-12">
                                                <label class="radio-inline">
                                                    <?php if ($shipping) { ?>
                                                    <input type="radio" name="shipping" value="1" checked="checked" />
                                                    <?php echo $text_yes; ?>
                                                    <?php } else { ?>
                                                    <input type="radio" name="shipping" value="1" />
                                                    <?php echo $text_yes; ?>
                                                    <?php } ?>
                                                </label>
                                                <label class="radio-inline">
                                                    <?php if (!$shipping) { ?>
                                                    <input type="radio" name="shipping" value="0" checked="checked" />
                                                    <?php echo $text_no; ?>
                                                    <?php } else { ?>
                                                    <input type="radio" name="shipping" value="0" />
                                                    <?php echo $text_no; ?>
                                                    <?php } ?>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
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
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-date-available"><?php echo $entry_date; ?></label>
                                    <div class="col-sm-12">
                                        <div class="input-group date">
                                            <input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><?php echo $tab_links; ?></h3>
                            <div class="pull-right">
                                <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="links">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-manufacturer">
                                        <div class="view-all pull-right">
                                            <a href="<?php echo $show_all[$language['language_id']]['manufacturer']; ?>" class="popup"><?php echo $entry_view_all; ?></a>
                                        </div>
                                        <span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span>
                                    </label>
                                    <div class="col-sm-12">
                                        <input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control input-full-width" />
                                        <input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-category">
                                        <div class="view-all pull-right">
                                            <a href="<?php echo $show_all[$language['language_id']]['category']; ?>" class="popup"><?php echo $entry_view_all; ?></a>
                                        </div>
                                        <span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span>
                                    </label>
                                    <div class="col-sm-12">
                                        <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control input-full-width" style="margin-bottom: 5px !important;"/>
                                        <?php if (!empty($product_categories)) { ?>
                                        <div id="product-category" class="well well-sm" style="overflow: auto;">
                                            <?php foreach ($product_categories as $product_category) { ?>
                                            <div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
                                                <input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-tag">
                                        <span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span>
                                    </label>
                                    <div class="col-sm-12">
                                        <ul class="nav nav-tabs" id="tag-language">
                                            <?php foreach ($languages as $language) { ?>
                                            <li><a href="#tag-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                            <?php } ?>
                                        </ul>
                                        <div class="tab-content">
                                            <?php foreach ($languages as $language) { ?>
                                            <div class="tab-pane" id="tag-language<?php echo $language['language_id']; ?>">
                                                <div class="view-all pull-right">
                                                    <b><a href="<?php echo $show_all[$language['language_id']]['tags']; ?>" class="popup"><?php echo $entry_view_all; ?></a></b>
                                                </div>
                                                <input type="text" name="tag" value="" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" data-lang="<?php echo $language['language_id']; ?>" class="form-control input-full-width" style="margin-bottom: 5px !important;" />
                                                <?php if (!empty($product_description[$language['language_id']]['tag'])) { ?>
                                                <div id="product-tag-<?php echo $language['language_id']; ?>" class="well well-sm" style="overflow: auto; clear: both;">
                                                    <?php foreach ($product_description[$language['language_id']]['tag'] as $tag_key => $tag_value) { ?>
                                                    <div id="product-tag<?php echo $tag_key; ?>"><i class="fa fa-minus-circle"></i> <?php echo $tag_value; ?>
                                                        <input type="hidden" name="product_tag[<?php echo $language['language_id']; ?>][]" value="<?php echo $tag_value; ?>" />
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
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
                            <div class="options">
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-option"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_option; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="option" value="" placeholder="<?php echo $entry_option; ?>" id="input-option" class="form-control input-full-width" />
                                    </div>
                                </div>
                                <div class="form-group <?php if (empty($product_options)) { ?>hidden <?php } ?>">
                                    <div class="col-sm-12">
                                        <ul class="nav nav-pills nav-stacked" id="option">
                                            <?php $option_row = 0; ?>
                                            <?php foreach ($product_options as $product_option) { ?>
                                            <li><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
                                            <?php $option_row++; ?>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="col-sm-12">
                                        <div class="tab-content" style="padding-top: 0px !important;">
                                            <?php $option_row = 0; ?>
                                            <?php $option_value_row = 0; ?>
                                            <?php foreach ($product_options as $product_option) { ?>
                                            <div class="tab-pane" id="tab-option<?php echo $option_row; ?>">
                                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_id]" value="<?php echo $product_option['product_option_id']; ?>" />
                                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][name]" value="<?php echo $product_option['name']; ?>" />
                                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][option_id]" value="<?php echo $product_option['option_id']; ?>" />
                                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][type]" value="<?php echo $product_option['type']; ?>" />
                                                <div class="hidden">
                                                    <label class="col-sm-12" for="input-required<?php echo $option_row; ?>"><?php echo $entry_required; ?></label>
                                                    <div class="col-sm-10">
                                                        <select name="product_option[<?php echo $option_row; ?>][required]" id="input-required<?php echo $option_row; ?>" class="form-control">
                                                            <?php if ($product_option['required']) { ?>
                                                            <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                                            <option value="0"><?php echo $text_no; ?></option>
                                                            <?php } else { ?>
                                                            <option value="1"><?php echo $text_yes; ?></option>
                                                            <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php if ($product_option['type'] == 'text') { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php if ($product_option['type'] == 'textarea') { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                                                    <div class="col-sm-8">
                                                        <textarea name="product_option[<?php echo $option_row; ?>][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width"><?php echo $product_option['value']; ?></textarea>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php if ($product_option['type'] == 'file') { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                                                    <div class="col-sm-8">
                                                        <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php if ($product_option['type'] == 'date') { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group date">
                                                            <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
                                                        <span class="input-group-btn">
                                                        <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                                        </span></div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php if ($product_option['type'] == 'time') { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group time">
                                                            <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
                                                        <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                        </span></div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php if ($product_option['type'] == 'datetime') { ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
                                                    <div class="col-sm-8">
                                                        <div class="input-group datetime">
                                                            <input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
                                                        <span class="input-group-btn">
                                                        <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                                                        </span></div>
                                                    </div>
                                                </div>
                                                <?php } ?>
                                                <?php if ($product_option['type'] == 'select' || $product_option['type'] == 'radio' || $product_option['type'] == 'checkbox' || $product_option['type'] == 'image') { ?>
                                                <div class="table-responsive">
                                                    <table id="option-value<?php echo $option_row; ?>" class="table table-striped table-hover">
                                                        <thead>
                                                        <tr>
                                                            <td class="text-left"><?php echo $entry_option_value; ?></td>
                                                            <td class="text-right"><?php echo $entry_price; ?></td>
                                                            <td></td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($product_option['product_option_value'] as $product_option_value) { ?>
                                                        <tr id="option-value-row<?php echo $option_value_row; ?>">
                                                            <td class="text-left"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][option_value_id]" class="form-control">
                                                                    <?php if (isset($option_values[$product_option['option_id']])) { ?>
                                                                    <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
                                                                    <?php if ($option_value['option_value_id'] == $product_option_value['option_value_id']) { ?>
                                                                    <option value="<?php echo $option_value['option_value_id']; ?>" selected="selected"><?php echo $option_value['name']; ?></option>
                                                                    <?php } else { ?>
                                                                    <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                                                                    <?php } ?>
                                                                    <?php } ?>
                                                                    <?php } ?>
                                                                </select>
                                                                <input type="hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][product_option_value_id]" value="<?php echo $product_option_value['product_option_value_id']; ?>" /></td>
                                                            <td class="text-right hidden"><input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][quantity]" value="<?php echo $product_option_value['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>
                                                            <td class="text-left hidden"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][subtract]" class="form-control">
                                                                    <?php if ($product_option_value['subtract']) { ?>
                                                                    <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                                                                    <option value="0"><?php echo $text_no; ?></option>
                                                                    <?php } else { ?>
                                                                    <option value="1"><?php echo $text_yes; ?></option>
                                                                    <option value="0" selected="selected"><?php echo $text_no; ?></option>
                                                                    <?php } ?>
                                                                </select></td>
                                                            <td class="text-right">
                                                                <div class="input-group price">
                                                                    <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price]" value="<?php echo $product_option_value['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" />
                                                                    <select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][price_prefix]" class="form-control">
                                                                        <?php if ($product_option_value['price_prefix'] == '+') { ?>
                                                                        <option value="+" selected="selected">+</option>
                                                                        <?php } else { ?>
                                                                        <option value="+">+</option>
                                                                        <?php } ?>
                                                                        <?php if ($product_option_value['price_prefix'] == '-') { ?>
                                                                        <option value="-" selected="selected">-</option>
                                                                        <?php } else { ?>
                                                                        <option value="-">-</option>
                                                                        <?php } ?>
                                                                    </select>
                                                                </div>
                                                            </td>
                                                            <td class="text-right hidden"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points_prefix]" class="form-control">
                                                                    <?php if ($product_option_value['points_prefix'] == '+') { ?>
                                                                    <option value="+" selected="selected">+</option>
                                                                    <?php } else { ?>
                                                                    <option value="+">+</option>
                                                                    <?php } ?>
                                                                    <?php if ($product_option_value['points_prefix'] == '-') { ?>
                                                                    <option value="-" selected="selected">-</option>
                                                                    <?php } else { ?>
                                                                    <option value="-">-</option>
                                                                    <?php } ?>
                                                                </select>
                                                                <input type="text" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][points]" value="<?php echo $product_option_value['points']; ?>" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>
                                                            <td class="text-right hidden"><select name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight_prefix]" class="form-control">
                                                                    <?php if ($product_option_value['weight_prefix'] == '+') { ?>
                                                                    <option value="+" selected="selected">+</option>
                                                                    <?php } else { ?>
                                                                    <option value="+">+</option>
                                                                    <?php } ?>
                                                                    <?php if ($product_option_value['weight_prefix'] == '-') { ?>
                                                                    <option value="-" selected="selected">-</option>
                                                                    <?php } else { ?>
                                                                    <option value="-">-</option>
                                                                    <?php } ?>
                                                                </select>
                                                                <input type="text hidden" name="product_option[<?php echo $option_row; ?>][product_option_value][<?php echo $option_value_row; ?>][weight]" value="<?php echo $product_option_value['weight']; ?>" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>
                                                            <td class="text-right"><button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row<?php echo $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>
                                                        </tr>
                                                        <?php $option_value_row++; ?>
                                                        <?php } ?>
                                                        </tbody>
                                                        <tfoot>
                                                        <tr>
                                                            <td colspan="2"></td>
                                                            <td class="text-right"><button type="button" onclick="addOptionValue('<?php echo $option_row; ?>');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>
                                                        </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                                <div class="hidden-option">
                                                    <select id="option-values<?php echo $option_row; ?>" style="display: none;">
                                                        <?php if (isset($option_values[$product_option['option_id']])) { ?>
                                                        <?php foreach ($option_values[$product_option['option_id']] as $option_value) { ?>
                                                        <option value="<?php echo $option_value['option_value_id']; ?>"><?php echo $option_value['name']; ?></option>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php $option_row++; ?>
                                            <?php } ?>
                                        </div>
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
                                <ul class="nav nav-tabs" id="seo-language">
                                    <?php foreach ($languages as $language) { ?>
                                    <li><a href="#seo-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content" style="padding-top:0 !important;">
                                    <?php foreach ($languages as $language) { ?>
                                    <div class="tab-pane" id="seo-language<?php echo $language['language_id']; ?>">
                                        <div class="pull-right">
                                            <button type="button" id="seo-show-<?php echo $language['language_id']; ?>" onclick="editSEO(<?php echo $language['language_id']; ?>);" data-toggle="tooltip" title="<?php echo 'Edit SEO'; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></button>
                                        </div>
                                        <?php if (!empty($seo_url[$language['language_id']])) { ?>
                                        <div id="seo-preview-<?php echo $language['language_id']; ?>" class="form-group">
                                            <div class="col-sm-12 seo-preview-title"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?></div>
                                            <div class="col-sm-12 seo-preview-url">
                                                <?php $link = str_replace(basename($preview[$language['language_id']]), '', $preview[$language['language_id']]);
                                                    echo $link; ?><span id="seo-url-<?php echo $language['language_id']; ?>"><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
                                            </div>
                                            <div class="col-sm-12 seo-preview-description"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></div>
                                        </div>
                                        <?php } else { ?>
                                        <div id="seo-new-product-<?php echo $language['language_id']; ?>">
                                            <?php echo $text_new_product_seo; ?>
                                        </div>
                                        <?php } ?>
                                        <div id="seo-edit-language-<?php echo $language['language_id']; ?>" class="hidden">
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-sm-12" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
                                                </div>
                                            </div>
                                            <?php if (!empty($seo_url[$language['language_id']])) { ?>
                                            <div class="form-group">
                                                <label class="col-sm-12" for="input-seo-url-<?php echo $language['language_id']; ?>"><?php echo $entry_seo_url; ?></label>
                                                <div class="col-sm-12" style="padding-top: 5px;">
                                                    <span>
                                                    <?php $link = str_replace(basename($preview[$language['language_id']]), '', $preview[$language['language_id']]);
                                                        echo $link; ?><span class="seo-url" data-lang="<?php echo $language['language_id']; ?>"><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
                                                    </span>
                                                    <input type="hidden" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url-<?php echo $language['language_id']; ?>" class="form-control" />
                                                </div>
                                            </div>
                                            <?php } else { ?>
                                            <input type="hidden" name="seo_url[<?php echo $language['language_id']; ?>]" value="" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url-<?php echo $language['language_id']; ?>" class="form-control" />
                                            <?php } ?>
                                            <div class="form-group">
                                                <label class="col-sm-12" for="input-meta-description<?php echo $language['language_id']; ?>"><?php echo $entry_meta_description; ?></label>
                                                <div class="col-sm-12">
                                                    <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control input-full-width"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="col-sm-12" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                                <div class="col-sm-12">
                                                    <textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control input-full-width"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="sku" value="<?php echo $sku; ?>" placeholder="<?php echo $entry_sku; ?>" id="input-sku" class="form-control" />
            <input type="hidden" name="upc" value="<?php echo $upc; ?>" placeholder="<?php echo $entry_upc; ?>" id="input-upc" class="form-control" />
            <input type="hidden" name="ean" value="<?php echo $ean; ?>" placeholder="<?php echo $entry_ean; ?>" id="input-ean" class="form-control" />
            <input type="hidden" name="jan" value="<?php echo $jan; ?>" placeholder="<?php echo $entry_jan; ?>" id="input-jan" class="form-control" />
            <input type="hidden" name="isbn" value="<?php echo $isbn; ?>" placeholder="<?php echo $entry_isbn; ?>" id="input-isbn" class="form-control" />
            <input type="hidden" name="mpn" value="<?php echo $mpn; ?>" placeholder="<?php echo $entry_mpn; ?>" id="input-mpn" class="form-control" />
            <input type="hidden" name="location" value="<?php echo $location; ?>" placeholder="<?php echo $entry_location; ?>" id="input-location" class="form-control" />
            <input type="hidden" name="minimum" value="<?php echo $minimum; ?>" placeholder="<?php echo $entry_minimum; ?>" id="input-minimum" class="form-control" />
            <input type="hidden" name="maximum" value="<?php echo $maximum; ?>" placeholder="<?php echo $entry_maximum; ?>" id="input-maximum" class="form-control" />
            <select name="subtract" id="input-subtract" class="form-control hidden">
                <?php if ($subtract) { ?>
                <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                <option value="0"><?php echo $text_no; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_yes; ?></option>
                <option value="0" selected="selected"><?php echo $text_no; ?></option>
                <?php } ?>
            </select>
            <input type="hidden" name="length" value="<?php echo $length; ?>" placeholder="<?php echo $entry_length; ?>" id="input-length" class="form-control" />
            <input type="hidden" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
            <input type="hidden" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
            <select name="length_class_id" id="input-length-class" class="form-control hidden">
                <?php foreach ($length_classes as $length_class) { ?>
                <?php if ($length_class['length_class_id'] == $length_class_id) { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>" selected="selected"><?php echo $length_class['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $length_class['length_class_id']; ?>"><?php echo $length_class['title']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            <input type="hidden" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
            <?php foreach ($product_filters as $product_filter) { ?>
            <input type="hidden" name="product_filter[]" value="<?php echo $product_filter['filter_id']; ?>" />
            <?php } ?>
            <?php if (in_array(0, $product_store)) { ?>
            <input type="hidden" name="product_store[]" value="0" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="product_store[]" value="0" />
            <?php } ?>
            <?php foreach ($stores as $store) { ?>
            <?php if (in_array($store['store_id'], $product_store)) { ?>
            <input type="hidden" name="product_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="product_store[]" value="<?php echo $store['store_id']; ?>" />
            <?php } ?>
            <?php } ?>
            <input type="hidden" name="download" value="" placeholder="<?php echo $entry_download; ?>" id="input-download" class="form-control" />
            <?php foreach ($product_downloads as $product_download) { ?>
            <input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
            <?php } ?>
            <input type="hidden" name="related" value="" placeholder="<?php echo $entry_related; ?>" id="input-related" class="form-control" />
            <?php foreach ($product_relateds as $product_related) { ?>
            <input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
            <?php } ?>
            <?php $attribute_row = 0; ?>
            <?php foreach ($product_attributes as $product_attribute) { ?>
            <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][name]" value="<?php echo $product_attribute['name']; ?>" placeholder="<?php echo $entry_attribute; ?>" class="form-control" />
            <input type="hidden" name="product_attribute[<?php echo $attribute_row; ?>][attribute_id]" value="<?php echo $product_attribute['attribute_id']; ?>" /></td>
            <?php foreach ($languages as $language) { ?>
            <textarea name="product_attribute[<?php echo $attribute_row; ?>][product_attribute_description][<?php echo $language['language_id']; ?>][text]" rows="5" placeholder="<?php echo $entry_text; ?>" class="form-control hidden"><?php echo isset($product_attribute['product_attribute_description'][$language['language_id']]) ? $product_attribute['product_attribute_description'][$language['language_id']]['text'] : ''; ?></textarea>
            <?php } ?>
            <?php $attribute_row++; ?>
            <?php } ?>
            <?php $recurring_row = 0; ?>
            <?php foreach ($product_recurrings as $product_recurring) { ?>
            <select name="product_recurring[<?php echo $recurring_row; ?>][recurring_id]" class="form-control hidden">
                <?php foreach ($recurrings as $recurring) { ?>
                <?php if ($recurring['recurring_id'] == $product_recurring['recurring_id']) { ?>
                <option value="<?php echo $recurring['recurring_id']; ?>" selected="selected"><?php echo $recurring['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <select name="product_recurring[<?php echo $recurring_row; ?>][customer_group_id]" class="form-control hidden">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $product_recurring['customer_group_id']) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php $recurring_row++; ?>
            <?php } ?>
            <?php $discount_row = 0; ?>
            <?php foreach ($product_discounts as $product_discount) { ?>
            <select name="product_discount[<?php echo $discount_row; ?>][customer_group_id]" class="form-control hidden">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $product_discount['customer_group_id']) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <input type="hidden" name="product_discount[<?php echo $discount_row; ?>][quantity]" value="<?php echo $product_discount['quantity']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" />
            <input type="hidden" name="product_discount[<?php echo $discount_row; ?>][priority]" value="<?php echo $product_discount['priority']; ?>" placeholder="<?php echo $entry_priority; ?>" class="form-control" />
            <input type="hidden" name="product_discount[<?php echo $discount_row; ?>][price]" value="<?php echo $product_discount['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" />
            <input type="hidden" name="product_discount[<?php echo $discount_row; ?>][date_start]" value="<?php echo $product_discount['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
            <input type="hidden" name="product_discount[<?php echo $discount_row; ?>][date_end]" value="<?php echo $product_discount['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
            <?php $discount_row++; ?>
            <?php } ?>
            <?php $special_row = 0; ?>
            <?php foreach ($product_specials as $product_special) { ?>
            <select name="product_special[<?php echo $special_row; ?>][customer_group_id]" class="form-control hidden">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $product_special['customer_group_id']) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <input type="hidden" name="product_special[<?php echo $special_row; ?>][priority]" value="<?php echo $product_special['priority']; ?>" placeholder="<?php echo $entry_quantity; ?>" class="form-control" />
            <input type="hidden" name="product_special[<?php echo $special_row; ?>][price]" value="<?php echo $product_special['price']; ?>" placeholder="<?php echo $entry_price; ?>" class="form-control" />
            <input type="hidden" name="product_special[<?php echo $special_row; ?>][date_start]" value="<?php echo $product_special['date_start']; ?>" placeholder="<?php echo $entry_date_start; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
            <input type="hidden" name="product_special[<?php echo $special_row; ?>][date_end]" value="<?php echo $product_special['date_end']; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" class="form-control" />
            <?php $special_row++; ?>
            <?php } ?>
            <?php $image_row = 0; ?>
            <?php foreach ($product_images as $product_image) { ?>
            <input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
            <input type="hidden" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
            </tr>
            <?php $image_row++; ?>
            <?php } ?>
            <input type="hidden" name="points" value="<?php echo $points; ?>" placeholder="<?php echo $entry_points; ?>" id="input-points" class="form-control" />
            <?php foreach ($customer_groups as $customer_group) { ?>
            <input type="hidden" name="product_reward[<?php echo $customer_group['customer_group_id']; ?>][points]" value="<?php echo isset($product_reward[$customer_group['customer_group_id']]) ? $product_reward[$customer_group['customer_group_id']]['points'] : ''; ?>" class="form-control" />
            <?php } ?>
            <select name="product_layout[0]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($product_layout[0]) && $product_layout[0] == $layout['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php foreach ($stores as $store) { ?>
            <select name="product_layout[<?php echo $store['store_id']; ?>]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($product_layout[$store['store_id']]) && $product_layout[$store['store_id']] == $layout['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php } ?>
        </form>
    </div>
    <style type="text/css"><!--
        #thumb-image img {
            width: 100% !important;
        }
        .input-group.price .form-control {
            min-width: 80px;
        }
        //--></style>
    <script type="text/javascript"><!--
        $(document).ready(function() {
            <?php foreach ($languages as $language) { ?>
            textEditor('#input-description<?php echo $language["language_id"]; ?>');
            <?php } ?>

            $.fn.editable.defaults.mode = 'inline';

            $('.seo-url').editable({
                url: function (params) {
                    $.ajax({
                        type: 'post',
                        url: 'index.php?route=catalog/product/inline&token=<?php echo $token; ?>&product_id=<?php echo $product_id;?>',
                        data: {seo_url: params.value, language_id : $(this).attr('data-lang')},
                        async: false,
                        success: function(json) {
                            $('#input-seo-url-' +  json['language_id']).val(params.value);
                            $('.seo-url').html(params.value);
                        },
                        error: function (xhr, ajaxOptions, thrownError) {
                            return false;
                        }
                    });
                },
                showbuttons: false,
            });

            $('.seo-url').on('hidden', function(e, reason) {
                if (reason === 'onblur') {
                    language_id = parseInt($(this).data('lang'));

                    if ($('#seo-preview-' + language_id).length) {
                        $('#seo-preview-' + language_id + ' .seo-preview-url #seo-url-' + language_id).html($(this).html());
                    }
                }
            });
        });
        //--></script>
    <script type="text/javascript"><!--
        // Manufacturer
        $('input[name=\'manufacturer\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        if ((typeof(json) != 'undefined' || typeof(json) != 'object') && (json == null || json == '')) {
                            $('.btn-manufacturer-add').remove();
                            $('.tooltip.fade.top.in').removeClass('in');

                            html = '<button type="button" data-toggle="tooltip" title="<?php echo $button_manufacturer_add; ?>" class="btn btn-sm btn-default btn-manufacturer-add" data-original-title="Add New Manufacturer"><i class="fa fa-plus text-success"></i></button>';

                            $('input[name=\'manufacturer\']').after(html);
                        }

                        json.unshift({
                            manufacturer_id: 0,
                            name: '<?php echo $text_none; ?>'
                        });

                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['manufacturer_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('.btn-manufacturer-add').remove();
                $('.tooltip.fade.top.in').removeClass('in');

                $('input[name=\'manufacturer\']').val(item['label']);
                $('input[name=\'manufacturer_id\']').val(item['value']);
            }
        });

        $(document).on('click', '.btn-manufacturer-add', function() {
            $.ajax({
                url: 'index.php?route=catalog/manufacturer/quick&token=<?php echo $token; ?>',
                type: 'post',
                data: {name: $('#input-manufacturer').val(), sort_order: '0', status: '1'},
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        $('.btn-manufacturer-add').remove();
                        $('.tooltip.fade.top.in').removeClass('in');

                        $('input[name=\'manufacturer_id\']').val(json['manufacturer_id']);

                        $('#input-manufacturer').after('<p id="quick-manufacturer-success" class="text-success">' + json['success'] + '</p>').fadeIn(3000);
                    }
                }
            }).done(function() {
                setTimeout(function(){
                    $('#quick-manufacturer-success').remove();
                }, 3000);
            });
        });

        // Tag
        var tag_language = 0;
        $('input[name=\'tag\']').autocomplete({
            'source': function(request, response) {
                tag_language = parseInt($(this).attr('id').replace('input-tag',''));
                var input = this.element;

                $.ajax({
                    url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&tag_name=' +  encodeURIComponent(request),
                    type: 'post',
                    data: { tag_text: $('#product-tag-' + tag_language + ' input').serializeArray() },
                    dataType: 'json',
                    success: function(json) {
                        if (json['new']) {
                            add_tag = true;

                            new_tag = $('input[name=\'tag\']').val();

                            tags = $('#product-tag-' + tag_language + ' input').serializeArray();

                            $.each(tags, function(key, tag) {
                                if (tag.value == new_tag) {
                                    add_tag = false;

                                    $('input[name=\'tag\']').attr({
                                        title                : '<?php echo $error_add_same_tag; ?>',
                                        'data-original-title': '<?php echo $error_add_same_tag; ?>',
                                        'data-toggle'        : 'tooltip',
                                        'data-placement'     : 'bottom',
                                    }).tooltip('show').addClass('tag-error');

                                    setTimeout(function(){
                                        $('input[name=\'tag\']').removeAttr('title data-original-title data-toggle data-placement');
                                    }, 5000);

                                    return false;
                                }
                            });

                            if (add_tag) {
                                response($.map(json['new'], function (item) {
                                    return {
                                        label: '<i class="fa fa-plus-circle"></i> &nbsp;<?php echo $button_add; ?> <b>' + item['tag'] + '</b>',
                                        value: item['tag_id']
                                    }
                                }));
                            }
                        } else {
                            response($.map(json, function(item) {
                                return {
                                    label: item['tag'],
                                    value: item['tag_id']
                                }
                            }));
                        }
                    }
                });
            },
            'select': function(item) {
                if (!$('#product-tag-' + tag_language).length) {
                    $(this).parent().append('<div id="product-tag-' + tag_language + '" class="well well-sm" style="overflow: auto; clear: both;"></div>');
                }

                $('#product-tag-' + tag_language + ' #product-tag' + item['value']).remove();

                $('#product-tag-' + tag_language).append('<div id="product-tag' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['value'] + '<input type="hidden" name="product_tag[' + tag_language  +'][]" value="' + item['value'] + '" /></div>');

                $(this).val('');
            }
        });

        $(document).on('click', 'div[id^=\'product-tag-\'] .fa-minus-circle', function() {
            _parent = $(this).parent().parent();

            $(this).parent().remove();
            
            if (!_parent.find('i').hasClass('fa-minus-circle')) {
                _parent.remove();
            }
        });

        $('input[name=\'tag\']').keypress(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                e.stopImmediatePropagation();

                add_tag = true;

                new_tag = $(this).val();

                $('input[name=\'tag\']').val('');

                $('#product-tag' + new_tag).remove();

                tag_language = parseInt($(this).attr('id').replace('input-tag',''));

                if (!$('#product-tag-' + tag_language).length) {
                    $(this).parent().append('<div id="product-tag-' + tag_language + '" class="well well-sm" style="overflow: auto; clear: both;"></div>');
                }

                tags = $('#product-tag-' + tag_language + ' input').serializeArray();

                $.each(tags, function(key, tag) {
                    if (tag.value == new_tag) {
                        add_tag = false;

                        return false;
                    }
                });

                if (add_tag) {
                    $('#product-tag-' + tag_language).append('<div id="product-tag' + new_tag + '"><i class="fa fa-minus-circle"></i> ' + new_tag + '<input type="hidden" name="product_tag[' + tag_language  +'][]" value="' + new_tag + '" /></div>');
                } else {
                    $('input[name=\'tag\']').attr({
                        title                : '<?php echo $error_add_same_tag; ?>',
                        'data-original-title': '<?php echo $error_add_same_tag; ?>',
                        'data-toggle'        : 'tooltip',
                        'data-placement'     : 'bottom',
                    }).tooltip('show').addClass('tag-error');

                    setTimeout(function(){
                        $('input[name=\'tag\']').removeAttr('title data-original-title data-toggle data-placement');
                    }, 5000);
                }
            }
        });

        // Category
        $('input[name=\'category\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        if ((typeof(json) != 'undefined' || typeof(json) != 'object') && (json == null || json == '')) {
                            $('.btn-category-add').remove();
                            $('.tooltip.fade.top.in').removeClass('in');

                            html = '<a href="javascript:void(0);" data-toggle="tooltip" title="<?php echo $button_category_add; ?>" class="btn btn-sm btn-default btn-category-add" data-original-title="Add New Category"><i class="fa fa-plus text-success"></i></a>';

                            $('input[name=\'category\']').after(html);
                        }

                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['category_id']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                $('input[name=\'category\']').val('');

                if (!$('#product-category').length) {
                    $('input[name=\'category\']').after('<div id="product-category" class="well well-sm" style="overflow: auto;"></div>');
                }

                $('#product-category' + item['value']).remove();

                $('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
            }
        });

        // $('#product-category').delegate('.fa-minus-circle', 'click', function() {
        $(document).on('click', '#product-category .fa-minus-circle', function() {
            $(this).parent().remove();

            if (!$("div[id^='product-category'] i").hasClass('fa-minus-circle')) {
                $('#product-category').remove();
            }
        });

        $(document).on('click', '.btn-category-add', function() {
            $.ajax({
                url: 'index.php?route=catalog/category/quick&token=<?php echo $token; ?>',
                type: 'post',
                data: {name: $('#input-category').val(), sort_order: '0', status: '1', column: '1', parent_id: '0'},
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        $('.btn-category-add').remove();
                        $('.tooltip.fade.top.in').removeClass('in');

                        if (!$('#product-category').length) {
                            $('input[name=\'category\']').after('<div id="product-category" class="well well-sm" style="overflow: auto;"></div>');
                        }

                        html  = '<div id="product-category' + json['category_id'] + '">';
                        html += '    <i class="fa fa-minus-circle"></i> ' + $('#input-category').val();
                        html += '    <input type="hidden" name="product_category[]" value="' + json['category_id'] + '">';
                        html += '</div>';

                        $('#input-category').val('');

                        $('#product-category').append(html);

                        $('#input-category').after('<p id="quick-category-success" class="text-success">' + json['success'] + '</p>').fadeIn(3000);
                    }
                }
            }).done(function() {
                setTimeout(function(){
                    $('#quick-category-success').fadeOut();
                    $('#quick-category-success').remove();
                }, 3000);
            });
        });
        //--></script>
    <script type="text/javascript"><!--
        var option_row = <?php echo $option_row; ?>;

        $('input[name=\'option\']').autocomplete({
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=catalog/option/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                    dataType: 'json',
                    success: function(json) {
                        response($.map(json, function(item) {
                            return {
                                category: item['category'],
                                label: item['name'],
                                value: item['option_id'],
                                type: item['type'],
                                option_value: item['option_value']
                            }
                        }));
                    }
                });
            },
            'select': function(item) {
                html  = '<div class="tab-pane" id="tab-option' + option_row + '">';
                html += '   <input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
                html += '   <input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
                html += '   <input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
                html += '   <input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

                html += '   <div class="hidden">';
                html += '     <label class="col-sm-12" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
                html += '     <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
                html += '         <option value="1"><?php echo $text_yes; ?></option>';
                html += '         <option value="0" selected="selected"><?php echo $text_no; ?></option>';
                html += '     </select></div>';
                html += '   </div>';

                if (item['type'] == 'text') {
                    html += '   <div class="form-group">';
                    html += '     <label class="col-sm-4 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                    html += '     <div class="col-sm-8"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control input-full-width" /></div>';
                    html += '   </div>';
                }

                if (item['type'] == 'textarea') {
                    html += '   <div class="form-group">';
                    html += '     <label class="col-sm-4 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                    html += '     <div class="col-sm-8"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control input-full-width"></textarea></div>';
                    html += '   </div>';
                }

                if (item['type'] == 'file') {
                    html += '   <div class="form-group">';
                    html += '     <label class="col-sm-4 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                    html += '     <div class="col-sm-8"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control input-full-width" /></div>';
                    html += '   </div>';
                }

                if (item['type'] == 'date') {
                    html += '   <div class="form-group">';
                    html += '     <label class="col-sm-4 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                    html += '     <div class="col-sm-8"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control input-full-width" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                    html += '   </div>';
                }

                if (item['type'] == 'time') {
                    html += '   <div class="form-group">';
                    html += '     <label class="col-sm-4 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                    html += '     <div class="col-sm-8"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control input-full-width" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                    html += '   </div>';
                }

                if (item['type'] == 'datetime') {
                    html += '   <div class="form-group">';
                    html += '     <label class="col-sm-4 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                    html += '     <div class="col-sm-8"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control input-full-width" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                    html += '   </div>';
                }

                if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
                    html += '<div class="table-responsive">';
                    html += '  <table id="option-value' + option_row + '" class="table table-striped table-hover">';
                    html += '    <thead>';
                    html += '      <tr>';
                    html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';
                    html += '        <td class="text-right"><?php echo $entry_price; ?></td>';
                    html += '        <td></td>';
                    html += '      </tr>';
                    html += '    </thead>';
                    html += '    <tbody>';
                    html += '    </tbody>';
                    html += '    <tfoot>';
                    html += '      <tr>';
                    html += '        <td colspan="2"></td>';
                    html += '        <td class="text-right"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus-circle"></i></button></td>';
                    html += '      </tr>';
                    html += '    </tfoot>';
                    html += '  </table>';
                    html += '</div>';

                    html += '  <select id="option-values' + option_row + '" style="display: none;">';

                    for (i = 0; i < item['option_value'].length; i++) {
                        html += '  <option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
                    }

                    html += '  </select>';
                    html += '</div>';
                }

                $('.options .tab-content').append(html);

                $('#option').append('<li><a href="#tab-option' + option_row + '" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$(\'a[href=\\\'#tab-option' + option_row + '\\\']\').parent().remove(); $(\'#tab-option' + option_row + '\').remove(); $(\'#option a:first\').tab(\'show\')"></i> ' + item['label'] + '</li>');

                $('#option a[href=\'#tab-option' + option_row + '\']').tab('show');

                $('[data-toggle=\'tooltip\']').tooltip({
                    container: 'body',
                    html: true
                });

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

                $('.options .form-group.hidden').removeClass('hidden');

                option_row++;
            }
        });
        //--></script>
    <script type="text/javascript"><!--
        var option_value_row = <?php echo $option_value_row; ?>;

        function addOptionValue(option_row) {
            html  = '<tr id="option-value-row' + option_value_row + '">';
            html += '  <td class="text-left"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][option_value_id]" class="form-control">';
            html += $('#option-values' + option_row).html();
            html += '  </select><input type="hidden" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][product_option_value_id]" value="" /></td>';
            html += '  <td class="text-right hidden"><input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][quantity]" value="" placeholder="<?php echo $entry_quantity; ?>" class="form-control" /></td>';
            html += '  <td class="text-left hidden"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][subtract]" class="form-control">';
            html += '    <option value="1"><?php echo $text_yes; ?></option>';
            html += '    <option value="0" selected="selected"><?php echo $text_no; ?></option>';
            html += '  </select></td>';
            html += '  <td class="text-right">';
            html += '   <div class="input-group price">';
            html += '     <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" />';
            html += '     <select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="selectpicker">';
            html += '       <option value="+">+</option>';
            html += '       <option value="-">-</option>';
            html += '     </select>';
            html += '   </div>';
            html += '  </td>';
            html += '  <td class="text-right hidden"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points_prefix]" class="form-control">';
            html += '    <option value="+">+</option>';
            html += '    <option value="-">-</option>';
            html += '  </select>';
            html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][points]" value="" placeholder="<?php echo $entry_points; ?>" class="form-control" /></td>';
            html += '  <td class="text-right hidden"><select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight_prefix]" class="form-control">';
            html += '    <option value="+">+</option>';
            html += '    <option value="-">-</option>';
            html += '  </select>';
            html += '  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][weight]" value="" placeholder="<?php echo $entry_weight; ?>" class="form-control" /></td>';
            html += '  <td class="text-right"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
            html += '</tr>';

            $('#option-value' + option_row + ' tbody').append(html);
            $('[rel=tooltip]').tooltip();

            $('#option-value-row' + option_value_row + ' select').selectpicker('refresh');
            $('#option-value-row' + option_value_row + ' .input-group.price .form-control').selectpicker('refresh');
            option_value_row++;
        }
        //--></script>
    <script type="text/javascript"><!--
        var image_row = 0;

        function addImage() {
            html  = '<tr id="image-row' + image_row + '">';
            html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
            html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
            html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="fa fa-minus-circle"></i></button></td>';
            html += '</tr>';

            $('#images tbody').append(html);

            image_row++;
        }
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
        $('#language a:first').tab('show');
        $('#option a:first').tab('show');
        $('#tag-language a:first').tab('show');
        $('#seo-language a:first').tab('show');
        //--></script></div>
    <script type="text/javascript"><!--
        $(document).ready(function() {
            $("#input-image-addon").fileinput({
                language: "<?php echo $language_code; ?>",
                allowedFileExtensions: ["jpg", "png", "gif", "jpeg"],
                uploadUrl: "index.php?route=catalog/product/uploads&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>",
                uploadAsync: true,
                overwriteInitial: false,
                initialPreview: [
                    <?php foreach ($product_images as $product_image) { ?>
                    "<img src='<?php echo $product_image['thumb']; ?>' data-code='<?php echo $product_image['image']; ?>'/>",
                    <?php } ?>
                ],
                initialPreviewConfig: [
                    <?php $image_count = 0; ?>
                    <?php foreach ($product_images as $product_image) { ?>
                    {caption: "<?php echo basename($product_image['image']); ?>", url: "index.php?route=catalog/product/deleteImage&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>&image=<?php echo $product_image['image'] ; ?>", key: <?php echo $image_count++; ?>},
                    <?php } ?>
                ]
            });

            $("#input-image-addon").on("filepredelete", function(jqXHR) {
                var abort = true;

                if (confirm("<?php echo $text_delete_image; ?>")) {
                    abort = false;
                }

                return abort; // you can also send any data/object that you can receive on `filecustomerror` event
            });

            $("#input-image-addon").on('filedeleted', function(event, key) {
                $('input[name=\'product_image[' + key + '][image]\']').remove();
                $('input[name=\'product_image[' + key + '][sort_order]\']').remove();
            });

            BasicImage.init();

            var manage_button = $('#image-manage');
            $('#image-manage').remove();

            $('button.btn.btn-default.fileinput-remove.fileinput-remove-button').before(manage_button);
        });

        var image_row = <?php echo $image_row; ?>;
        var image_sort = <?php echo empty($product_image['sort_order']) ? 0 : $product_image['sort_order']; ?>

        function editSEO(language_id) {
            $('#seo-edit-language-' + language_id).removeClass('hidden');
            $('#seo-show-' + language_id).addClass('hidden');
        }

        $('.seo input, .seo textarea').on('keyup', function() {
            language_id = parseInt($(this).attr('id').replace('input-meta-title', '').replace('input-meta-description', ''));

            if ($('#seo-preview-' + language_id).length) {
                if ($(this).is('input')) {
                    $('#seo-preview-' + language_id + ' .seo-preview-title').html($(this).val());
                } else {
                    $('#seo-preview-' + language_id + ' .seo-preview-description').html($(this).val());
                }
            } else {
                seo_html  = '<div id="seo-preview-' + language_id + '" class="form-group">';

                if ($(this).is('input')) {
                    seo_html += '    <div class="col-sm-12 seo-preview-title">';
                    seo_html +=     $(this).val();
                    seo_html += '    </div>';
                    /*
                    seo_html += '    <div class="col-sm-12 seo-preview-url">';
                    seo_html += '        ';
                    seo_html += '        <span id="seo-url-' + language_id + '"></span>';
                    seo_html += '     </div>';
                    */
                    seo_html += '     <div class="col-sm-12 seo-preview-description"></div>';
                } else {
                    seo_html += '    <div class="col-sm-12 seo-preview-title"></div>';
                    /*
                    seo_html += '    <div class="col-sm-12 seo-preview-url">';
                    seo_html += '        ';
                    seo_html += '        <span id="seo-url-' + language_id + '"></span>';
                    seo_html += '     </div>';
                    */
                    seo_html += '     <div class="col-sm-12 seo-preview-description">';
                    seo_html +=     $(this).val();
                    seo_html += '    </div>';
                }

                seo_html += '</div>';

                $('#seo-new-product-'+ language_id).before(seo_html);
                $('#seo-new-product-'+ language_id).remove();
            }
        });

        $(document).delegate('.editable-input .form-control.input-sm', 'keyup', function() {
            language_id = parseInt($(this).parent().parent().parent().parent().parent().parent().parent().find('.seo-url').data('lang'));

            if ($('#seo-preview-' + language_id).length) {
                $('#seo-preview-' + language_id + ' .seo-preview-url #seo-url-' + language_id).html($(this).val());
            }
        });
        //--></script>
<?php echo $footer; ?>
<link href="view/javascript/jquery/layout/jquery-ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/jquery/layout/jquery-ui.js" ></script>
<script type="text/javascript" src="view/javascript/jquery/layout/jquery-lockfixed.js" ></script>
<script type="text/javascript" src="view/javascript/jquery/layout/jquery.ui.touch-punch.js" ></script>
<link href="view/javascript/bootstrap3-editable/css/bootstrap-editable.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/bootstrap3-editable/js/bootstrap-editable.js" ></script>
<link href="../system/vendor/kartik-v/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="../system/vendor/kartik-v/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<script src="../system/vendor/kartik-v/bootstrap-fileinput/js/fileinput.min.js"></script>
<script src="../system/vendor/kartik-v/bootstrap-fileinput/js/fileinput_locale_<?php echo $language_code; ?>.js"></script>
