<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-product" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-product" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-product" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
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
										<label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
										<div class="col-sm-10">
											<input type="text" name="product_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
											<?php if (isset($error_name[$language['language_id']])) { ?>
											<div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
											<?php } ?>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
										<div class="col-sm-10" style="padding-top: 5px;">
										    <span>
												<?php $link = str_replace(basename($preview[$language['language_id']]), '', $preview[$language['language_id']]);
													echo $link; ?><span class="seo-url" data-lang="<?php echo $language['language_id']; ?> "><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
										    </span>
										    <input type="hidden" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url-<?php echo $language['language_id']; ?>" class="form-control" />
											<div class="pull-right">
												<a href="<?php echo $preview[$language['language_id']]; ?>" data-toggle="tooltip" title="<?php echo $text_preview; ?>" target="_blank" class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
										<div class="col-sm-10">
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
									<input name="file[]" value="<?php echo $product_image['image']; ?>" type="file" multiple=true class="file-loading" id="input-image-addon" />
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
								<div class="form-group required">
									<label class="col-sm-3 col-md-2 control-label" for="input-price"><?php echo $entry_price; ?></label>
									<div class="col-sm-9 col-md-4">
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
									<label class="col-sm-3 col-md-2 control-label" for="input-quantity"><?php echo $entry_quantity; ?></label>
									<div class="col-sm-9 col-md-4">
										<input type="text" name="quantity" value="<?php echo $quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control" />
									</div>
								</div>
							</fieldset>
							<fieldset class="data-fieldset">
								<legend><?php echo $entry_inventory; ?></legend>
								<div class="form-group required">
									<label class="col-sm-3 col-md-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
									<div class="col-sm-9 col-md-4">
										<input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
										<?php if ($error_model) { ?>
										<div class="text-danger"><?php echo $error_model; ?></div>
										<?php } ?>
									</div>
									<label class="col-sm-3 col-md-2 control-label" for="input-stock-status"><span data-toggle="tooltip" title="<?php echo $help_stock_status; ?>"><?php echo $entry_stock_status; ?></span></label>
									<div class="col-sm-9 col-md-4">
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
							</fieldset>
							<fieldset class="data-fieldset">
								<legend><?php echo $entry_shipping_group; ?></legend>
								<div class="form-group required">
									<label class="col-sm-3 col-md-2 control-label" for="input-weight"><?php echo $entry_weight; ?></label>
									<div class="col-sm-9 col-md-4">
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
									<label class="col-sm-3 col-md-2 control-label"><?php echo $entry_shipping; ?></label>
									<div class="col-sm-9 col-md-4">
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
								<label class="col-sm-3 control-label"><?php echo $text_enabled; ?></label>
								<div class="col-sm-9">
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
								<label class="col-sm-3 control-label" for="input-date-available"><?php echo $entry_date; ?></label>
								<div class="col-sm-9">
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
								<label class="col-sm-3 control-label" for="input-manufacturer"><span data-toggle="tooltip" title="<?php echo $help_manufacturer; ?>"><?php echo $entry_manufacturer; ?></span></label>
								<div class="col-sm-9">
									<input type="text" name="manufacturer" value="<?php echo $manufacturer ?>" placeholder="<?php echo $entry_manufacturer; ?>" id="input-manufacturer" class="form-control input-full-width" />
									<input type="hidden" name="manufacturer_id" value="<?php echo $manufacturer_id; ?>" />
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
								<div class="col-sm-9">
									<input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control input-full-width" style="margin-bottom: 5px !important;"/>
									<div id="product-category" class="well well-sm" style="overflow: auto;">
										<?php foreach ($product_categories as $product_category) { ?>
										<div id="product-category<?php echo $product_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product_category['name']; ?>
											<input type="hidden" name="product_category[]" value="<?php echo $product_category['category_id']; ?>" />
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 control-label" for="input-tag<?php echo $language['language_id']; ?>"><?php echo $entry_tag; ?></label>
								<div class="col-sm-9 tags-select">
									<select id="tags-<?php echo $language['language_id']; ?>" name="product_description[<?php echo $language['language_id']; ?>][tag][]" class="inputbox chzn-done tags-multi-select hidden" size="5" multiple="multiple" style="display: none !important;">
										<?php if (!empty($product_description[$language['language_id']]['tag'])) {
										foreach ($product_description[$language['language_id']]['tag'] as $tag_key => $tag_value) { ?>
										<option data-tag-remove="<?php echo $tag_key; ?>" value="<?php echo $tag_value; ?>" selected="selected"><?php echo $tag_value; ?></option>
										<?php     }
																	} ?>
									</select>
									<div class="form-control">
										<?php if (!empty($product_description[$language['language_id']]['tag'])) {
																	foreach ($product_description[$language['language_id']]['tag'] as $tag_key => $tag_value) { ?>
										<span class="tag-choice"><?php echo $tag_value; ?><a class="tag-choice-close" onclick="removeTag(this);" data-tag-remove-index="<?php echo $tag_key; ?>"><i class="fa fa-times"></i></a></span>
										<?php     }
																  } ?>
										<input type="text" name="tag" value="" style="margin-top: -5px;" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control input-full-width tag-select" />
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
								<label class="col-sm-3 control-label" for="input-option"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_option; ?></span></label>
								<div class="col-sm-9">
									<input type="text" name="option" value="" placeholder="<?php echo $entry_option; ?>" id="input-option" class="form-control input-full-width" />
								</div>
							</div>
							<div class="form-group hidden">
								<div class="col-sm-3">
									<ul class="nav nav-pills nav-stacked" id="option">
										<?php $option_row = 0; ?>
										<?php foreach ($product_options as $product_option) { ?>
										<li><a href="#tab-option<?php echo $option_row; ?>" data-toggle="tab"><i class="fa fa-minus-circle" onclick="$('a[href=\'#tab-option<?php echo $option_row; ?>\']').parent().remove(); $('#tab-option<?php echo $option_row; ?>').remove(); $('#option a:first').tab('show');"></i> <?php echo $product_option['name']; ?></a></li>
										<?php $option_row++; ?>
										<?php } ?>
									</ul>
								</div>
								<div class="col-sm-9">
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
												<label class="col-sm-2 control-label" for="input-required<?php echo $option_row; ?>"><?php echo $entry_required; ?></label>
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
												<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
												<div class="col-sm-10">
													<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
												</div>
											</div>
											<?php } ?>
											<?php if ($product_option['type'] == 'textarea') { ?>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
												<div class="col-sm-10">
													<textarea name="product_option[<?php echo $option_row; ?>][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width"><?php echo $product_option['value']; ?></textarea>
												</div>
											</div>
											<?php } ?>
											<?php if ($product_option['type'] == 'file') { ?>
											<div class="form-group" style="display: none;">
												<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
												<div class="col-sm-10">
													<input type="text" name="product_option[<?php echo $option_row; ?>][value]" value="<?php echo $product_option['value']; ?>" placeholder="<?php echo $entry_option_value; ?>" id="input-value<?php echo $option_row; ?>" class="form-control input-full-width" />
												</div>
											</div>
											<?php } ?>
											<?php if ($product_option['type'] == 'date') { ?>
											<div class="form-group">
												<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
												<div class="col-sm-3">
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
												<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
												<div class="col-sm-10">
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
												<label class="col-sm-2 control-label" for="input-value<?php echo $option_row; ?>"><?php echo $entry_option_value; ?></label>
												<div class="col-sm-10">
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
												<table id="option-value<?php echo $option_row; ?>" class="table table-striped table-bordered table-hover">
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
														<td class="text-right"><button type="button" onclick="$(this).tooltip('destroy');$('#option-value-row<?php echo $option_value_row; ?>').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
													</tr>
													<?php $option_value_row++; ?>
													<?php } ?>
													</tbody>
													<tfoot>
													<tr>
														<td colspan="2"></td>
														<td class="text-left"><button type="button" onclick="addOptionValue('<?php echo $option_row; ?>');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
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
			<?php foreach ($languages as $language) { ?>
				<input type="hidden" name="product_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($product_description[$language['language_id']]['meta_title']) ? $product_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
				<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control hidden"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
				<textarea name="product_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control hidden"><?php echo isset($product_description[$language['language_id']]) ? $product_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
			<?php } ?>
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
			<?php foreach ($product_downloads as $product_download) { ?>
				<input type="hidden" name="product_download[]" value="<?php echo $product_download['download_id']; ?>" />
			<?php } ?>
			<?php foreach ($product_relateds as $product_related) { ?>
				<input type="hidden" name="product_related[]" value="<?php echo $product_related['product_id']; ?>" />
			<?php } ?>
			<?php $image_row = 0; ?>
			<?php foreach ($product_images as $product_image) { ?>
			<input type="hidden" name="product_image[<?php echo $image_row; ?>][image]" value="<?php echo $product_image['image']; ?>" id="input-image<?php echo $image_row; ?>" />
			<input type="hidden" name="product_image[<?php echo $image_row; ?>][sort_order]" value="<?php echo $product_image['sort_order']; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" />
			</tr>
			<?php $image_row++; ?>
			<?php } ?>
		</form>
    </div>
	<style>
		#thumb-image img {
			width: 100% !important;
		}
	</style>
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
					},
                    error: function (xhr, ajaxOptions, thrownError) {
                        return false;
                    }
                })
            },
            showbuttons: false,
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

            $('input[name=\'manufacturer\']').val(item['label']);
            $('input[name=\'manufacturer_id\']').val(item['value']);
        }
    });

    <?php if (!empty($tag_key)) { ?>
        var tag_key = '<?php echo $tag_key; ?>';
    <?php } else  { ?>
        var tag_key = 0;
    <?php } ?>

    // Tag
    $('.tag-select').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&tag_name=' +  encodeURIComponent(request),
                type: 'post',
                data: { tag_text: $(this).parent().parent().find('.tags-multi-select').serializeArray() },
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            label: item['tag'],
                            value: item['tag_id']
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            var tag_value = item['label'];

            $(this).parent().parent().find('.tags-multi-select').append('<option data-tag-remove="' + tag_key + '" value="' + tag_value +'" selected="selected">' + tag_value + '</option>');

            $(this).before('<span class="tag-choice">' + item['label'] + '<a class="tag-choice-close" onclick="removeTag(this);" data-tag-remove-index="' + tag_key + '"><i class="fa fa-times"></i></a></span>');

            $(this).val();
            tag_key = tag_key + 1 ;
        }
    });

    function removeTag(tag) {
        var tag_value = $(tag).data('tag-remove-index');
        $(tag).parent().parent().parent().parent().find('.tags-multi-select option[data-tag-remove="' + tag_value + '"]').remove();
        $(tag).parent().remove();
    }

    $('.tag-select').keypress(function(event) {
        if(event.keyCode == 13){
            event.preventDefault();
            label = $(this).val();
            var add_tag = 1;
            var error = '';

            if (label == '') {
                add_tag = 0;
                error  = '<div class="alert alert-danger tag-error"><i class="fa fa-exclamation-circle"></i>  <?php echo $error_tag_empty; ?>';
                error += '    <button type="button" class="close" data-dismiss="alert">&times;</button>';
                error += '</div>';
            }

            $(this).parent().parent().find( '.tags-multi-select option:selected' ).each(function() {
                if ($(this).val() == label) {
                    add_tag = 0;
                    error  = '<div class="alert alert-danger tag-error"><i class="fa fa-exclamation-circle"></i>  <?php echo $error_tag; ?>';
                    error += '    <button type="button" class="close" data-dismiss="alert">&times;</button>';
                    error += '</div>';
                }
            });

            if (add_tag == 1) {
                $(this).parent().parent().find('.tags-multi-select').append('<option data-tag-remove="' + tag_key + '" value="' + label +'" selected="selected">' + label + '</option>');

                $(this).before('<span class="tag-choice">' + label + '<a class="tag-choice-close" onclick="removeTag(this);" data-tag-remove-index="' + tag_key + '"><i class="fa fa-times"></i></a></span>');

                $(this).val('');

                tag_key = tag_key + 1 ;
            } else {
                $(this).parent().after(error);
                $('.tag-error').delay(5000).fadeOut('slow');
            }
        }
    });

    $(".tags-select").click(function () {
        $(this).children('.form-control').find('.tag-select').focus();
    });

    // Category
    $('input[name=\'category\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
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

            $('#product-category' + item['value']).remove();

            $('#product-category').append('<div id="product-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '" /></div>');
        }
    });

    $('#product-category').delegate('.fa-minus-circle', 'click', function() {
        $(this).parent().remove();
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
            html += '	<input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
            html += '	<input type="hidden" name="product_option[' + option_row + '][name]" value="' + item['label'] + '" />';
            html += '	<input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
            html += '	<input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

            html += '	<div class="hidden">';
            html += '	  <label class="col-sm-2 control-label" for="input-required' + option_row + '"><?php echo $entry_required; ?></label>';
            html += '	  <div class="col-sm-10"><select name="product_option[' + option_row + '][required]" id="input-required' + option_row + '" class="form-control">';
            html += '	      <option value="1"><?php echo $text_yes; ?></option>';
            html += '	      <option value="0" selected="selected"><?php echo $text_no; ?></option>';
            html += '	  </select></div>';
            html += '	</div>';

            if (item['type'] == 'text') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control input-full-width" /></div>';
                html += '	</div>';
            }

            if (item['type'] == 'textarea') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control input-full-width"></textarea></div>';
                html += '	</div>';
            }

            if (item['type'] == 'file') {
                html += '	<div class="form-group" style="display: none;">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" id="input-value' + option_row + '" class="form-control input-full-width" /></div>';
                html += '	</div>';
            }

            if (item['type'] == 'date') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-3"><div class="input-group date"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD" id="input-value' + option_row + '" class="form-control input-full-width" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                html += '	</div>';
            }

            if (item['type'] == 'time') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><div class="input-group time"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="HH:mm" id="input-value' + option_row + '" class="form-control input-full-width" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                html += '	</div>';
            }

            if (item['type'] == 'datetime') {
                html += '	<div class="form-group">';
                html += '	  <label class="col-sm-2 control-label" for="input-value' + option_row + '"><?php echo $entry_option_value; ?></label>';
                html += '	  <div class="col-sm-10"><div class="input-group datetime"><input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="<?php echo $entry_option_value; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-value' + option_row + '" class="form-control input-full-width" /><span class="input-group-btn"><button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button></span></div></div>';
                html += '	</div>';
            }

            if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
                html += '<div class="table-responsive">';
                html += '  <table id="option-value' + option_row + '" class="table table-striped table-hover">';
                html += '  	 <thead>';
                html += '      <tr>';
                html += '        <td class="text-left"><?php echo $entry_option_value; ?></td>';
                html += '        <td class="text-right"><?php echo $entry_price; ?></td>';
                html += '        <td></td>';
                html += '      </tr>';
                html += '  	 </thead>';
                html += '  	 <tbody>';
                html += '    </tbody>';
                html += '    <tfoot>';
                html += '      <tr>';
                html += '        <td colspan="2"></td>';
                html += '        <td class="text-right"><button type="button" onclick="addOptionValue(' + option_row + ');" data-toggle="tooltip" title="<?php echo $button_option_value_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>';
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
		html += '  	<div class="input-group price">';
        html += '  	  <input type="text" name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price]" value="" placeholder="<?php echo $entry_price; ?>" class="form-control" />';
        html += '  	  <select name="product_option[' + option_row + '][product_option_value][' + option_value_row + '][price_prefix]" class="form-control">';
        html += '       <option value="+">+</option>';
        html += '       <option value="-">-</option>';
        html += '     </select>';
        html += '  	</div>';
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
        html += '  <td class="text-right"><button type="button" onclick="$(this).tooltip(\'destroy\');$(\'#option-value-row' + option_value_row + '\').remove();" data-toggle="tooltip" rel="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#option-value' + option_row + ' tbody').append(html);
        $('[rel=tooltip]').tooltip();

        option_value_row++;
    }
    //--></script>
    <script type="text/javascript"><!--
    var image_row = 0;

    function addImage() {
        html  = '<tr id="image-row' + image_row + '">';
        html += '  <td class="text-left"><a href="" id="thumb-image' + image_row + '"data-toggle="image" class="img-thumbnail"><img src="<?php echo $placeholder; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /><input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-image' + image_row + '" /></td>';
        html += '  <td class="text-right"><input type="text" name="product_image[' + image_row + '][sort_order]" value="" placeholder="<?php echo $entry_sort_order; ?>" class="form-control" /></td>';
        html += '  <td class="text-left"><button type="button" onclick="$(\'#image-row' + image_row  + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
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
    //--></script></div>
	<script type="text/javascript">
		$(document).ready(function() {
			$("#input-image-addon").fileinput({
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
				<?php foreach ($product_images as $product_image) { ?>
					{caption: "<?php echo basename($product_image['image']); ?>", url: "index.php?route=catalog/product/deleteImage&token=<?php echo $token; ?>&product_id=<?php echo $product_id; ?>&image=<?php echo $product_image['image'] ; ?>", key: <?php echo $product_image['sort_order']; ?>},
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

			BasicImage.init();
		});
	</script>
<?php echo $footer; ?>
<link href="view/javascript/jquery/layout/jquery-ui.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/jquery/layout/jquery-ui.js" ></script>
<script type="text/javascript" src="view/javascript/jquery/layout/jquery-lockfixed.js" ></script>
<link href="view/javascript/bootstrap3-editable/css/bootstrap-editable.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/bootstrap3-editable/js/bootstrap-editable.js" ></script>
<link href="../system/vendor/kartik-v/bootstrap-fileinput/css/fileinput.min.css" media="all" rel="stylesheet" type="text/css" />
<script src="../system/vendor/kartik-v/bootstrap-fileinput/js/plugins/canvas-to-blob.min.js" type="text/javascript"></script>
<script src="../system/vendor/kartik-v/bootstrap-fileinput/js/fileinput.min.js"></script>
<script src="../system/vendor/kartik-v/bootstrap-fileinput/js/fileinput_locale_<?php echo $language_code; ?>.js"></script>