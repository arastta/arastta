<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-facebook-store" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-facebook-store" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $help_ssl; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-facebook-store" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-items" data-toggle="tab"><?php echo $tab_items; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                          <div class="form-group required">
                            <label class="col-sm-2 control-label" for="entry-app-id"><?php echo $entry_app_id; ?></label>
                            <div class="col-sm-10">
                              <input type="text" name="facebook_store_app_id" value="<?php echo $app_id; ?>" placeholder="<?php echo $entry_app_id; ?>" id="entry-app-id" class="form-control"/>
                              <?php if ($error_app_id) { ?>
                              <div class="text-danger"><?php echo $error_app_id; ?></div>
                              <?php } ?>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-header-currencies"><?php echo $entry_show_header_currency; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_header_currency) { ?>
                                <input type="radio" name="facebook_store_show_header_currency" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_currency" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_header_currency) { ?>
                                <input type="radio" name="facebook_store_show_header_currency" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_currency" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-header-language"><?php echo $entry_show_header_language; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_header_language) { ?>
                                <input type="radio" name="facebook_store_show_header_language" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_language" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_header_language) { ?>
                                <input type="radio" name="facebook_store_show_header_language" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_language" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-header-category"><?php echo $entry_show_header_category; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_header_category) { ?>
                                <input type="radio" name="facebook_store_show_header_category" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_category" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_header_category) { ?>
                                <input type="radio" name="facebook_store_show_header_category" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_category" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-header-search"><?php echo $entry_show_header_search; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_header_search) { ?>
                                <input type="radio" name="facebook_store_show_header_search" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_search" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_header_search) { ?>
                                <input type="radio" name="facebook_store_show_header_search" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_header_search" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-footer"><?php echo $entry_show_footer; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_footer) { ?>
                                <input type="radio" name="facebook_store_show_footer" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_footer" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_footer) { ?>
                                <input type="radio" name="facebook_store_show_footer" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_footer" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-product-description"><?php echo $entry_show_product_description; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_product_description) { ?>
                                <input type="radio" name="facebook_store_show_product_description" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_product_description" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_product_description) { ?>
                                <input type="radio" name="facebook_store_show_product_description" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_product_description" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-product-price"><?php echo $entry_show_product_price; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_product_price) { ?>
                                <input type="radio" name="facebook_store_show_product_price" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_product_price" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_product_price) { ?>
                                <input type="radio" name="facebook_store_show_product_price" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_product_price" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-product-rating"><?php echo $entry_show_product_rating; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_product_rating) { ?>
                                <input type="radio" name="facebook_store_show_product_rating" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_product_rating" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_product_rating) { ?>
                                <input type="radio" name="facebook_store_show_product_rating" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_product_rating" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-show-addtocart"><?php echo $entry_show_addtocart; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($show_addtocart) { ?>
                                <input type="radio" name="facebook_store_show_addtocart" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_addtocart" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$show_addtocart) { ?>
                                <input type="radio" name="facebook_store_show_addtocart" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_show_addtocart" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                            <div class="col-sm-10">
                              <label class="radio-inline">
                                <?php if ($status) { ?>
                                <input type="radio" name="facebook_store_status" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_status" value="1" />
                                <?php echo $text_yes; ?>
                                <?php } ?>
                              </label>
                              <label class="radio-inline">
                                <?php if (!$status) { ?>
                                <input type="radio" name="facebook_store_status" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                                <?php } else { ?>
                                <input type="radio" name="facebook_store_status" value="0" />
                                <?php echo $text_no; ?>
                                <?php } ?>
                              </label>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-sm-2 control-label" for="input-data-feed"><span data-toggle="tooltip" title="<?php echo $help_data_feed; ?>"><?php echo $entry_data_feed; ?></span></label>
                            <div class="col-sm-10">
                              <textarea rows="5" readonly="readonly" id="input-data-feed" class="form-control"><?php echo $data_feed; ?></textarea>
                            </div>
                          </div>
                        </div>
                        <div class="tab-pane" id="tab-items">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab-modules" data-toggle="tab"><?php echo $tab_modules; ?></a></li>
                                        <li><a href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab-modules">
                                            <?php foreach ($modules as $module) { ?>
                                            <div class="module-block ui-draggable ui-draggable-handle" data-code="<?php echo $module['code'] . '-' . $module['module_id']; ?>">
                                                <i class="fa fa-arrows-alt"></i><span class="module-name"><?php echo $module['name']; ?></span>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="tab-pane" id="tab-products">
                                            <div id="search" class="input-group">
                                                <input type="text" name="search" value="" placeholder="Search" class="form-control input-lg" autocomplete="off"><ul class="dropdown-menu" style="padding: 2px; display: none;"></ul>
                                                <span class="input-group-btn">
                                                  <button type="button" disabled class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dashed dashed-module-list ui-droppable ui-sortable">
                                        <div class="text-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                        <?php foreach ($feeds as $feed) { ?>
                                        <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $feed['code'] . '-' . $feed['id'] ; ?>">
                                            <div class="mblock-header">
                                                <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i>
                                                    <span class="module-name"><?php echo $feed['name'] ; ?></span>
                                                </div>
                                            </div>
                                            <div class="mblock-control-menu ui-sortable-handle">
                                                <div class="mblock-action pull-right">
                                                    <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_remove_feed; ?>') ? removeFeed($(this)):false;"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="facebook_store_feed[]" value="<?php echo $feed['code'] . '-' . $feed['id'] ; ?>">
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
var confirm_text = '<?php echo $text_remove_feed; ?>';

$(document).ready(function() {
    FaceBook.init();
});

function save(type){
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
}
//--></script>

<?php echo $footer; ?>
