<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-category" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-category" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-category" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-category" class="form-horizontal">
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
                                                <input type="text" name="category_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
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
                                                     echo $link; ?><span class="seo-url" data-lang="<?php echo $language['language_id']; ?> "><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
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
                                                <textarea name="category_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['description'] : ''; ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
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
                                    <label class="col-sm-12" for="input-parent"><?php echo $entry_parent; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="path" value="<?php echo $path; ?>" placeholder="<?php echo $entry_parent; ?>" id="input-parent" class="form-control" />
                                        <input type="hidden" name="parent_id" value="<?php echo $parent_id; ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $entry_image; ?></label>
                                    <div class="col-sm-12"><a href="" id="thumb-image" data-toggle="image" class="img-thumbnail"><img src="<?php echo $thumb; ?>" alt="" title="" data-placeholder="<?php echo $placeholder; ?>" /></a>
                                        <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-top"><span data-toggle="tooltip" title="<?php echo $help_top; ?>"><?php echo $entry_top; ?></span></label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <?php if ($top) { ?>
                                                <input type="checkbox" name="top" value="1" checked="checked" id="input-top" />
                                                <?php } else { ?>
                                                <input type="checkbox" name="top" value="1" id="input-top" />
                                                <?php } ?>
                                                &nbsp; </label>
                                        </div>
                                    </div>
                                </div>
                                <?php if(!empty($menu_name_override) && $top) { ?>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-update-menu-name"><span data-toggle="tooltip" title="<?php echo $help_update_menu_name; ?>"><?php echo $entry_update_menu_name; ?></span></label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="update_menu_name" value="1" id="input-update-menu-name" />
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-column"><span data-toggle="tooltip" title="<?php echo $help_column; ?>"><?php echo $entry_column; ?></span></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="column" value="<?php echo $column; ?>" placeholder="<?php echo $entry_column; ?>" id="input-column" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="filter" value="" placeholder="<?php echo $entry_filter; ?>" id="input-filter" class="form-control" />
            <?php foreach ($category_filters as $category_filter) { ?>
            <input type="hidden" name="category_filter[]" value="<?php echo $category_filter['filter_id']; ?>" />
            </div>
            <?php } ?>
            <?php if (in_array(0, $category_store)) { ?>
            <input type="hidden" name="category_store[]" value="0" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="category_store[]" value="0" />
            <?php } ?>
            <?php foreach ($stores as $store) { ?>
            <?php if (in_array($store['store_id'], $category_store)) { ?>
            <input type="hidden" name="category_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="category_store[]" value="<?php echo $store['store_id']; ?>" />
            <?php } ?>
            <?php } ?>
            <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            <?php foreach ($languages as $language) { ?>
            <input type="hidden" name="seo_url[<?php echo $language['language_id']; ?>]" value="<?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?>" placeholder="<?php echo $entry_seo_url; ?>" id="input-seo-url-<?php echo $language['language_id']; ?>" class="form-control" />
            <input type="hidden" name="category_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control" />
            <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control hidden"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
            <textarea name="category_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control hidden"><?php echo isset($category_description[$language['language_id']]) ? $category_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
            <?php } ?>
            <select name="category_layout[0]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($category_layout[0]) && $category_layout[0] == $layout['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php foreach ($stores as $store) { ?>
            <select name="category_layout[<?php echo $store['store_id']; ?>]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($category_layout[$store['store_id']]) && $category_layout[$store['store_id']] == $layout['layout_id']) { ?>
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
        .data .col-sm-12  {
            margin-bottom: 5px !important;
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
                    url: 'index.php?route=catalog/category/inline&token=<?php echo $token; ?>&category_id=<?php echo $category_id;?>',
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
    $('input[name=\'path\']').autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    json.unshift({
                        category_id: 0,
                        name: '<?php echo $text_none; ?>'
                    });

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
            $('input[name=\'path\']').val(item['label']);
            $('input[name=\'parent_id\']').val(item['value']);
        }
    });
    //--></script>
    <script type="text/javascript"><!--
    $('#language a:first').tab('show');
    $('#seo-language a:first').tab('show');
    //--></script></div>
<?php echo $footer; ?>
