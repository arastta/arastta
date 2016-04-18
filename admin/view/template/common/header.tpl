<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
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
    <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="screen" />
    <link href="view/javascript/bootstrap/arastta/arastta.css" type="text/css" rel="stylesheet" />
    <?php if (isset($show_menu) && $show_menu == 'right') { ?>
    <link href="view/stylesheet/right_menu.css" type="text/css" rel="stylesheet" />
    <?php } ?>
    <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
    <link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
    <link href="view/javascript/bootstrap-select/css/bootstrap-select.min.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="view/javascript/bootstrap-select/js/bootstrap-select.min.js"></script>
    <?php if ($bootstrap_select_lang) { ?>
    <script type="text/javascript" src="view/javascript/bootstrap-select/js/i18n/defaults-<?php echo $bootstrap_select_lang; ?>.min.js"></script>
    <?php } ?>
    <script type="text/javascript" src="view/javascript/bootstrap-notify/bootstrap-notify.min.js"></script>    
    <script src="view/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($styles as $style) { ?>
    <link type="text/css" href="<?php echo $style['href']; ?>" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php if ($style_declarations) { ?>
    <style type="text/css">
        <?php foreach ($style_declarations as $style) { ?>
        <?php echo $style; ?>
        <?php } ?>
    </style>
    <?php } ?>
    <script type="text/javascript" src="view/javascript/jquery/moment/moment.js" ></script>
    <script type="text/javascript" src="view/javascript/jquery/moment/locale/<?php echo $moment_lang; ?>.js" ></script>
    <script type="text/javascript" src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js"></script>
    <?php foreach ($scripts as $script) { ?>
    <script type="text/javascript" src="<?php echo $script; ?>"></script>
    <?php } ?>
    <?php if ($script_declarations) { ?>
    <script type="text/javascript">
        <?php foreach ($script_declarations as $script) { ?>
        <?php echo $script; ?>
        <?php } ?>
    </script>
    <?php } ?>
