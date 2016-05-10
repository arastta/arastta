<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-layout" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save" id="layout-save"><i class="fa fa-check"></i></button>
                <a href="<?php echo  $extension_module; ?>" data-toggle="tooltip" title="<?php echo $button_module; ?>" class="btn btn-default" data-original-title="<?php echo $button_module; ?>"><i class="fa fa-cubes"></i></a>
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
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
                    <div class="row layout-builder" id="layout-builder">
                        <div class="col-md-3 col-sm-4 hidden-xs">
                            
                            <div class="layout">
                                <?php if ($stores) { ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span class="text-muted"><?php echo $text_theme; ?></span>
                                        <span>
                                        <select name="store" id="store" class="form-control">
                                            <?php if ($store_id == $store['store_id']) { ?>
                                            <option value="0" selected="selected"><?php echo $store['store_name'];?></option>
                                            <?php } else { ?>
                                            <option value="0"><?php echo $store['store_name'];?></option>
                                            <?php } ?>
                                            <?php foreach ($stores as $store) { ?>
                                            <?php if ($store_id == $store['store_id']) { ?>
                                            <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                        </span>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-sm-12 layout-list">
                                        <select type="text" name="change_layouts" id="change_layouts" class="form-control with-nav">
                                            <option value="0"><?php echo $entry_addnew;?></option>
                                            <?php foreach($layouts as $layout) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" <?php if( $change_layouts == $layout['layout_id'] ) { echo "selected=selected"; $name = $layout['name']; } ?>><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 layout-buttons">
                                        <a class="btn btn-default edit-layout" href="<?php echo $edit . '&layout_id=' . $layout_id ; ?>" data-toggle="tooltip" title="<?php echo $text_edit;?>"><i class="fa fa-pencil"></i></a>
                                        <a class="btn btn-primary add-layout" href="<?php echo $add ; ?>" data-toggle="tooltip" title="<?php echo $text_add;?>"><i class="fa fa-plus"></i></a>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="confirm('Are you sure?') ? removeLayout('<?php echo $layout_id; ?>') : false;" data-original-title="Layout Delete"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                            </div>

                            <div class="left-module-block">
                                <div id="module_list" class="module_list" data-text-confirm="<?php echo $text_confirm;?>" data-text-edit="<?php echo $text_edit;?>">
                                    <div class="heading-accordion">&nbsp;<?php echo $text_module; ?></div>
                                    <div class="module_accordion accordion">
                                        <?php echo $module_list_html; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 visible-xs" style="margin-bottom: 130px;">
                            <div class="layout">
                                <?php if ($stores) { ?>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <span class="text-muted"><?php echo $text_theme; ?></span>
                                        <span>
                                        <select name="store" id="store" class="form-control">
                                            <?php if ($store_id == $store['store_id']) { ?>
                                            <option value="0" selected="selected"><?php echo $store['store_name'];?></option>
                                            <?php } else { ?>
                                            <option value="0"><?php echo $store['store_name'];?></option>
                                            <?php } ?>
                                            <?php foreach ($stores as $store) { ?>
                                            <?php if ($store_id == $store['store_id']) { ?>
                                            <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                        </span>
                                    </div>
                                </div>
                                <?php } ?>
                                <div class="row">
                                    <div class="col-sm-12 layout-list">
                                        <select type="text" name="change_layouts" id="change_layouts" class="form-control with-nav">
                                            <option value="0"><?php echo $entry_addnew;?></option>
                                            <?php foreach($layouts as $layout) { ?>
                                            <option value="<?php echo $layout['layout_id']; ?>" <?php if( $change_layouts == $layout['layout_id'] ) { echo "selected=selected"; $name = $layout['name']; } ?>><?php echo $layout['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-12 layout-buttons">
                                        <a class="btn btn-default edit-layout" href="<?php echo $edit . '&layout_id=' . $layout_id ; ?>" data-toggle="tooltip" title="<?php echo $text_edit;?>"><i class="fa fa-pencil"></i></a>
                                        <a class="btn btn-primary add-layout" href="<?php echo $add ; ?>" data-toggle="tooltip" title="<?php echo $text_add;?>"><i class="fa fa-plus"></i></a>
                                        <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="confirm('Are you sure?') ? removeLayout('<?php echo $layout_id; ?>') : false;" data-original-title="Layout Delete"><i class="fa fa-trash-o"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9 col-sm-8" style="top: 5px;">
                            <div class="accordion-content-drop">
                                <div class="container-fluid">
                                    <?php $module_id = 0; ?>
                                    <?php if (!empty($positions['header_top'])) { ?>
                                    <div class="row colsliders">
                                        <div class="col-md-12 position">
                                            <div class="layout-page-header"><span><?php echo $text_header_top;?></span></div>
                                            <?php foreach($positions['header_top'] as $header_top) { ?>
                                            <h5>
                                                <?php
                                                    $text = 'text_' . $header_top;
                                                    echo !empty($$text) ? $$text : $header_top;
                                                ?>
                                            </h5>
                                            <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="<?php echo $header_top; ?>">
                                                <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$header_top])) { ?>
                                                <?php foreach($layouts[$layout_id]['modules'][$header_top] as $header_top_position) { ?>
                                                <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $header_top_position['code']; ?>" style="display: block;">
                                                    <div class="mblock-header">
                                                        <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $header_top_position['name']; ?></div>
                                                    </div>
                                                    <div class="mblock-control-menu ui-sortable-handle">
                                                        <div class="mblock-action">
                                                            <a class="btn btn-xs btn-edit" href="<?php echo $header_top_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                        </div>
                                                        <div class="mblock-action pull-right">
                                                            <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $header_top_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $header_top_position['code']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $header_top_position['position']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $header_top_position['sort_order']; ?>" class="sort">
                                                    <?php $module_id++; ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row colsliders">
                                        <div class="col-md-12 position empty">
                                            <div class="layout-page-header layout-page-header-bg"><span><?php echo $text_header;?></span></div>
                                        </div>
                                    </div>
                                    <?php if (!empty($positions['top'])) { ?>
                                    <div class="row colsliders">
                                        <div class="col-md-12 position">
                                            <div class="layout-page-header"><span><?php echo $text_top;?></span></div>
                                            <?php foreach($positions['top'] as $top) { ?>
                                            <h5>
                                                <?php
                                                    $text = 'text_' . $top;
                                                    echo !empty($$text) ? $$text : $top;
                                                ?>
                                            </h5>
                                            <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="<?php echo $top; ?>">
                                                <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$top])) { ?>
                                                <?php foreach($layouts[$layout_id]['modules'][$top] as $top_position) { ?>
                                                <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $top_position['code']; ?>" style="display: block;">
                                                    <div class="mblock-header">
                                                        <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $top_position['name']; ?></div>
                                                    </div>
                                                    <div class="mblock-control-menu ui-sortable-handle">
                                                        <div class="mblock-action">
                                                            <a class="btn btn-xs btn-edit" href="<?php echo $top_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                        </div>
                                                        <div class="mblock-action pull-right">
                                                            <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $top_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $top_position['code']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $top_position['position']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $top_position['sort_order']; ?>" class="sort">
                                                    <?php $module_id++; ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row colsliders">
                                        <?php if (!empty($positions['left'])) { ?>
                                        <div class="col-md-3 position sidebar_column">
                                            <div class="layout-page-header"><span><?php echo $text_left; ?></span>
                                                <div class="btn-group hidden-lg hidden-md">
                                                    <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=column_left&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <?php foreach($positions['left'] as $left) { ?>
                                            <h5>
                                                <?php
                                                    $text = 'text_' . $left;
                                                    echo !empty($$text) ? $$text : $left;
                                                ?>
                                            </h5>
                                            <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="<?php echo $left; ?>">
                                                <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$left])) { ?>
                                                <?php foreach($layouts[$layout_id]['modules'][$left] as $left_position) { ?>
                                                <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $left_position['code']; ?>" style="display: block;">
                                                    <div class="mblock-header">
                                                        <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $left_position['name']; ?></div>
                                                    </div>
                                                    <div class="mblock-control-menu ui-sortable-handle">
                                                        <div class="mblock-action">
                                                            <a class="btn btn-xs btn-edit" href="<?php echo $left_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                        </div>
                                                        <div class="mblock-action pull-right">
                                                            <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $left_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $left_position['code']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $left_position['position']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $left_position['sort_order']; ?>" class="sort">
                                                    <?php $module_id++; ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <?php } ?>
                                        <?php if (!empty($positions['main_top']) || !empty($positions['main_bottom'])) { ?>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <?php if (!empty($positions['main_top'])) { ?>
                                                <div class="col-md-12 position main_column">
                                                    <div class="layout-page-header"><span><?php echo $text_main_top; ?></span>
                                                        <div class="btn-group hidden-lg hidden-md">
                                                            <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=content_top&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                                                        </div>
                                                    </div>
                                                    <?php foreach($positions['main_top'] as $main_top) { ?>
                                                    <h5>
                                                        <?php
                                                            $text = 'text_' . $main_top;
                                                            echo !empty($$text) ? $$text : $main_top;
                                                        ?>
                                                    </h5>
                                                    <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="<?php echo $main_top; ?>">
                                                        <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                        <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$main_top])) { ?>
                                                        <?php foreach($layouts[$layout_id]['modules'][$main_top] as $main_top_position) { ?>
                                                        <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $main_top_position['code']; ?>" style="display: block;">
                                                            <div class="mblock-header">
                                                                <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $main_top_position['name']; ?></div>
                                                            </div>
                                                            <div class="mblock-control-menu ui-sortable-handle">
                                                                <div class="mblock-action">
                                                                    <a class="btn btn-xs btn-edit" href="<?php echo $main_top_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                                </div>
                                                                <div class="mblock-action pull-right">
                                                                    <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $main_top_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $main_top_position['code']; ?>">
                                                            <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $main_top_position['position']; ?>">
                                                            <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $main_top_position['sort_order']; ?>" class="sort">
                                                            <?php $module_id++; ?>
                                                        </div>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>
                                                <?php if (!empty($positions['main_bottom'])) { ?>
                                                <div class="col-md-12 position main_column">
                                                    <div class="layout-page-header"><span><?php echo $text_main_bottom; ?></span>
                                                        <div class="btn-group hidden-lg hidden-md">
                                                            <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=content_bottom&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                                                        </div>
                                                    </div>
                                                    <?php foreach($positions['main_bottom'] as $main_bottom) { ?>
                                                    <h5>
                                                        <?php
                                                            $text = 'text_' . $main_bottom;
                                                            echo !empty($$text) ? $$text : $main_bottom;
                                                        ?>
                                                    </h5>
                                                    <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="<?php echo $main_bottom; ?>">
                                                        <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                        <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$main_bottom])) { ?>
                                                        <?php foreach($layouts[$layout_id]['modules'][$main_bottom] as $main_bottom_position) { ?>
                                                        <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $main_bottom_position['code']; ?>" style="display: block;">
                                                            <div class="mblock-header">
                                                                <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $main_bottom_position['name']; ?></div>
                                                            </div>
                                                            <div class="mblock-control-menu ui-sortable-handle">
                                                                <div class="mblock-action">
                                                                    <a class="btn btn-xs btn-edit" href="<?php echo $main_bottom_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                                </div>
                                                                <div class="mblock-action pull-right">
                                                                    <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $main_bottom_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $main_bottom_position['code']; ?>">
                                                            <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $main_bottom_position['position']; ?>">
                                                            <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $main_bottom_position['sort_order']; ?>" class="sort">
                                                            <?php $module_id++; ?>
                                                        </div>
                                                        <?php } ?>
                                                        <?php } ?>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php } ?>
                                        <?php if (!empty($positions['right'])) { ?>
                                        <div class="col-md-3 position sidebar_column">
                                            <div class="layout-page-header"><span><?php echo $text_right; ?></span>
                                                <div class="btn-group hidden-lg hidden-md">
                                                    <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=column_right&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                                                </div>
                                            </div>
                                            <?php foreach($positions['right'] as $right) { ?>
                                            <h5>
                                                <?php
                                                    $text = 'text_' . $right;
                                                    echo !empty($$text) ? $$text : $right;
                                                ?>
                                            </h5>
                                            <div class="dashed ui-droppable ui-sortable" data-position="<?php echo $right; ?>">
                                                <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$right])) { ?>
                                                <?php foreach($layouts[$layout_id]['modules'][$right] as $right_position) { ?>
                                                <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $right_position['code']; ?>" style="display: block;">
                                                    <div class="mblock-header">
                                                        <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $right_position['name']; ?></div>
                                                    </div>
                                                    <div class="mblock-control-menu ui-sortable-handle">
                                                        <div class="mblock-action">
                                                            <a class="btn btn-xs btn-edit" href="<?php echo $right_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                        </div>
                                                        <div class="mblock-action pull-right">
                                                            <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $right_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $right_position['code']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $right_position['position']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $right_position['sort_order']; ?>" class="sort">
                                                    <?php $module_id++; ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if (!empty($positions['bottom'])) { ?>
                                    <div class="row colsliders">
                                        <div class="col-md-12 position">
                                            <div class="layout-page-header"><span><?php echo $text_bottom;?></span></div>
                                            <?php foreach($positions['bottom'] as $bottom) { ?>
                                            <h5>
                                                <?php
                                                    $text = 'text_' . $bottom;
                                                    echo !empty($$text) ? $$text : $bottom;
                                                ?>
                                            </h5>
                                            <div class="dashed ui-droppable ui-sortable" data-position="<?php echo $bottom; ?>">
                                                <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$bottom])) { ?>
                                                <?php foreach($layouts[$layout_id]['modules'][$bottom] as $bottom_position) { ?>
                                                <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $bottom_position['code']; ?>" style="display: block;">
                                                    <div class="mblock-header">
                                                        <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $bottom_position['name']; ?></div>
                                                    </div>
                                                    <div class="mblock-control-menu ui-sortable-handle">
                                                        <div class="mblock-action">
                                                            <a class="btn btn-xs btn-edit" href="<?php echo $bottom_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                        </div>
                                                        <div class="mblock-action pull-right">
                                                            <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $bottom_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $bottom_position['code']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $bottom_position['position']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $bottom_position['sort_order']; ?>" class="sort">
                                                    <?php $module_id++; ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row colsliders">
                                        <div class="col-md-12 position empty">
                                            <div class="layout-page-header layout-page-header-bg"><span><?php echo $text_footer; ?></span></div>
                                        </div>
                                    </div>
                                    <?php if (!empty($positions['footer_bottom'])) { ?>
                                    <div class="row colsliders">
                                        <div class="col-md-12 position">
                                            <div class="layout-page-header"><span><?php echo $text_footer_bottom;?></span></div>
                                            <?php foreach($positions['footer_bottom'] as $footer_bottom) { ?>
                                            <h5>
                                                <?php
                                                    $text = 'text_' . $footer_bottom;
                                                    echo !empty($$text) ? $$text : $footer_bottom;
                                                ?>
                                            </h5>
                                            <div class="dashed ui-droppable ui-sortable" data-position="<?php echo $footer_bottom; ?>">
                                                <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                                                <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules'][$footer_bottom])) { ?>
                                                <?php foreach($layouts[$layout_id]['modules'][$footer_bottom] as $footer_bottom_position) { ?>
                                                <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $footer_bottom_position['code']; ?>" style="display: block;">
                                                    <div class="mblock-header">
                                                        <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $footer_bottom_position['name']; ?></div>
                                                    </div>
                                                    <div class="mblock-control-menu ui-sortable-handle">
                                                        <div class="mblock-action">
                                                            <a class="btn btn-xs btn-edit" href="<?php echo $footer_bottom_position['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                                        </div>
                                                        <div class="mblock-action pull-right">
                                                            <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $footer_bottom_position['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $footer_bottom_position['code']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $footer_bottom_position['position']; ?>">
                                                    <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $footer_bottom_position['sort_order']; ?>" class="sort">
                                                    <?php $module_id++; ?>
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                            </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="data_index" data-index="<?php echo $module_id;?>"></div>

                    <input type="hidden" name="layout_id" value="<?php echo $layout_id; ?>">
                    <input type="hidden" name="name" value="<?php echo !empty($name)? $name : ''; ?>">
                </form>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        Layout.init();

        $("#store").change(function() {
            redirectURL('index.php?route=appearance/layout&token=<?php echo $token;?>&store_id=' + $(this).val());
        });

        $("#change_layouts").change(function() {
            redirectURL('index.php?route=appearance/layout&token=<?php echo $token;?>&layout_id=' + $(this).val());
        });

        var moduleListHeigth = $('.row.colsliders .col-md-6').height() - 12;

        var moduleCol = moduleListHeigth - 100;

        $('.row.colsliders .col-md-3.sidebar_column').attr('style','min-height:' + moduleListHeigth + 'px !important;');
        $('.row.colsliders .col-md-3.sidebar_column .dashed').attr('style','min-height:' + moduleCol + 'px !important;');
        //$('.col-md-6 .dashed').attr('style','min-height:' + moduleCol + 'px !important;');

        $('.btn-res-edit').on('click', function(event) {
            event.preventDefault();
            var data_href = $(this).attr('href');
            $('#res-model-large').attr('src',data_href);
            $('#res-module-modal').modal('show');
        });

        $('#res-model-large').on('load', function(event) {
            event.preventDefault();
            var iframe = $('#res-model-large');
            var current_url = document.getElementById("res-model-large").contentWindow.location.href;

            if(current_url != 'about:blank'){
                $('#res-module-modal-loading').addClass('loading_iframe');

                iframe.contents().find('.page-header [href]').on('click', function(event) {
                    $('#res-module-modal-loading').addClass('loading_iframe');
                });

                iframe.contents().find('form').on('submit', function(event) {
                    $('#res-module-modal-loading').addClass('loading_iframe');
                });

                if (current_url.indexOf('position') < 0) {
                    $('#res-module-modal').modal('hide');
                    $('body').removeClass('modal-open');

                    redirectURL('index.php?route=appearance/layout&token=<?php echo $token;?>&layout_id=<?php echo $layout_id; ?>');
                } else {
                    iframe.contents().find('html,body').css({
                        height: 'auto'
                    });

                    iframe.contents().find('#header, #content .page-header .breadcrumb, #column-left, #column-right, #footer').hide();
                    iframe.contents().find('#content').css({marginLeft: '0px'});
                    iframe.contents().find('#content').css({padding: '10px 0 0 0'});

                    $('#res-module-modal-loading').removeClass('loading_iframe');
                }
            }
        });
    });

    function removeModule(module_id, id) {
        $.ajax({
            url: 'index.php?route=appearance/layout/removeModule&token=<?php echo $token;?>',
            type: 'post',
            data: {module_id: module_id},
            dataType: 'json',
            cache: false,
            success: function(json) {
                if (json['success'] == 1) {
                    $('#' + id).remove();
                } else {
                    alert(json['error_warning']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function removeLayoutModule(layout_id, layout_module_id, module) {
        $.ajax({
            url: 'index.php?route=appearance/layout/removeLayoutModule&token=<?php echo $token;?>',
            type: 'post',
            data: {layout_id: layout_id, layout_module_id: layout_module_id},
            dataType: 'json',
            cache: false,
            success: function(json) {
                if (json['success'] == 1) {
                    module.parents('.mblock').remove();
                } else {
                    alert(json['error_warning']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function removeLayout(layout_id) {
        $.ajax({
            url: 'index.php?route=appearance/layout/removeLayout&token=<?php echo $token;?>',
            type: 'post',
            data: {layout_id: layout_id},
            dataType: 'json',
            cache: false,
            success: function(json) {
                if (json['success'] == 1) {
                    redirectURL('index.php?route=appearance/layout&token=<?php echo $token;?>');
                } else {
                    alert(json['error_warning']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function save(type) {
        var input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'button';
        input.value = type;
        form = $("form[id^='form-']").append(input);
        form.submit();
    }

    var action_url = '<?php echo str_replace("&amp;", "&", $action); ?>';
</script>
<div id="layout-add" class="modal-box modal fade">
    <div class="modal-dialog iframe-width">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="layout-add-iframe" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
            </div>
            <div id="layout-add-loading"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
            </div>
        </div>
    </div>
</div>
<div id="layout-edit" class="modal-box modal fade">
    <div class="modal-dialog iframe-width">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="layout-edit-iframe" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
            </div>
            <div id="layout-edit-loading"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
            </div>
        </div>
    </div>
</div>
<div id="module-modal" class="modal-box modal fade">
    <div class="modal-dialog iframe-width">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="model-large" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
            </div>
            <div id="module-modal-loading"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
            </div>
        </div>
    </div>
</div>
<div id="res-module-modal" class="modal-box modal fade">
    <div class="modal-dialog iframe-width">
        <div class="modal-content">
            <div class="modal-body">
                <iframe id="res-model-large" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
            </div>
            <div id="res-module-modal-loading"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
