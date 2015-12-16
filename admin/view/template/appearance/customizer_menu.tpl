<ul id="customize-all-content">
    <?php foreach($sections as $section_name => $section_value){ ?>
    <li id="accordion-section-<?php echo $section_name; ?>" class="ds_heading accordion-section control-section control-section-default" style="">
        <h3 class="accordion-section-title" tabindex="0">
            <?php echo $section_value['title']; ?>
            <span class="screen-reader-text"><?php echo $text_menu_description;?></span>
        </h3>
        <ul class="accordion-section-content ds_content" style="">
            <li class="customizer-section-description-container">
                <p class="description customizer-section-description"><?php echo $section_value['description']; ?></p>
            </li>
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
                         if(!empty($default_data[$control_name])) {
                            $selected = "selected=selected" ;
                         } else if (!empty($control_value['default'])) {
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
<script type="text/javascript">
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
</script>