</head>
<body>
<div id="container">
    <?php if ($logged) { ?>
    <header id="header" class="navbar navbar-static-top">
        <div class="menu-logo">
            <div class="logo-image">
                <img height="20" src="view/image/logo.png" alt="Arastta" title="Arastta" />
            </div>
            <div class="menu-sitename"><a href="<?php echo $site_url; ?>" target="_blank"><?php echo $sitename; ?></a></div>
        </div>
        <div id="arastta-search-div" class="col-sm-3 col-md-3 pull-left">
            <?php echo $search; ?>
        </div>
        <ul class="nav pull-left">
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"  title="<?php echo $text_new; ?>"><i class="fa fa-plus fa-lg"></i> <span class="header-item"><?php echo $text_new; ?></span></a>
                <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
                    <li class="dropdown-header"><?php echo $text_new; ?></li>
                    <li><a href="<?php echo $new_product; ?>" style="display: block; overflow: auto;"><?php echo $text_new_product; ?></a></li>
                    <li><a href="<?php echo $new_category; ?>" style="display: block; overflow: auto;"><?php echo $text_new_category; ?></a></li>
                    <li><a href="<?php echo $new_manufacturer; ?>" style="display: block; overflow: auto;"><?php echo $text_new_manufacturer; ?></a></li>
                    <li><a href="<?php echo $new_customer; ?>" style="display: block; overflow: auto;"><?php echo $text_new_customer; ?></a></li>
                    <li><a href="<?php echo $new_download; ?>" style="display: block; overflow: auto;"><?php echo $text_new_download; ?></a></li>
                </ul>
            </li>
        </ul>
        <ul class="nav pull-right">
            <li class="dropdown hidden-xs"><a class="dropdown-toggle" data-toggle="dropdown"><?php if(!empty($alert_order)) { ?><span class="label label-danger pull-left"><?php echo $alert_order; ?></span><?php } ?><i class="fa fa-shopping-cart fa-lg"></i></a>
                <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
                    <li class="dropdown-header"><?php echo $text_order; ?></li>
                    <li><a href="<?php echo $order_status; ?>" style="display: block; overflow: auto;"><span class="label label-warning pull-right"><?php echo $order_status_total; ?></span><?php echo $text_order_status; ?></a></li>
                    <li><a href="<?php echo $complete_status; ?>"><span class="label label-success pull-right"><?php echo $complete_status_total; ?></span><?php echo $text_complete_status; ?></a></li>
                    <li><a href="<?php echo $return; ?>"><span class="label label-danger pull-right"><?php echo $return_total; ?></span><?php echo $text_return; ?></a></li>
                </ul>
            </li>
            <li class="dropdown hidden-xs"><a class="dropdown-toggle" data-toggle="dropdown"><?php if(!empty($alert_customer)) { ?><span class="label label-danger pull-left"><?php echo $alert_customer; ?></span><?php } ?><i class="fa fa-user fa-lg"></i></a>
                <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
                    <li class="dropdown-header"><?php echo $text_customer; ?></li>
                    <li><a href="<?php echo $online; ?>"><span class="label label-success pull-right"><?php echo $online_total; ?></span><?php echo $text_online; ?></a></li>
                    <li><a href="<?php echo $customer_approval; ?>"><span class="label label-danger pull-right"><?php echo $customer_total; ?></span><?php echo $text_approval; ?></a></li>
                </ul>
            </li>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><?php if(!empty($alert_product)) { ?><span class="label label-danger pull-left"><?php echo $alert_product; ?></span><?php } ?><i class="fa fa-bell fa-lg"></i></a>
                <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
                    <li class="dropdown-header"><?php echo $text_product; ?></li>
                    <li><a href="<?php echo $product; ?>"><span class="label label-danger pull-right"><?php echo $product_total; ?></span><?php echo $text_stock; ?></a></li>
                    <li><a href="<?php echo $review; ?>"><span class="label label-danger pull-right"><?php echo $review_total; ?></span><?php echo $text_review; ?></a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header"><?php echo $text_affiliate; ?></li>
                    <li><a href="<?php echo $affiliate_approval; ?>"><span class="label label-danger pull-right"><?php echo $affiliate_total; ?></span><?php echo $text_approval; ?></a></li>
                </ul>
            </li>
            <?php if ($languages) { ?>
            <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-flag fa-lg"></i></a>
                <ul class="dropdown-menu dropdown-menu-right alerts-dropdown">
                    <li class="dropdown-header"><?php echo $text_language; ?></li>
                    <?php foreach ($languages as $language) { ?>
                    <li><a href="<?php echo $language['link']; ?>"><span class="pull-right"><img src="../image/flags/<?php echo $language['image']; ?>" /></span><?php echo $language['name']; ?></a></li>
                    <?php } ?>
                </ul>
            </li>
            <?php } ?>
            <?php if ($preturn_update) { ?>
            <li><a href="<?php echo $update; ?>" title="<?php echo $alert_update . ' ' . $text_update; ?>"><?php if(!empty($alert_update)) { ?><span class="label label-danger pull-left"><?php echo $alert_update; ?></span><?php } ?><i class="fa fa-refresh fa-lg"></i></a></li>
            <?php } ?>
            <li class="dropdown hidden-xs"><a href="http://arastta.org/learn" target="_blank"><i class="fa fa-life-ring fa-lg"></i></a></li>

            <li class="basic-advanced">
                <?php if ($theme == 'basic') { ?>
                <input type="checkbox" name="checkbox1" id="checkbox1" class="ios-toggle" checked/>
                <?php } else { ?>
                <input type="checkbox" name="checkbox1" id="checkbox1" class="ios-toggle"/>
                <?php } ?>
                <label for="checkbox1" class="checkbox-label" data-off="off" data-on="on"></label>
                <?php if ($basic_mode_message == 'show') { ?>
                <div class="ba-message">
                    <span><?php echo $text_basic_mode; ?></span>
                    <div>
                        <button type="button" class="close" data-dismiss="modal" aria-label="<?php echo $text_close; ?>"><span aria-hidden="true"><i class="fa fa-times-circle"></i></span></button>
                    </div>
                </div>
                <?php } ?>
            </li>

            <li id="header-profile" class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown">
                    <img width="25" height="25" src="<?php echo $image; ?>" alt="<?php echo $name ?>" title="<?php echo $name; ?>" class="img-circle" />
                </a>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <div class="header-profile">
                            <h4><a href="<?php echo $url_user ?>"><?php echo $name; ?></a></h4>
                            <small><?php echo $user_group; ?></small>
                        </div>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
                    <li class="divider"></li>
                    <li class="dropdown-header"><?php echo $text_store; ?> <i class="fa fa-shopping-cart"></i></li>
                    <?php foreach ($stores as $store) { ?>
                    <li><a href="<?php echo $store['href']; ?>" target="_blank"><?php echo $store['name']; ?></a></li>
                    <?php } ?>
                    <li class="divider"></li>
                    <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                </ul>
            </li>
            <li id="settings-menu" class="settings">
                <a><i class="fa fa-cogs"></i></a>
            </li>
        </ul>
    </header>
    <?php } ?>
