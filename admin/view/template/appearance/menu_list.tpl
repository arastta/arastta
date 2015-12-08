<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
            </div>
            <div class="panel-body">
                <div class="col-sm-3">
                    <div class="heading-accordion"><span class="menu-item-add"><?php echo $text_new_menu_item; ?></span></div>
                    <div class="module_accordion accordion">
                        <div class="accordion-heading"><?php echo $text_custom; ?></div>
                        <div class="accordion-content accordion-content-drag addCustom">
                            <div class="input-group" id="addCustom">
                                <div class="input-group">
                                    <input type="text" name="custom_name" value="" placeholder="<?php echo $column_custom_name; ?>" id="input-custom-name" class="form-control input-full-width" />
                                </div>
                                <br/>
                                <div class="input-group">
                                    <input type="text" name="custom_url" value="" placeholder="<?php echo $column_custom_link; ?>" id="input-custom-link" class="form-control input-full-width" />
                                </div>
                                <br/>
                                <div class="pull-right">
                                    <button type="button" onclick="addMenu('custom');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_custom;?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-heading"><?php echo $text_category; ?></div>
                        <div class="accordion-content accordion-content-drag addCategory">
                            <div class="input-group" id="addCategory">
                                <input type="text" name="filter_category_name" value="" placeholder="<?php echo $column_category_name; ?>" id="input-category-name" class="form-control input-full-width" autocomplete="off" />
                                <input type="hidden" name="category_id" value="" id="category_id" class="form-control"/>
                                <div class="input-group-btn">
                                    <button type="button" onclick="addMenu('category');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_categories; ?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-heading"><?php echo $text_product; ?></div>
                        <div class="accordion-content accordion-content-drag addProduct">
                            <div class="input-group" id="addProduct">
                                <input type="text" name="filter_product_name" value="" placeholder="<?php echo $column_product_name; ?>" id="input-product-name" class="form-control input-full-width" autocomplete="off" />
                                <input type="hidden" name="product_id" value="" id="product_id" class="form-control"/>
                                <div class="input-group-btn">
                                    <button type="button" onclick="addMenu('product');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_products;?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-heading"><?php echo $text_manufacturer; ?></div>
                        <div class="accordion-content accordion-content-drag addManufacturer">
                            <div class="input-group" id="addManufacturer">
                                <input type="text" name="filter_manufacturer_name" value="" placeholder="<?php echo $column_manufacturer_name; ?>" id="input-manufacturer-name" class="form-control input-full-width" autocomplete="off" />
                                <input type="hidden" name="manufacturer_id" value="" id="manufacturer_id" class="form-control"/>
                                <div class="input-group-btn">
                                    <button type="button" onclick="addMenu('manufacturer');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_manufacturers; ?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-heading"><?php echo $text_information; ?></div>
                        <div class="accordion-content accordion-content-drag addInformation">
                            <div class="input-group" id="addInformation">
                                <input type="text" name="filter_information_name" value="" placeholder="<?php echo $column_information_name; ?>" id="input-information-name" class="form-control input-full-width" autocomplete="off" />
                                <input type="hidden" name="information_id" value="" id="information_id" class="form-control"/></td>
                                <div class="input-group-btn">
                                    <button type="button" onclick="addMenu('information');" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="<?php echo $button_informations; ?>">
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9" id="menu-management">
                    <form method="post" enctype="multipart/form-data" id="form-menu">
                        <div class="heading-accordion"><span class="menu-item-add"><?php echo $text_menu_title; ?></span></div>
                        <div class="menu-edit ">
                            <div id="post-body">
                                <div id="post-body-content">
                                    <div class="drag-instructions post-body-plain">
                                        <p><?php echo $text_menu_description; ?></p>
                                    </div>
                                    <ul class="menu" id="menu-to-edit">
                                        <?php $class_count = 1; $sub_menu = ''; $count = 0;?>
                                        <?php foreach($menus as $menu) { ?>
                                        <?php if($menu['isSubMenu'] && ($sub_menu == $menu['isSubMenu'] || empty($sub_menu))) {
                                        $class = 'menu-item-depth-' . $class_count;
                                        $sub_menu = $menu['isSubMenu'];
                                        $delteItem = 'child-';
                                      } else if($menu['isSubMenu']) {
                                        $class_count++;
                                        $sub_menu = $menu['isSubMenu'];
                                        $class = 'menu-item-depth-' . $class_count;
                                        $delteItem = 'child-';
                                      } else {
                                        $class_count = 1;
                                        $class = 'menu-item-depth-0';
                                        $delteItem = '';
                                        $sub_menu = '';
                                      }
                                    $count++;
                                      ?>
                                        <li id="menu-<?php echo $delteItem; ?>item-<?php echo $menu['menu_id']; ?>" class="menu-item <?php echo $class; ?> menu-item-page menu-item-edit-inactive pending">
                                            <dl class="menu-item-bar">
                                                <dt class="menu-item-handle">
                                                    <span class="item-title"><span class="menu-item-title"><?php echo $menu['name']; ?></span> <span class="is-submenu" <?php echo ($menu['isSubMenu']) ? '' : 'style="display: none;"'; ?> ><?php echo $text_sub_item; ?></span></span>
                                                <span class="item-controls">
                                                    <span class="item-type"><?php echo ucwords($menu['menu_type']);?></span>
                                                    <a class="item-edit openMenuItem  <?php echo $menu['menu_type'];?>" id="edit-<?php echo $delteItem . $menu['menu_id']; ?>" title="">
                                                        <i class="fa fa-caret-down"></i>
                                                    </a>
                                                </span>
                                                </dt>
                                            </dl>

                                            <div class="menu-item-settings" id="menu-item-settings-edit-<?php echo $delteItem; ?><?php echo $menu['menu_id']; ?>">
                                                <?php echo $text_menu_name;?><br>
                                                <?php foreach ($languages as $language) { ?>
                                                <?php if($menu['isSubMenu']) { ?>
                                                <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                                    <input type="text" name="menu_child_name[<?php echo $language['language_id']; ?>]" value="<?php echo isset($menu_child_desc[$menu['menu_id']][$language['language_id']]['name']) ? $menu_child_desc[$menu['menu_id']][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $text_menu_name; ?>" class="form-control input-full-width" />
                                                </div>
                                                <?php if (isset($error_group[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_group[$language['language_id']]; ?></div>
                                                <?php } ?>
                                                <?php } else { ?>
                                                <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                                    <input type="text" name="menu_name[<?php echo $language['language_id']; ?>]" value="<?php echo isset($menu_desc[$menu['menu_id']][$language['language_id']]['name']) ? $menu_desc[$menu['menu_id']][$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $text_menu_name; ?>" class="form-control input-full-width" />
                                                </div>
                                                <?php if (isset($error_group[$language['language_id']])) { ?>
                                                <div class="text-danger"><?php echo $error_group[$language['language_id']]; ?></div>
                                                <?php } ?>
                                                <?php } ?>
                                                <?php } ?>
                                                <br>
                                                <?php if ($menu['menu_type'] == 'custom') { ?>
                                                <?php echo $text_menu_link;?><br>
                                                <?php foreach ($languages as $language) { ?>
                                                <?php if($menu['isSubMenu']) { ?>
                                                <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                                    <input type="text" name="menu_child_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($menu_child_desc[$menu['menu_id']][$language['language_id']]['link']) ? $menu_child_desc[$menu['menu_id']][$language['language_id']]['link'] : ''; ?>" placeholder="<?php echo $text_menu_link; ?>" class="form-control input-full-width" />
                                                </div>
                                                <?php } else { ?>
                                                <div class="input-group"><span class="input-group-addon"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /></span>
                                                    <input type="text" name="menu_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($menu_desc[$menu['menu_id']][$language['language_id']]['link']) ? $menu_desc[$menu['menu_id']][$language['language_id']]['link'] : ''; ?>" placeholder="<?php echo $text_menu_link; ?>" class="form-control input-full-width" />
                                                </div>
                                                <?php } ?>
                                                <?php } ?>
                                                <br>
                                                <?php } else { ?>
                                                <?php foreach ($languages as $language) { ?>
                                                <?php if($menu['isSubMenu']) { ?>
                                                <input type="hidden" name="menu_child_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($menu_child_desc[$menu['menu_id']][$language['language_id']]['link']) ? $menu_child_desc[$menu['menu_id']][$language['language_id']]['link'] : ''; ?>" />
                                                <?php } else { ?>
                                                <input type="hidden" name="menu_link[<?php echo $language['language_id']; ?>]" value="<?php echo isset($menu_desc[$menu['menu_id']][$language['language_id']]['link']) ? $menu_desc[$menu['menu_id']][$language['language_id']]['link'] : ''; ?>" />
                                                <?php } ?>
                                                <?php } ?>
                                                <?php } ?>
                                                <?php if(empty($menu['isSubMenu'])) { ?>
                                                <?php echo $entry_columns; ?>
                                                <div class="input-group">
                                                    <input type="text" name="menu_columns" value="<?php echo isset($menu_desc[$menu['menu_id']][$language['language_id']]['columns']) ? $menu_desc[$menu['menu_id']][$language['language_id']]['columns'] : ''; ?>" placeholder="<?php echo $entry_columns; ?>" id="input-columns" class="form-control input-full-width" />
                                                </div>
                                                <br />
                                                <?php } ?>
                                                <?php if(count($stores) > 0) { ?>
                                                <?php echo $entry_store; ?>
                                                <br />
                                                <div class="well well-sm" style="height: 100%; max-height: 150px;  margin-right: 10px; overflow: auto;padding-right: 10px; margin-bottom: 5px;">
                                                    <div class="checkbox">
                                                        <label>
                                                            <?php if (in_array(0, $menu['store'])) { ?>
                                                            <input type="checkbox" name="menu_store[]" value="0" checked="checked" />
                                                            <?php echo $text_default; ?>
                                                            <?php } else { ?>
                                                            <input type="checkbox" name="menu_store[]" value="0" />
                                                            <?php echo $text_default; ?>
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                    <?php foreach ($stores as $store) { ?>
                                                    <div class="checkbox">
                                                        <label>
                                                            <?php if (in_array($store['store_id'], $menu['store'])) { ?>
                                                            <input type="checkbox" name="menu_store[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                                                            <?php echo $store['name']; ?>
                                                            <?php } else { ?>
                                                            <input type="checkbox" name="menu_store[]" value="<?php echo $store['store_id']; ?>" />
                                                            <?php echo $store['name']; ?>
                                                            <?php } ?>
                                                        </label>
                                                    </div>
                                                    <?php } ?>
                                                </div>
                                                <?php } ?>
                                                <div class="pull-right">
                                                    <?php if(empty($menu['status'])) { ?>
                                                    <a id="enableMenu-<?php echo $count; ?>" onclick="statusMenu('enable', '<?php echo $menu['menu_id']; ?>', 'menu-<?php echo $delteItem; ?>item-<?php echo $menu['menu_id']; ?>', 'enableMenu-<?php echo $count; ?>')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="<?php echo $button_enable; ?>" class="btn btn-success btn-xs btn-edit btn-group"><i class="fa fa-check-circle"></i></a>
                                                    <?php } else { ?>
                                                    <a id="disableMenu-<?php echo $count; ?>" onclick="statusMenu('disable', '<?php echo $menu['menu_id']; ?>', 'menu-<?php echo $delteItem; ?>item-<?php echo $menu['menu_id']; ?>', 'disableMenu-<?php echo $count; ?>')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="<?php echo $button_disable; ?>" class="btn btn-danger btn-xs btn-edit btn-group"><i class="fa fa-times-circle"></i></a>
                                                    <?php }?>
                                                    <a onclick="saveMenu('menu-item-settings-edit-<?php echo $delteItem; ?><?php echo $menu['menu_id']; ?>', 'menu-<?php echo $delteItem; ?>item-<?php echo $menu['menu_id']; ?>')" data-type="iframe" data-toggle="tooltip" style="top:2px!important;font-size:1.2em !important;" title="<?php echo $button_save; ?>" class="btn btn-success btn-xs btn-edit btn-group"><i class="fa fa-save"></i></a>
                                                    <button type="button" data-toggle="tooltip" title="" style="top:2px!important;font-size:1.2em !important;" class="btn btn-danger btn-xs btn-edit btn-group btn-loading" onclick="confirm('<?php echo $text_confirm; ?>') ? deleteMenu('<?php echo $menu['menu_id']; ?>', 'menu-<?php echo $delteItem; ?>item-<?php echo $menu['menu_id']; ?>') : false;" data-original-title="<?php echo $button_delete; ?>"><i class="fa fa-trash-o"></i></button>
                                                </div>
                                                <br>
                                                <br>
                                                <?php (!empty($menu['isSubMenu'])) ? $menuID = 'ChildMenu-' : $menuID = 'MainMenu-'; ?>
                                                <input class="menu-item-data-typeMenu" type="hidden" name="menu-item-typeMenu[<?php echo  $menuID . $menu['menu_id']; ?>]" value="<?php echo (!empty($menu['isSubMenu'])) ? 'ChildMenu' : 'MainMenu' ; ?>">
                                                <input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo  $menuID . $menu['menu_id']; ?>]" value="<?php echo $menu['menu_id']; ?>">
                                                <input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $menuID . $menu['menu_id']; ?>]" value="<?php echo ($menu['isSubMenu']) ? $menu['isSubMenu'] : '0' ; ?>">
                                                <input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $menuID . $menu['menu_id']; ?>]" value="<?php echo $menu['menu_id']; ?>">
                                                <input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $menuID . $menu['menu_id']; ?>]" value="post_type">
                                            </div>
                                            <ul class="menu-item-transport"></ul>
                                        </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var changeMenuPosition     = '<?php echo str_replace('amp;', '', $changeMenuPosition); ?>';

    var token                  = '<?php echo $token; ?>';
    var addMenuHref            = '<?php echo str_replace('amp;', '', $add);?>';

    var saveMenuHref           = '<?php echo str_replace('amp;', '', $save); ?>';

    var statusMenuEnable       = '<?php echo str_replace('amp;', '', $enableMenu); ?>';
    var statusMenuDisable      = '<?php echo str_replace('amp;', '', $disableMenu); ?>';

    var statusMenuChildEnable  = '<?php echo str_replace('amp;', '', $enableChildMenu); ?>';
    var statusMenuChildDisable = '<?php echo str_replace('amp;', '', $disableChildMenu); ?>';

    var deleteMenuHref         = '<?php echo str_replace('amp;', '', $deleteMenu); ?>';
    var deleteMenuChildHref    = '<?php echo str_replace('amp;', '', $deleteChildMenu); ?>';
</script>
<?php echo $footer; ?>
