<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
    <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <?php foreach ($scripts as $script) { ?>
    <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <script type="text/javascript">
        var wpColorPickerL10n = {"clear":"<?php echo $entry_clear; ?>","defaultString":"<?php echo $entry_default; ?>","pick":"<?php echo $entry_select_color; ?>","current":"<?php echo $entry_current_color; ?>"};
    </script>
</head>
<body>
<div class="wp-full-overlay expanded">
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
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="customizer-controls" class="wrap wp-full-overlay-sidebar">
        <div id="customizer-header-actions" class="wp-full-overlay-header">
            <button type="button" name="reset" id="reset" class="btn btn-danger button-primary reset" data-toggle="tooltip" title="<?php echo $button_reset; ?>" data-original-title="Reset Customizer editing">
                <i class="fa fa-trash-o"></i>
            </button>
            <button type="submit" name="save" id="save" class="btn btn-default button-primary save" data-toggle="tooltip" title="<?php echo $button_save; ?>" data-original-title="Save Customizer editing">
                <i class="fa fa-save text-success"></i>
            </button>
            <div class="checkbox advance">
                <label><input type="checkbox" id="advance-conrol" value=""><span><?php echo $text_advance; ?></span></label>
            </div>
            <a class="customizer-controls-close" href="<?php echo $button_back;?>">
                <span class="screen-reader-text"><?php echo $entry_close; ?></span>
            </a>
            <span class="control-panel-back" tabindex="-1">
                <span class="screen-reader-text"><?php echo $entry_back; ?></span>
            </span>
        </div>
        <div id="widgets-right">
            <div class="wp-full-overlay-sidebar-content" tabindex="-1">
                <div id="customizer-theme-controls" class="module_accordion ds_accordion">
                    <div id="accordion-section-themes" class="customize-themes">
                        <h3 class="accordion-section-title nonecontent">
                            <p class="description customizer-section-description"><?php echo 'Change Themes'; ?></p>
                            <select name="customizer_themes" id="customizer_themes" class="form-control">
                                <?php foreach ($templates as $template) { ?>
                                <?php if (!isset($theme) && $template == $config_template) { ?>
                                <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                                <?php } elseif ($template == $theme)  { ?>
                                <option value="<?php echo $template; ?>" selected="selected"><?php echo $template; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $template; ?>"><?php echo $template; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </h3>
                    </div>
                    <ul id="customize-all-content">
                        <?php foreach($sections as $section_name => $section_value){ ?>
                        <li id="accordion-section-<?php echo $section_name; ?>" class="ds_heading accordion-section control-section control-section-default" style="">
                            <h3 class="accordion-section-title" tabindex="0">
                                <?php echo $section_value['title']; ?>
                                <span class="screen-reader-text"><?php echo $text_menu_description;?></span>
                            </h3>
                            <ul class="accordion-section-content ds_content" style="">
                                <?php if (isset($section_value['description'])) { ?>
                                <li class="customizer-section-description-container">
                                    <p class="description customizer-section-description"><?php echo $section_value['description']; ?></p>
                                </li>
                                <?php } ?>
                                <?php foreach($section_value['control'] as $control_name => $control_value) { ?>
                                <li id="customizer-control-<?php echo $control_name; ?>" class="customizer-control customizer-control-text <?php if(!empty($control_value['advance'])) { echo 'advance-hide';} ?>" <?php if(empty($control_value['advance'])) { echo 'style="display: list-item;"';} ?>>
                                <label>
                                    <span class="customizer-control-title"><?php echo $control_value['label']; ?></span>
                                    <?php if(!empty($control_value['description'])) { ?>
                                    <span class="description customizer-control-description"><?php echo $control_value['description']; ?></span>
                                    <?php } ?>
                                    <?php if(!empty($control_value['advance'])){ $isAdvance = '1'; } ?>
                                    <?php switch ($control_value['type']) {
                                                case 'text': ?>
                                    <?php
                                                        if(!empty($default_data[$control_name])) {
                                                            $value = $default_data[$control_name];
                                                        } else if (!empty($control_value['default'])) {
                                                            $value = $control_value['default'];
                                                        } else {
                                                            $value = '';
                                                        }
                                                     ?>
                                    <input  type="text" name="<?php echo $control_name; ?>" id="<?php echo $control_name; ?>" class="form-control" value="<?php echo $value; ?>" placeholder="<?php echo !empty($control_value['placeholder']) ? $control_value['placeholder']  : ''; ?>">
                                    <script type="text/javascript">
                                        <?php $element = explode('_', $control_name); ?>
                                        $( '#customizer-control-<?php echo $control_name; ?> input').on('change', function() {
                                            $('<?php echo $control_value["selector"]; ?>', $('#customizer-preview iframe').text(this.value));
                                        });
                                    </script>
                                    <?php         break; ?>
                                    <?php   case 'textarea': ?>
                                    <?php
                                                        if(!empty($default_data[$control_name])) {
                                                            $value = $default_data[$control_name];
                                                        } else if (!empty($control_value['default'])) {
                                                            $value = $control_value['default'];
                                                        } else {
                                                            $value = '';
                                                        }
                                                     ?>
                                    <textarea name="<?php echo $control_name; ?>" rows="5" id="<?php echo $control_name; ?>" class="form-control"><?php echo $value; ?></textarea>
                                    <?php         break; ?>
                                    <?php   case 'checkbox': ?>
                                    <?php if(!empty($control_value['choices'])) { ?>
                                    <?php foreach($control_value['choices'] as $choices_name => $choices_value){ ?>
                                    <div id="input-<?php echo $control_name; ?>">
                                        <div class="checkbox">
                                            <label>
                                                <?php
                                                    if(!empty($default_data[$control_name])) {
                                                    $selected = "selected=selected" ;
                                                    } else if (!empty($control_value['default'])) {
                                                    $selected = "selected=selected" ;
                                                    } else {
                                                        $selected = '';
                                                    }
                                                     ?>
                                                <input type="checkbox" name="<?php echo $control_name; ?>[]" id="<?php echo $choices_name; ?>" <?php echo $selected; ?>  value="<?php echo $choices_name; ?>">
                                                <?php echo $choices_value; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php } ?>
                                    <script type="text/javascript">
                                        <?php $element = explode('_', $control_name); ?>
                                        $( '#customizer-control-<?php echo $control_name; ?> input').on('change', function() {
                                            $('<?php echo $control_value["selector"]; ?>', $('#customizer-preview iframe').contents()).css('<?php echo $element[1]; ?>', this.value);
                                        });
                                    </script>
                                    <?php         break; ?>
                                    <?php   case 'radio': ?>
                                    <?php if(!empty($control_value['choices'])) { ?>
                                    <?php foreach($control_value['choices'] as $choices_name => $choices_value){ ?>
                                    <div id="input-<?php echo $control_name; ?>">
                                        <div class="radio">
                                            <label>
                                                <?php
                                                if(!empty($default_data[$control_name])) {
                                                    $selected = "selected=selected" ;
                                                } else if (!empty($control_value['default'])) {
                                                    $selected = "selected=selected" ;
                                                } else {
                                                    $selected = '';
                                                }
                                                ?>
                                                <input type="radio" name="<?php echo $control_name; ?>" id="<?php echo $choices_name; ?>" <?php echo $selected; ?> value="<?php echo $choices_name; ?>">
                                                <?php echo $choices_value; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php } ?>
                                    <script type="text/javascript">
                                        <?php $element = explode('_', $control_name); ?>
                                        $( '#customizer-control-<?php echo $control_name; ?> input').on('change', function() {
                                            $('<?php echo $control_value["selector"]; ?>', $('#customizer-preview iframe').contents()).css('<?php echo $element[1]; ?>', this.value);
                                        });
                                    </script>
                                    <?php         break; ?>
                                    <?php   case 'select': ?>
                                    <?php if(!empty($control_value['choices'])) { ?>
                                    <select name="<?php echo $control_name; ?>" id="<?php echo $control_name; ?>" class="form-control">
                                        <?php foreach($control_value['choices'] as $choices_name => $choices_value){ ?>
                                        <?php
                                             if(!empty($default_data[$control_name]) && ($choices_name == $default_data[$control_name])) {
                                                $selected = "selected=selected" ;
                                             } else if (!isset($default_data[$control_name]) && !empty($control_value['default'])) {
                                                $selected = "selected=selected" ;
                                             } else {
                                                $selected = '';
                                             }
                                             ?>
                                        <option value="<?php echo $choices_name; ?>" <?php echo $selected; ?>><?php echo $choices_value; ?></option>
                                        <?php } ?>
                                    </select>
                                    <?php } ?>
                                    <script type="text/javascript">
                                        <?php $element = explode('_', $control_name); ?>
                                        $( '#customizer-control-<?php echo $control_name; ?> select').on('change', function() {
                                            $('<?php echo $control_value["selector"]; ?>', $('#customizer-preview iframe').contents()).css('<?php echo $element[1]; ?>', this.value);
                                        });
                                    </script>
                                    <?php         break; ?>
                                    <?php   case 'font': ?>
                                    <select name="<?php echo $control_name; ?>" id="<?php echo $control_name; ?>" class="form-control">
                                        <option value="inherit" <?php echo (!empty($default_data[$control_name]) and $default_data[$control_name] == "inherit") ? 'selected=selected' : ''; ?>>Inherit</option>
                                        <?php foreach($fonts as $font_key => $font_value){ ?>
                                        <?php $font_key = ($font_key == 'system') ? 'System Fonts' : 'Google Fonts'; ?>
                                        <optgroup label="<?php echo $font_key; ?>">
                                            <?php foreach($font_value as $font){ ?>
                                            <option value="<?php echo $font['family']; ?>" <?php echo (!empty($default_data[$control_name]) and $default_data[$control_name] == $font['family']) ? 'selected=selected' : ''; ?>><?php echo $font['family']; ?></option>
                                            <?php } ?>
                                        </optgroup>
                                        <?php } ?>
                                    </select>
                                    <script type="text/javascript">
                                        $( '#customizer-control-font select').on('change', function() {
                                            $('head', $('#customizer-preview iframe').contents()).append('<link href="//fonts.googleapis.com/css?family='+ this.value.trim().replace(/\s+/g, '+') +'" rel="stylesheet" type="text/css" />');
                                            $('body', $('#customizer-preview iframe').contents()).css({'font-family': this.value});
                                        });
                                    </script>
                                    <?php         break; ?>
                                    <?php   case 'color': ?>
                                    <?php
                                         if(!empty($default_data[$control_name])) {
                                            $value = $default_data[$control_name];
                                         } else if (!empty($control_value['default'])) {
                                            $value = $control_value['default'];
                                         } else {
                                            $value = '';
                                         }
                                     ?>
                                    <div class="customizer-control-content">
                                        <div class="wp-picker-container">
                                                        <span class="wp-picker-input-wrap">
                                                            <input type="text" name="<?php echo $control_name; ?>" id="<?php echo $control_name; ?>" class="color-picker-hex wp-color-picker" maxlength="7" value="<?php echo $value; ?>" data-default-color="<?php echo $value; ?>" style="display: none;">
                                                            <input type="button" class="button button-small hidden wp-picker-default" value="<?php echo $entry_default; ?>">
                                                        </span>
                                            <div class="wp-picker-holder"></div>
                                        </div>
                                    </div>
                                    <script type="text/javascript">
                                        <?php $element = explode('_', $control_name); ?>
                                        picker = $('#<?php echo $control_name; ?>');
                                        picker.val('<?php echo $value; ?>').wpColorPicker();
                                        $( '#customizer-control-<?php echo $control_name; ?> input').on('change', function() {
                                            $('<?php echo $control_value["selector"]; ?>', $('#customizer-preview iframe').contents()).css('<?php echo $element[1]; ?>', this.value);
                                        });
                                    </script>
                                    <?php         break; ?>
                                    <?php   case 'image': ?>
                                    <?php
                                         if(!empty($default_data[$control_name])) {
                                            $value = $default_data[$control_name];
                                            $_value = $default_data[$control_name . '_raw'];
                                         } else if (!empty($control_value['default'])) {
                                            $value = $control_value['default'];
                                            $_value = $control_value['default_raw'];
                                         } else {
                                            $value = $no_image;
                                            $_value = '';
                                         }
                                     ?>
                                    <div class="current">
                                        <span>
                                            <a href="javascript:void(0);" id="thumb-image" data-toggle="<?php echo $control_name; ?>" class="img-thumbnail">
                                                <img src="<?php echo $value; ?>" id="<?php echo $control_name; ?>-src" width="100" height="100" alt=""  title="" data-placeholder="" />
                                            </a>
                                            <input type="hidden" name="<?php echo $control_name; ?>" value="<?php echo $_value; ?>" id="<?php echo $control_name; ?>" />
                                        </span>
                                    </div>
                                    <div style="clear:both"></div>
                                    <?php if ($control_name == 'logo') { ?>
                                    <script type="text/javascript">
                                        $('#customizer-control-<?php echo $control_name; ?> input').on('change', function(e) {
                                            $('<?php echo $control_value["selector"] ?>', $('#customizer-preview iframe').contents()).attr('src', 'image/'+this.value);
                                        });
                                    </script>
                                    <?php } else { ?>
                                    <script type="text/javascript">
                                        $( '#customizer-control-<?php echo $control_name; ?> input').on('change', function() {
                                            $('<?php echo $control_value["selector"]; ?>', $('#customizer-preview iframe').contents()).css('background-image', 'url("image/'+this.value+'")');
                                        });
                                    </script>
                                    <?php } ?>
                                    <?php         break; ?>
                                    <?php } ?>

                                </label>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div id="customizer-footer-actions" class="wp-full-overlay-footer">
            <a href="javascript:void(0);" class="collapse-sidebar button-secondary" title="<?php echo $entry_collapse_sidebar; ?>">
                <span class="collapse-sidebar-arrow"></span>
                <span class="collapse-sidebar-label"><?php echo $entry_collapse; ?></span>
            </a>
        </div>
        <input type="hidden" name="button_back" id="button-back" value="<?php echo $button_back;?>" />
    </form>
    <div id="customizer-footer-actions" style="display:none;" class="wp-full-overlay-footer open-customizer">
        <a href="javascript:void(0);" class="collapse-sidebar button-secondary" title="">
            <span class="collapse-sidebar-arrow"></span>
        </a>
    </div>
    <div id="customizer-preview" class="wp-full-overlay-main"></div>
</div>
</body>
</html>
<script type="text/javascript"><!--
$(function() {
    var collapse = 'open';
    $('.ds_accordion .ds_content').css('display', 'none');

    $('.ds_accordion .ds_heading').click(function() {
        if (!$(this).hasClass('active')) {
            $(this).addClass('active').siblings('.ds_heading').removeClass('active');
            var statsTemplate = $(this).children('.ds_content');
            $(statsTemplate).slideDown(350);
            $(this).siblings('.ds_heading').children('.ds_content').slideUp(350);
        }
    });

    $('.ds_accordion .ds_heading:first-child').each(function(dataAndEvents, deepDataAndEvents) {
        $(this).click();
    });

    $('.collapse-sidebar').click(function(){
        if(collapse == 'open'){
            $( "#customizer-controls" ).animate({marginLeft: "-320px"}, 1 );
            $( ".wp-full-overlay.collapsed" ).animate({marginLeft: "0px"}, 1 );
            $( ".open-customizer" ).show();
            $( ".expanded" ).addClass('collapsed');
            $( ".expanded" ).removeClass('expanded');
            collapse = 'close';
        } else {
            $( "#customizer-controls" ).animate({marginLeft: "0px"}, 1 );
            $( ".wp-full-overlay.expanded" ).animate({marginLeft: "300px"}, 1 );
            $( ".open-customizer" ).hide();
            $( ".collapsed" ).addClass('expanded');
            $( ".collapsed" ).removeClass('collapsed');
            collapse = 'open';
        }
    });
});

function getURLVar(key) {
    var value = [];

    var query = String(document.location).split('?');

    if (query[1]) {
        var part = query[1].split('&');

        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');

            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }

        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
}

$(document).ready(function() {
    // Image Manager
    $(document).delegate('.img-thumbnail', 'click', function(e) {
        e.preventDefault();

        $('.popover').popover('hide', function() {
            $('.popover').remove();
        });

        var element = this;
        $(element).popover({
            html: true,
            placement: 'right',
            trigger: 'manual',
            content: function() {
                return '<button type="button" id="button-image" class="btn btn-primary"><i class="fa fa-pencil"></i></button> <button type="button" id="button-clear" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>';
            }
        });

        $(element).popover('toggle');

        $('#button-image').on('click', function() {
            $('#modal-image').remove();

            $.ajax({
                url: 'index.php?route=common/filemanager&token=' + '<?php echo $token; ?>' + '&target=' + $(element).parent().find('input').attr('id') + '&customizer=' + $(element).parent().find('input').attr('id') + '-src',
                dataType: 'html',
                beforeSend: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
                    $('#button-image').prop('disabled', true);
                },
                complete: function() {
                    $('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
                    $('#button-image').prop('disabled', false);
                },
                success: function(html) {
                    $('body').append('<div id="modal-image" class="modal">' + html + '</div>');

                    $('#modal-image').modal('show');
                }
            });
            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });

        $('#button-clear').on('click', function() {
            $(element).find('img').attr('src', $(element).find('img').attr('data-placeholder'));

            $(element).parent().find('input').attr('value', '');
            $(element).popover('hide', function() {
                $('.popover').remove();
            });
        });

    });

    $('#reset').on('click', function() {
        $.ajax({
            url: '<?php echo str_replace("amp;", "", $reset); ?>',
            dataType: 'json',
            success: function(json) {
                window.location=("<?php echo str_replace('amp;', '', $action . '&button_back=' . base64_encode($button_back)); ?>");
            }
        });
    });

    $( '#customizer-control-custom-css textarea').on('change', function() {
        $("#custom_css", $('#customizer-preview iframe').contents()).remove();
        $('head', $('#customizer-preview iframe').contents()).append('<style type="text/css" id="custom_css">'+this.value+'</style>');
    });

    $( '#customizer-control-custom-js textarea').on('change', function() {
        $("#custom_js", $('#customizer-preview iframe').contents()).remove();
        $('head', $('#customizer-preview iframe').contents()).append('<script type="text/javascript" id="custom_js">'+this.value+'<\/script>');
    });

    <?php if(!empty($isAdvance)){ ?>
        var advance = '0';
        $('.advance').css('display', 'inherit');

        $( '#advance-conrol').on('change', function() {
            if (advance == '0') {
                $('.advance-hide').addClass('advance-show');
                $('.advance-hide').removeClass('advance-hide');

                $('.advance-show input').each(function( index ) {
                    $(this).prop('disabled', false);
                });
                
                advance = '1';
            } else {
                $('.advance-show').addClass('advance-hide');
                $('.advance-show').removeClass('advance-show');

                $('.advance-hide input').each(function( index ) {
                    $(this).prop('disabled', true);
                });
                
                advance = '0';
            }
        });
        <?php  } ?>

    $.ajax({
        url: '<?php echo str_replace("amp;", "", $changeTheme); ?>',
        type: 'post',
        data: {template : '<?php echo $theme; ?>'},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    }).done(function() {
        $.ajax({
            url: '<?php echo $frontend; ?>',
            type: 'post',
            beforeSend: function() {
                $('#customizer-preview').html('<div id="customizer-loading" class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
            },
            success: function(response) {
                $('#customizer-loading').remove();
                iframe = $('<iframe />').appendTo( $('#customizer-preview') );
                iframe[0].contentWindow.document.open();
                iframe[0].contentWindow.document.write( response );
                iframe[0].contentWindow.document.close();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        }).done(function() {
            $.ajax({
                url: '<?php echo str_replace("amp;", "", $changeTheme); ?>',
                type: 'post',
                data: {template : '<?php echo $config_template; ?>'},
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        });
    });

    $( "#customizer_themes" ).change(function() {
        var template = $('#customizer_themes').val();

        $.ajax({
            url: '<?php echo str_replace("amp;", "", $changeTheme); ?>',
            type: 'post',
            data: {template : template},
            beforeSend: function() {
                $('#customizer-preview').html('<div id="customizer-loading" class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
            },
            success: function(response) {
                $('#customizer-loading').remove();

                window.location=("<?php echo str_replace('amp;', '', $action); ?>");
            /* 
             $('#customize-all-content').addClass('removeCustomizeAllContent');
             $('#customize-all-content').before(response['html']);
             $('.removeCustomizeAllContent').remove();

             $.ajax({
                 url: '<?php echo $frontend; ?>',
                 type: 'post',
                 beforeSend: function() {
                    $('#customizer-preview').html('<div id="customizer-loading" class="text-center"><i class="fa fa-spinner fa-spin checkout-spin"></i></div>');
                 },
                 success: function(response) {
                     $('#customizer-loading').remove();
                     
                     iframe = $('<iframe />').appendTo( $('#customizer-preview') );
                     iframe[0].contentWindow.document.open();
                     iframe[0].contentWindow.document.write( response );
                     iframe[0].contentWindow.document.close();
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
             });
             */

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
});
//--></script>
