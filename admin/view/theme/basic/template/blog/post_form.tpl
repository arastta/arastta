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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-blog" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-8">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <ul class="nav nav-tabs" id="language">
                                    <?php foreach ($languages as $language) { ?>
                                    <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>"/> <?php echo $language['name']; ?></a></li>
                                    <?php } ?>
                                </ul>
                                <div class="tab-content">
                                    <?php foreach ($languages as $language) { ?>
                                    <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                        <div class="form-group required">
                                            <label class="col-sm-12"><?php echo $entry_name; ?></label>
                                            <div class="col-sm-12">
                                                <input name="post_description[<?php echo $language['language_id']; ?>][name]" class="form-control" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['name'] : ''; ?>"/>
                                                <?php if (isset($error_name[$language['language_id']])) { ?>
                                                <span class="error"><?php echo $error_name[$language['language_id']]; ?></span>
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
                                            <label class="col-sm-12"><?php echo $entry_description; ?></label>
                                            <div class="col-sm-12">
                                                <textarea name="post_description[<?php echo $language['language_id']; ?>][description]" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['description'] : ''; ?></textarea>
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
                                    <div class="col-sm-9"></div>
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
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-12"><?php echo $entry_author; ?></label>
                                        <div class="col-sm-12">
                                            <input type="text" name="author" class="form-control" value="<?php echo $author; ?>"/>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <label class="col-sm-12"><?php echo $entry_allow_comment; ?></label>
                                        <div class="col-sm-12">
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
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <label class="col-sm-12"><?php echo $entry_featured; ?></label>
                                        <div class="col-sm-12">
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
									<label class="col-sm-12" for="input-date-available"><?php echo $entry_date_available; ?></label>
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
                                    <label class="col-sm-12" for="input-category">
                                        <div class="view-all pull-right">
                                            <a href="<?php echo $show_all[$language['language_id']]['category']; ?>" class="popup"><?php echo $entry_view_all; ?></a>
                                        </div>
                                        <span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span>
                                    </label>
                                    <div class="col-sm-12">
                                        <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="input-category" class="form-control input-full-width" style="margin-bottom: 5px !important;"/>
                                        <?php if (!empty($post_categories)) { ?>
                                        <div id="post-category" class="well well-sm" style="overflow: auto;">
                                            <?php foreach ($post_categories as $post_category) { ?>
                                            <div id="post-category<?php echo $post_category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $post_category['name']; ?>
                                                <input type="hidden" name="post_category[]" value="<?php echo $post_category['category_id']; ?>" />
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
                                                    <b><a href="<?php echo $show_all[$language['language_id']]['tag']; ?>" class="popup"><?php echo $entry_view_all; ?></a></b>
                                                </div>
                                                <input type="text" name="tag" value="" placeholder="<?php echo $entry_tag; ?>" id="input-tag<?php echo $language['language_id']; ?>" data-lang="<?php echo $language['language_id']; ?>" class="form-control input-full-width" style="margin-bottom: 5px !important;" />
                                                <?php if (!empty($post_description[$language['language_id']]['tag'])) { ?>
                                                <div id="post-tag-<?php echo $language['language_id']; ?>" class="well well-sm" style="overflow: auto; clear: both;">
                                                    <?php foreach ($post_description[$language['language_id']]['tag'] as $tag_key => $tag_value) { ?>
                                                    <div id="post-tag<?php echo $tag_key; ?>"><i class="fa fa-minus-circle"></i> <?php echo $tag_value; ?>
                                                        <input type="hidden" name="post_tag[<?php echo $language['language_id']; ?>][]" value="<?php echo $tag_value; ?>" />
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
                                            <div class="col-sm-12 seo-preview-title"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_title'] : ''; ?></div>
                                            <div class="col-sm-12 seo-preview-url">
                                                <?php $link = str_replace(basename($preview[$language['language_id']]), '', $preview[$language['language_id']]);
                                                    echo $link; ?><span id="seo-url-<?php echo $language['language_id']; ?>"><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
                                            </div>
                                            <div class="col-sm-12 seo-preview-description"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_description'] : ''; ?></div>
                                        </div>
                                        <?php } else { ?>
                                        <div id="seo-new-post-<?php echo $language['language_id']; ?>">
                                            <?php echo $text_new_post_seo; ?>
                                        </div>
                                        <?php } ?>
                                        <div id="seo-edit-language-<?php echo $language['language_id']; ?>" class="hidden">
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-sm-12" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="post_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
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
                                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control input-full-width"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="col-sm-12" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                                <div class="col-sm-12">
                                                    <textarea name="post_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control input-full-width"><?php echo isset($post_description[$language['language_id']]) ? $post_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
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
            <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            <?php if (in_array(0, $post_store)) { ?>
            <input type="hidden" name="post_store[]" value="0" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="post_store[]" value="0" />
            <?php } ?>
            <?php foreach ($stores as $store) { ?>
            <?php if (in_array($store['store_id'], $post_store)) { ?>
            <input type="hidden" name="post_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="post_store[]" value="<?php echo $store['store_id']; ?>" />
            <?php } ?>
            <?php } ?>
            <select name="post_layout[0]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($post_layout[0]) && $post_layout[0] == $layout['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php foreach ($stores as $store) { ?>
            <select name="post_layout[<?php echo $store['store_id']; ?>]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($post_layout[$store['store_id']]) && $post_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
                        url: 'index.php?route=catalog/post/inline&token=<?php echo $token; ?>&post_id=<?php echo $post_id;?>',
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
        // Tag
        var tag_language = 0;
        $('input[name=\'tag\']').autocomplete({
            'source': function(request, response) {
                tag_language = parseInt($(this).attr('id').replace('input-tag',''));
                var input = this.element;

                $.ajax({
                    url: 'index.php?route=blog/post/autocomplete&token=<?php echo $token; ?>&tag_name=' +  encodeURIComponent(request),
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

        $('input[name=\'tag\']').keypress(function(e) {
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
            'source': function(request, response) {
                $.ajax({
                    url: 'index.php?route=blog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
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

                if (!$('#post-category').length) {
                    $('input[name=\'category\']').after('<div id="post-category" class="well well-sm" style="overflow: auto;"></div>');
                }

                $('#post-category' + item['value']).remove();

                $('#post-category').append('<div id="post-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="post_category[]" value="' + item['value'] + '" /></div>');
            }
        });

        // $('#post-category').delegate('.fa-minus-circle', 'click', function() {
        $(document).on('click', '#post-category .fa-minus-circle', function() {
            $(this).parent().remove();

            if (!$("div[id^='post-category'] i").hasClass('fa-minus-circle')) {
                $('#post-category').remove();
            }
        });

        $(document).on('click', '.btn-category-add', function() {
            $.ajax({
                url: 'index.php?route=blog/category/quick&token=<?php echo $token; ?>',
                type: 'post',
                data: {name: $('#input-category').val(), sort_order: '0', status: '1', column: '1', parent_id: '0'},
                dataType: 'json',
                success: function(json) {
                    if (json['success']) {
                        $('.btn-category-add').remove();
                        $('.tooltip.fade.top.in').removeClass('in');

                        if (!$('#post-category').length) {
                            $('input[name=\'category\']').after('<div id="post-category" class="well well-sm" style="overflow: auto;"></div>');
                        }

                        html  = '<div id="post-category' + json['category_id'] + '">';
                        html += '    <i class="fa fa-minus-circle"></i> ' + $('#input-category').val();
                        html += '    <input type="hidden" name="post_category[]" value="' + json['category_id'] + '">';
                        html += '</div>';

                        $('#input-category').val('');

                        $('#post-category').append(html);

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
        $('#tag-language a:first').tab('show');
        $('#seo-language a:first').tab('show');
        //--></script>
    <script type="text/javascript"><!--
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

                $('#seo-new-post-'+ language_id).before(seo_html);
                $('#seo-new-post-'+ language_id).remove();
            }
        });

        $(document).delegate('.editable-input .form-control.input-sm', 'keyup', function() {
            language_id = parseInt($(this).parent().parent().parent().parent().parent().parent().parent().find('.seo-url').data('lang'));

            if ($('#seo-preview-' + language_id).length) {
                $('#seo-preview-' + language_id + ' .seo-preview-url #seo-url-' + language_id).html($(this).val());
            }
        });
        //--></script>
</div>
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
