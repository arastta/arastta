<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-review" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-review" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-review" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-review" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-author"><?php echo $entry_author; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $entry_author; ?>" id="input-author" class="form-control" />
                                        <?php if ($error_author) { ?>
                                        <div class="text-danger"><?php echo $error_author; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-product"><span data-toggle="tooltip" title="<?php echo $help_product; ?>"><?php echo $entry_product; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="product" value="<?php echo $product; ?>" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
                                        <?php if ($error_product) { ?>
                                        <div class="text-danger"><?php echo $error_product; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-text"><?php echo $entry_text; ?></label>
                                    <div class="col-sm-12">
                                        <textarea name="text" cols="60" rows="8" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control"><?php echo $text; ?></textarea>
                                        <?php if ($error_text) { ?>
                                        <div class="text-danger"><?php echo $error_text; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-name"><?php echo $entry_rating; ?></label>
                                    <div class="col-sm-12">
                                        <div class="star-rating">
                                          <div class="star-rating-wrap">
                                            <?php if ($rating == 5) { ?>
                                            <input class="star-rating-input" id="star-rating-5" type="radio" name="rating" value="5" checked="checked" >
                                            <?php } else { ?>
                                            <input class="star-rating-input" id="star-rating-5" type="radio" name="rating" value="5">
                                            <?php } ?>
                                            <label class="star-rating-ico fa fa-star-o fa-lg" for="star-rating-5" title="5 out of 5 stars"></label>
                                            <?php if ($rating == 4) { ?>
                                            <input class="star-rating-input" id="star-rating-4" type="radio" name="rating" value="4" checked="checked" >
                                            <?php } else { ?>
                                            <input class="star-rating-input" id="star-rating-4" type="radio" name="rating" value="4">
                                            <?php } ?>
                                            <label class="star-rating-ico fa fa-star-o fa-lg" for="star-rating-4" title="4 out of 5 stars"></label>
                                            <?php if ($rating == 3) { ?>
                                            <input class="star-rating-input" id="star-rating-3" type="radio" name="rating" value="3" checked="checked" >
                                            <?php } else { ?>
                                            <input class="star-rating-input" id="star-rating-3" type="radio" name="rating" value="3">
                                            <?php } ?>
                                            <label class="star-rating-ico fa fa-star-o fa-lg" for="star-rating-3" title="3 out of 5 stars"></label>
                                            <?php if ($rating == 2) { ?>
                                            <input class="star-rating-input" id="star-rating-2" type="radio" name="rating" value="2" checked="checked" >
                                            <?php } else { ?>
                                            <input class="star-rating-input" id="star-rating-2" type="radio" name="rating" value="2">
                                            <?php } ?>
                                            <label class="star-rating-ico fa fa-star-o fa-lg" for="star-rating-2" title="2 out of 5 stars"></label>
                                            <?php if ($rating == 1) { ?>
                                            <input class="star-rating-input" id="star-rating-1" type="radio" name="rating" value="1" checked="checked" >
                                            <?php } else { ?>
                                            <input class="star-rating-input" id="star-rating-1" type="radio" name="rating" value="1">
                                            <?php } ?>
                                            <label class="star-rating-ico fa fa-star-o fa-lg" for="star-rating-1" title="1 out of 5 stars"></label>
                                          </div>
                                        </div>
                                        <?php if ($error_rating) { ?>
                                        <div class="text-danger"><?php echo $error_rating; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
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
                                    <div class="col-sm-12" style="margin-bottom: 10px;">
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
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript"><!--
    $('input[name=\'product\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    if (json.length === 0) {
                        $.ajax({
                            url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
                            dataType: 'json',
                            success: function(json) {
                                response($.map(json, function(item) {
                                    return {
                                        label: item['name'],
                                        value: item['product_id']
                                    }
                                }));
                            }
                        });
                    } else {
                        response($.map(json, function(item) {
                            return {
                                label: item['name'],
                                value: item['product_id']
                            }
                        }));
                    }
                }
            });
        },
        'select': function(item) {
            $('input[name=\'product\']').val(item['label']);
            $('input[name=\'product_id\']').val(item['value']);
        }
    });
    //--></script></div>
<?php echo $footer; ?>
