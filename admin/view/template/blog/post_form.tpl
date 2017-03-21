<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-blog" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-blog" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-blog" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-data" data-toggle="tab"><?php echo $tab_data; ?></a></li>
                        <li><a href="#tab-seo" data-toggle="tab"><?php echo $tab_seo; ?></a></li>
                        <li><a href="#tab-links" data-toggle="tab"><?php echo $tab_links; ?></a></li>
                        <li><a href="#tab-design" data-toggle="tab"><?php echo $tab_design; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">
                            <ul class="nav nav-tabs" id="language">
                                <?php foreach ($languages as $language) { ?>
                                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/> <?php echo $language['name']; ?></a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php foreach ($languages as $language) { ?>
                                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                    <div class="form-group required">
                                        <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                                        <div class="col-sm-10">
                                            <input name="post_description[<?php echo $language['language_id']; ?>][name]" class="form-control" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['name'] : ''; ?>"/>
                                            <?php if (isset($error_name[$language['language_id']])) { ?>
                                            <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="post_description[<?php echo $language['language_id']; ?>][description]" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['description'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-tag<?php echo $language['language_id']; ?>"><span data-toggle="tooltip" title="<?php echo $help_tag; ?>"><?php echo $entry_tag; ?></span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="tag" value="" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" class="form-control" />
                                            <div class="view-all"><a href="<?php echo $show_all[$language['language_id']]['tag']; ?>" class="popup"><?php echo $entry_view_all; ?></a></div>
                                            <?php if (!empty($post_description[$language['language_id']]['tag'])) { ?>
                                            <div id="blog-tag-<?php echo $language['language_id']; ?>" class="well well-sm" style="overflow: auto; clear: both;">
                                                <?php foreach ($post_description[$language['language_id']]['tag'] as $tag_key => $tag_value) { ?>
                                                <div id="blog-tag-<?php echo $tag_key; ?>"><i class="fa fa-minus-circle"></i> <?php echo $tag_value; ?>
                                                    <input type="hidden" name="post_tag[<?php echo $language['language_id']; ?>][]" value="<?php echo $tag_value; ?>" />
                                                </div>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-data">
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
                                <div class="col-sm-10">
                                    <a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>"/></a>
                                    <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_author; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="author" class="form-control" value="<?php echo $author; ?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_allow_comment; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($allow_comment) { ?>
                                        <input type="radio" name="allow_comment" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="allow_comment" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$allow_comment) { ?>
                                        <input type="radio" name="allow_comment" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="allow_comment" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_featured; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($featured) { ?>
                                        <input type="radio" name="featured" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="featured" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$featured) { ?>
                                        <input type="radio" name="featured" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="featured" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
							<div class="form-group">
								<label class="col-sm-2 control-label" for="input-date-available"><?php echo $entry_date_available; ?></label>
								<div class="col-sm-3">
									<div class="input-group date">
										<input type="text" name="date_available" value="<?php echo $date_available; ?>" placeholder="<?php echo $entry_date_available; ?>" data-date-format="YYYY-MM-DD" id="input-date-available" class="form-control" />
                                        <span class="input-group-btn">
                                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                                        </span>
									</div>
								</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_status; ?></label>
                                <div class="col-sm-10">
                                    <label class="radio-inline">
                                        <?php if ($status) { ?>
                                        <input type="radio" name="status" value="1" checked="checked" />
                                        <?php echo $text_yes; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="status" value="1" />
                                        <?php echo $text_yes; ?>
                                        <?php } ?>
                                    </label>
                                    <label class="radio-inline">
                                        <?php if (!$status) { ?>
                                        <input type="radio" name="status" value="0" checked="checked" />
                                        <?php echo $text_no; ?>
                                        <?php } else { ?>
                                        <input type="radio" name="status" value="0" />
                                        <?php echo $text_no; ?>
                                        <?php } ?>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_sort_order; ?></label>
                                <div class="col-sm-10">
                                    <input name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="form-control"/>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-seo">
                            <ul class="nav nav-tabs" id="seo-language">
                                <?php foreach ($languages as $language) { ?>
                                <li><a href="#seo-language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                <?php } ?>
                            </ul>
                            <div class="tab-content">
                                <?php foreach ($languages as $language) { ?>
                                <div class="tab-pane" id="seo-language<?php echo $language['language_id']; ?>">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label" for="input-seo-url"><span data-toggle="tooltip" title="<?php echo $help_seo_url; ?>"><?php echo $entry_seo_url; ?></span></label>
                                        <div class="col-sm-10">
                                            <input type="text" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url" class="form-control" />
                                            <?php if (isset($error_seo_url[$language['language_id']])) { ?>
                                            <div class="text-danger"><?php echo $error_seo_url[$language['language_id']]; ?></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $entry_meta_title; ?></label>
                                        <div class="col-sm-10">
                                            <input name="post_description[<?php echo $language['language_id']; ?>][meta_title]" class="form-control" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_title'] : ''; ?>"/>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $entry_meta_description; ?></label>
                                        <div class="col-sm-10">
                                            <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_description]" class="form-control"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label"><?php echo $entry_meta_keyword; ?></label>
                                        <div class="col-sm-10">
                                            <input name="post_description[<?php echo $language['language_id']; ?>][meta_keyword]" class="form-control" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_keyword'] : ''; ?>"/>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-links">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_category; ?></label>
                                <div class="col-sm-10">
                                    <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" class="form-control"/>
                                    <div id="blog-category" class="well well-sm" style="height: 150px; overflow: auto;">
                                        <?php foreach ($post_categories as $post_category) { ?>
                                        <?php if (in_array($post_category['category_id'], $post_category)) { ?>
                                        <div id="blog-category<?php echo $post_category['category_id']; ?>">
                                            <i class="fa fa-minus-circle"></i> <?php echo $post_category['name']; ?>
                                            <input type="hidden" name="post_category[]" value="<?php echo $post_category['category_id']; ?>"/>
                                        </div>
                                        <?php } ?>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_store; ?></label>
                                <div class="col-sm-10">
                                    <div class="well well-sm" style="height: 150px; overflow: auto;">
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array(0, $post_store)) { ?>
                                                <input type="checkbox" name="post_store[]" value="0" checked="checked"/>
                                                <?php echo $text_default; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="post_store[]" value="0"/>
                                                <?php echo $text_default; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php foreach ($stores as $store) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($store['store_id'], $post_store)) { ?>
                                                <input type="checkbox" name="post_store[]" value="<?php echo $store['store_id']; ?>" checked="checked"/>
                                                <?php echo $store['name']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="post_store[]" value="<?php echo $store['store_id']; ?>"/>
                                                <?php echo $store['name']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tab-design">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <td class="text-left"><?php echo $entry_store; ?></td>
                                        <td class="text-left"><?php echo $entry_layout; ?></td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-left"><?php echo $text_default; ?></td>
                                        <td class="text-left"><select name="post_layout[0]" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($layouts as $layout) { ?>
                                            <?php if (isset($post_layout[0]) && $post_layout[0] == $layout['layout_id']) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select></td>
                                    </tr>
                                    <?php foreach ($stores as $store) { ?>
                                    <tr>
                                        <td class="text-left"><?php echo $store['name']; ?></td>
                                        <td class="text-left"><select name="post_layout[<?php echo $store['store_id']; ?>]" class="form-control">
                                            <option value=""></option>
                                            <?php foreach ($layouts as $layout) { ?>
                                            <?php if (isset($post_layout[$store['store_id']]) && $post_layout[$store['store_id']] == $layout['layout_id']) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select></td>
                                    </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
        $(document).ready(function() {
        <?php foreach ($languages as $language) { ?>
        textEditor('#input-description<?php echo $language["language_id"]; ?>');
        <?php } ?>
        });
        //--></script>
    <script type="text/javascript"><!--
        $('#language a:first').tab('show');
        $('#seo-language a:first').tab('show');
        //--></script>
    <script type="text/javascript"><!--
        // Tag
        var tag_language = 0;
        $('input[name=\'tag\']').autocomplete({
            'source': function(request, response) {
                tag_language = parseInt($(this).attr('id').replace('input-tag',''));

                $.ajax({
                    url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&tag_name=' +  encodeURIComponent(request),
                    type: 'post',
                    data: { tag_text: $('#post-tag-' + tag_language + ' input').serializeArray() },
                    dataType: 'json',
                    success: function(json) {
                        if (json['new']) {
                            add_tag = true;

                            new_tag = $('input[name=\'tag\']').val();

                            tags = $('#post-tag-' + tag_language + ' input').serializeArray();

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
                if (!$('#post-tag-' + tag_language).length) {
                    $(this).parent().append('<div id="post-tag-' + tag_language + '" class="well well-sm" style="overflow: auto; clear: both;"></div>');
                }

                $('#post-tag-' + tag_language + ' #post-tag' + item['value']).remove();

                $('#post-tag-' + tag_language).append('<div id="post-tag' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['value'] + '<input type="hidden" name="post_tag[' + tag_language  +'][]" value="' + item['value'] + '" /></div>');

                $(this).val('');
            }
        });

        $(document).on('click', 'div[id^=\'post-tag-\'] .fa-minus-circle', function() {
            _parent = $(this).parent().parent();

            $(this).parent().remove();

            if (!_parent.find('i').hasClass('fa-minus-circle')) {
                _parent.remove();
            }
        });

        $('input[name=\'tag\']').keypress(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                e.stopImmediatePropagation();

                add_tag = true;

                new_tag = $(this).val();

                $('input[name=\'tag\']').val('');

                $('#post-tag' + new_tag).remove();

                tag_language = parseInt($(this).attr('id').replace('input-tag',''));

                if (!$('#post-tag-' + tag_language).length) {
                    $(this).parent().append('<div id="post-tag-' + tag_language + '" class="well well-sm" style="overflow: auto; clear: both;"></div>');
                }

                tags = $('#post-tag-' + tag_language + ' input').serializeArray();

                $.each(tags, function(key, tag) {
                    if (tag.value == new_tag) {
                        add_tag = false;

                        return false;
                    }
                });

                if (add_tag) {
                    $('#post-tag-' + tag_language).append('<div id="post-tag' + new_tag + '"><i class="fa fa-minus-circle"></i> ' + new_tag + '<input type="hidden" name="post_tag[' + tag_language  +'][]" value="' + new_tag + '" /></div>');
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
            'source': function (request, response) {
                $.ajax({
                    url     : 'index.php?route=blog/blog_category/autocomplete&token=<?php echo $token; ?>&filter_name=' + encodeURIComponent(request),
                    dataType: 'json',
                    success : function (json) {
                        response($.map(json, function (item) {
                            return {
                                label: item['name'],
                                value: item['category_id']
                            }
                        }));
                    }
                });
            },
            'select': function (item) {
                $('input[name=\'category\']').val('');

                $('#blog-category' + item['value']).remove();

                $('#blog-category').append('<div id="blog-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_category[]" value="' + item['value'] + '" /></div>');
            }
        });

        $('#blog-category').delegate('.fa-minus-circle', 'click', function () {
            $(this).parent().remove();
        });

		$('.date').datetimepicker({
			pickTime: false
		});
        //--></script>
    <script type="text/javascript"><!--
        function save(type){
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'button';
            input.value = type;
            form = $("form[id^='form-']").append(input);
            form.submit();
        }
        //--></script>
</div>
<?php echo $footer; ?>