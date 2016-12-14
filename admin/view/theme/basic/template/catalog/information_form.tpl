<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-information" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-information" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-information" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-information" class="form-horizontal">
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
                                            <label class="col-sm-12" for="input-title<?php echo $language['language_id']; ?>"><?php echo $entry_title; ?></label>
                                            <div class="col-sm-12">
                                                <input type="text" name="information_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" id="input-title<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
                                                <?php if (isset($error_title[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
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
                                        <div class="form-group required">
                                            <label class="col-sm-12" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                                            <div class="col-sm-12">
                                                <textarea name="information_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['description'] : ''; ?></textarea>
                                                <?php if (isset($error_description[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_description[$language['language_id']]; ?></div>
                                                <?php } ?>
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
                                    <label class="col-sm-12" for="input-bottom"><span data-toggle="tooltip" title="<?php echo $help_bottom; ?>"><?php echo $entry_bottom; ?></span></label>
                                    <div class="col-sm-12">
                                        <div class="checkbox">
                                            <label>
                                            <?php if ($bottom) { ?>
                                            <input type="checkbox" name="bottom" value="1" checked="checked" id="input-bottom" />
                                            <?php } else { ?>
                                            <input type="checkbox" name="bottom" value="1" id="input-bottom" />
                                            <?php } ?>
                                            &nbsp; </label>
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
                                            <button type="button" id="seo-show-<?php echo $language['language_id']; ?>" onclick="editSEO(<?php echo $language['language_id']; ?>);" data-toggle="tooltip" title="<?php echo $button_seo; ?>" class="btn btn-primary btn-sm btn-basic-list"><i class="fa fa-pencil"></i></button>
                                        </div>
                                        <?php if (!empty($seo_url[$language['language_id']])) { ?>
                                        <div id="seo-preview-<?php echo $language['language_id']; ?>" class="form-group">
                                            <div class="col-sm-12 seo-preview-title"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_title'] : ''; ?></div>
                                            <div class="col-sm-12 seo-preview-url">
                                                <?php $link = str_replace(basename($preview[$language['language_id']]), '', $preview[$language['language_id']]);
                                                    echo $link; ?><span id="seo-url-<?php echo $language['language_id']; ?>"><?php echo isset($seo_url[$language['language_id']]) ? $seo_url[$language['language_id']] : ''; ?></span>
                                            </div>
                                            <div class="col-sm-12 seo-preview-description"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></div>
                                        </div>
                                        <?php } else { ?>
                                        <div id="seo-new-product-<?php echo $language['language_id']; ?>">
                                            <?php echo $text_new_information_seo; ?>
                                        </div>
                                        <?php } ?>
                                        <div id="seo-edit-language-<?php echo $language['language_id']; ?>" class="hidden">
                                            <hr>
                                            <div class="form-group">
                                                <label class="col-sm-12" for="input-meta-title<?php echo $language['language_id']; ?>"><?php echo $entry_meta_title; ?></label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="information_description[<?php echo $language['language_id']; ?>][meta_title]" value="<?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_title'] : ''; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
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
                                                    <textarea name="information_description[<?php echo $language['language_id']; ?>][meta_description]" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description<?php echo $language['language_id']; ?>" class="form-control input-full-width"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_description'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group hidden">
                                                <label class="col-sm-12" for="input-meta-keyword<?php echo $language['language_id']; ?>"><?php echo $entry_meta_keyword; ?></label>
                                                <div class="col-sm-12">
                                                    <textarea name="information_description[<?php echo $language['language_id']; ?>][meta_keyword]" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword<?php echo $language['language_id']; ?>" class="form-control input-full-width"><?php echo isset($information_description[$language['language_id']]) ? $information_description[$language['language_id']]['meta_keyword'] : ''; ?></textarea>
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
            <?php if (in_array(0, $information_store)) { ?>
            <input type="hidden" name="information_store[]" value="0" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="information_store[]" value="0" />
            <?php } ?>
            <?php foreach ($stores as $store) { ?>
            <?php if (in_array($store['store_id'], $information_store)) { ?>
            <input type="hidden" name="information_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="hidden" name="information_store[]" value="<?php echo $store['store_id']; ?>" />
            <?php } ?>
            <?php } ?>
            <input type="hidden" name="sort_order" value="<?php echo $sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            <select name="information_layout[0]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($information_layout[0]) && $information_layout[0] == $layout['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php foreach ($stores as $store) { ?>
            <select name="information_layout[<?php echo $store['store_id']; ?>]" class="form-control hidden">
                <option value=""></option>
                <?php foreach ($layouts as $layout) { ?>
                <?php if (isset($information_layout[$store['store_id']]) && $information_layout[$store['store_id']] == $layout['layout_id']) { ?>
                <option value="<?php echo $layout['layout_id']; ?>" selected="selected"><?php echo $layout['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $layout['layout_id']; ?>"><?php echo $layout['name']; ?></option>
                <?php } ?>
                <?php } ?>
            </select>
            <?php } ?>
       </form>
    </div>
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
                    url: 'index.php?route=catalog/information/inline&token=<?php echo $token; ?>&information_id=<?php echo $information_id;?>',
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
                     seo_html += '        <span id="seo-url-' + language_id + '"></span>';
                     seo_html += '     </div>';
                     */
                    seo_html += '     <div class="col-sm-12 seo-preview-description"></div>';
                } else {
                    seo_html += '    <div class="col-sm-12 seo-preview-title"></div>';

                    seo_html += '    <div class="col-sm-12 seo-preview-url">';
                    /*
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
    <script type="text/javascript"><!--
    $('#language a:first').tab('show');
    $('#seo-language a:first').tab('show');
    //--></script></div>
<?php echo $footer; ?>
