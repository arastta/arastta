<?php 

// Set logo side areas
$logo_left_area = 4;
$logo_right_area = 4;
if ($theme_config->get('logo_width') == 12) {
    $logo_left_area == 6;
    $logo_right_area == 6;
}elseif ($theme_config->get('logo_width') == 3) {
    $logo_left_area == 4;
    $logo_right_area == 5;
}elseif ($theme_config->get('logo_width') == 4) {
    $logo_left_area == 4;
    $logo_right_area == 4;
}elseif ($theme_config->get('logo_width') == 6) {
    $logo_left_area == 3;
    $logo_right_area == 3;
}elseif ($theme_config->get('logo_width') == 8) {
    $logo_left_area == 2;
    $logo_right_area == 2;
}
?>
<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $title; ?></title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content= "<?php echo $keywords; ?>" />
    <?php } ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php if ($icon) { ?>
    <link href="<?php echo $icon; ?>" rel="icon" />
    <?php } ?>
    <?php foreach ($metas as $meta) { ?>
    <meta name="<?php echo $meta['name']; ?>" content="<?php echo $meta['content']; ?>" />
    <?php } ?>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css?family=PT+Serif:400,700" rel="stylesheet" type="text/css">
    <link href="catalog/view/theme/second/stylesheet/stylesheet.css" rel="stylesheet">
    <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <?php if ($style_declarations) { ?>
    <style type="text/css">
        <?php foreach ($style_declarations as $style) { ?>
        <?php echo $style; ?>
        <?php } ?>
    </style>
    <?php } ?>   
    <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
    <script src="catalog/view/javascript/arastta.js" type="text/javascript"></script>
    <script src="catalog/view/theme/second/javascript/script.js" type="text/javascript"></script>
    <?php foreach ($scripts as $script) { ?>
    <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <?php if ($script_declarations) { ?>
    <script type="text/javascript">
        <?php foreach ($script_declarations as $script) { ?>
        <?php echo $script; ?>
        <?php } ?>
    </script>
    <?php } ?>
    <?php echo $google_analytics; ?>
</head>
<body class="<?php echo $class; ?>">
  
<nav id="top">
    <div class="container">
        <?php echo $currency; ?>
        <?php echo $language; ?>
        <div id="top-links" class="nav pull-right">
            <ul class="list-inline">
                <li><a href="<?php echo $contact; ?>"><i class="fa fa-phone"></i></a> <span class="hidden-xs hidden-sm hidden-md"><?php echo $telephone; ?></span></li>
                <li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <?php if ($logged) { ?>
                        <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
                        <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
                        <li><a href="<?php echo $credit; ?>"><?php echo $text_credit; ?></a></li>
                        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                        <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
                        <?php } else { ?>
                        <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
                        <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
                <li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
                <li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
            </ul>
        </div>
    </div>
</nav>
<header>
    <div class="container">
        <div class="row">
            <div class="col-sm-<?php echo $logo_left_area; ?> hidden-xs">
                <div class="text-muted social-links"><?php if($theme_config->get('facebook_url', 'https://facebook.com/#')) : ?><a href="<?php echo $theme_config->get('facebook_url', 'https://facebook.com/#'); ?>" target="_blank" class="facebook"><span class="fa fa-2x fa-fw fa-facebook"></span></a><?php endif; ?> <?php if($theme_config->get('twitter_url', 'https://twitter.com/#')) : ?><a href="<?php echo $theme_config->get('twitter_url', 'https://twitter.com/#'); ?>" target="_blank" class="twitter"><span class="fa fa-2x fa-fw fa-twitter"></span></a><?php endif; ?> <?php if($theme_config->get('pinterest_url', 'https://pinterest.com/#')) : ?><a href="<?php echo $theme_config->get('pinterest_url', 'https://pinterest.com/#'); ?>" target="_blank" class="pinterest"><span class="fa fa-2x fa-fw fa-pinterest"></span></a><?php endif; ?> <?php if($theme_config->get('instagram_url', 'https://instagram.com/#')) : ?><a href="<?php echo $theme_config->get('instagram_url', 'https://instagram.com/#'); ?>" target="_blank" class="instagram"><span class="fa fa-2x fa-fw fa-instagram"></span></a><?php endif; ?></div>
            </div>
            <div class="col-sm-<?php echo $theme_config->get('logo_width', 4); ?>">
                <div id="logo" class="text-center">
                    <?php if ($logo) { ?>
                    <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
                    <?php } else { ?>
                    <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
                    <?php } ?>
                </div>
            </div>
            <div class="col-sm-<?php echo $logo_right_area; ?>"><?php echo $cart; ?></div>
        </div>
    </div>
</header>
<?php if ($categories) { ?>
<nav id="menu" class="navbar">
    <div class="container">
        <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
            <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
        </div>
        <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
                <?php foreach ($categories as $category) { ?>
                <?php if ($category['children']) { ?>
                <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
                    <div class="dropdown-menu">
                        <div class="dropdown-inner">
                            <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                            <ul class="list-unstyled">
                                <?php foreach ($children as $child) { ?>
                                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                                <?php } ?>
                            </ul>
                            <?php } ?>
                        </div>
                        <?php if ($category['href'] != '#') { ?>
                        <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a>
                        <?php } ?>
                    </div>
                </li>
                <?php } else { ?>
                <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                <?php } ?>
                <?php } ?>
            </ul>
                <div class="search-menu pull-right text-right">
                    <a class="btn btn-search-menu" data-toggle="modal" data-target="#searchmenu"><span class="fa fa-search"></span></a>
                </div>
        </div>
    </div>
</nav>
<?php } ?>
<!-- Modal -->
<div class="modal fade" id="searchmenu" tabindex="-1" role="dialog" aria-labelledby="Search">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <?php echo $search; ?>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.modal').on('shown.bs.modal', function () {
  $(this).find('input:text:visible:first').focus();
})
//--></script>
