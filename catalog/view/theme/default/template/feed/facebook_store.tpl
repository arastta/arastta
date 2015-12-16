<?php if ($not_activate) { ?>
<?php echo $message; ?>
<?php } else { ?>
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
    <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
    <link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
    <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
    <?php echo $google_analytics; ?>
</head>
<body id="facebook-feed-body">
<nav id="top" class="col-sm-12">
    <div class="container">
        <?php if (count($currencies) > 1 && $show_header_currency) { ?>
        <div class="pull-left">
            <form action="<?php echo $action_currency; ?>" method="post" enctype="multipart/form-data" id="facebook-currency">
                <div class="btn-group">
                    <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                        <?php foreach ($currencies as $currency) { ?>
                        <?php if ($currency['symbol_left'] && $currency['code'] == $currency_code) { ?>
                        <strong><?php echo $currency['symbol_left']; ?></strong>
                        <?php } elseif ($currency['symbol_right'] && $currency['code'] == $currency_code) { ?>
                        <strong><?php echo $currency['symbol_right']; ?></strong>
                        <?php } ?>
                        <?php } ?>
                        <span><?php echo $text_currency; ?></span> <i class="fa fa-caret-down"></i></button>
                    <ul class="dropdown-menu">
                        <?php foreach ($currencies as $currency) { ?>
                        <?php if ($currency['symbol_left']) { ?>
                        <li><button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_left']; ?> <?php echo $currency['title']; ?></button></li>
                        <?php } else { ?>
                        <li><button class="currency-select btn btn-link btn-block" type="button" name="<?php echo $currency['code']; ?>"><?php echo $currency['symbol_right']; ?> <?php echo $currency['title']; ?></button></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                </div>
                <input type="hidden" name="code" value="" />
                <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            </form>
        </div>
        <?php } ?>

        <?php if (count($languages) > 1 && $show_header_currency) { ?>
        <div class="pull-left">
            <form action="<?php echo $action_language; ?>" method="post" enctype="multipart/form-data" id="facebook-language">
                <div class="btn-group">
                    <button class="btn btn-link dropdown-toggle" data-toggle="dropdown">
                        <?php foreach ($languages as $language) { ?>
                        <?php if ($language['code'] == $language_code) { ?>
                        <img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>">
                        <?php } ?>
                        <?php } ?>
                        <span><?php echo $text_language; ?></span> <i class="fa fa-caret-down"></i></button>
                    <ul class="dropdown-menu">
                        <?php foreach ($languages as $language) { ?>
                        <li><a href="<?php echo $language['code']; ?>"><img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <input type="hidden" name="code" value="" />
                <input type="hidden" name="redirect" value="<?php echo $redirect_language; ?>" />
            </form>
        </div>
        <?php } ?>

    </div>
</nav>
<header>
    <div>
        <div class="row">
            <?php
      if (empty($show_header_category) && $show_header_search) {
        $class_search   = 'col-sm-11';
        $class_category = 'col-sm-0';
      } else if (empty($show_header_search) && $show_header_category) {
        $class_search   = 'col-sm-0';
        $class_category = 'col-sm-11';
      } else if ($show_header_category && $show_header_search) {
        $class_search   = 'col-sm-6';
        $class_category = 'col-sm-5';
      }
      ?>
            <div class="col-sm-1">
                <a href="<?php echo $home; ?>" class="btn btn-default btn-lg" style="height: 45px; width: 50px;"><i class="fa fa-home"></i></a>
            </div>
            <?php  if ($show_header_category) { ?>
            <div class="<?php echo $class_category; ?>">
                <select name="category_id" id="category-search" class="form-control">
                    <option value="0"><?php echo $text_category; ?></option>
                    <?php foreach ($categories as $category_1) { ?>
                    <?php if ($category_1['category_id'] == $filter_category_id) { ?>
                    <option value="<?php echo $category_1['category_id']; ?>" selected="selected"><?php echo $category_1['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $category_1['category_id']; ?>"><?php echo $category_1['name']; ?></option>
                    <?php } ?>
                    <?php foreach ($category_1['children'] as $category_2) { ?>
                    <?php if ($category_2['category_id'] == $filter_category_id) { ?>
                    <option value="<?php echo $category_2['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $category_2['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
                    <?php } ?>
                    <?php foreach ($category_2['children'] as $category_3) { ?>
                    <?php if ($category_3['category_id'] == $filter_category_id) { ?>
                    <option value="<?php echo $category_3['category_id']; ?>" selected="selected">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $category_3['category_id']; ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                    <?php } ?>
                </select>
            </div>
            <?php } ?>
            <?php if ($show_header_search) { ?>
            <div class="<?php echo $class_search; ?>">
                <div id="search" class="input-group facebook-search">
                    <input type="text" name="search" value="" placeholder="<?php echo $text_search ;?>" class="form-control input-lg" autocomplete="off"><ul class="dropdown-menu" style="padding:2px 2px 2px 2px;"></ul>
          <span class="input-group-btn">
            <a id="search-button" class="btn btn-default btn-lg"><i class="fa fa-search"></i></a>
          </span>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</header>
<div>
    <div class="row">
        <div id="content" class="col-sm-12">
            <?php if ($products) { ?>
            <div class="row hidden">
                <div class="col-sm-4">
                    <div class="btn-group hidden-xs" style="display: none">
                        <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
                        <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
                    </div>
                </div>
                <div class="col-sm-2 text-right">
                    <label class="control-label" for="input-sort"><?php echo $text_sort; ?></label>
                </div>
                <div class="col-sm-3 text-right">
                    <select id="input-sort" class="form-control" onchange="location = this.value;">
                        <?php foreach ($sorts as $sorts) { ?>
                        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-sm-1 text-right">
                    <label class="control-label" for="input-limit"><?php echo $text_limit; ?></label>
                </div>
                <div class="col-sm-2 text-right">
                    <select id="input-limit" class="form-control" onchange="location = this.value;">
                        <?php foreach ($limits as $limits) { ?>
                        <?php if ($limits['value'] == $limit) { ?>
                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row" id="facebook-products">
                <?php foreach ($products as $product) { ?>
                <div class="product-facebook-layout col-sm-3">
                    <div class="product-thumb">
                        <div class="image"><a href="<?php echo $product['href']; ?>" target="_blank"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                        <div>
                            <div class="caption">
                                <h4><a href="<?php echo $product['href']; ?>" target="_blank"><?php echo $product['name']; ?></a></h4>
                                <?php if ($show_product_description) { ?>
                                <p><?php echo $product['description']; ?></p>
                                <?php } ?>
                                <?php if ($show_product_rating) { ?>
                                <?php if ($product['rating']) { ?>
                                <div class="rating">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                    <?php if ($product['rating'] < $i) { ?>
                                    <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                                    <?php } else { ?>
                                    <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                                    <?php } ?>
                                    <?php } ?>
                                </div>
                                <?php } ?>
                                <?php } ?>
                                <?php if ($show_product_price) { ?>
                                <?php if ($product['price']) { ?>
                                <p class="price">
                                    <?php if (!$product['special']) { ?>
                                    <?php echo $product['price']; ?>
                                    <?php } else { ?>
                                    <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                                    <?php } ?>
                                    <?php if ($product['tax']) { ?>
                                    <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                                    <?php } ?>
                                </p>
                                <?php } ?>
                                <?php } ?>
                            </div>
                            <?php if ($show_addtocart) { ?>
                            <div class="button-group">
                                <a href="<?php echo $product['href']; ?>" class="btn btn-primary" target="_blank"><i class="fa fa-shopping-cart"></i> <span><?php echo $button_cart; ?></span></a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<style type="text/css">
    .product-thumb .button-group a {
        width: 100% !important;
    }
    .product-thumb .caption {
        min-height: 31% !important;
    }
    #category-search {
        height: 45px;
    }
    .currency-select {
        text-align: left;
    }
    .product-thumb .button-group, .product-thumb {
        overflow: hidden !important;
    }
    .caption h4 {
        white-space: nowrap;
        position: relative;
    }
    .caption h4:after {
        content: "";
        position: absolute;
        right: -20px;
        height: 25px;
        width: 25px;
        background-image: -webkit-gradient(linear,left top,right top,color-stop(0%,rgba(255,255,255,0)),color-stop(100%,rgba(255,255,255,1)));
        background-image: -webkit-linear-gradient(left,rgba(255,255,255,0),rgba(255,255,255,1));
        background: linear-gradient(to right,rgba(255,255,255,0.2),rgba(255,255,255,0.8),rgba(255,255,255,1));
    }
</style>
<script type="text/javascript"><!--
$(document).ready(function() {
    // Product List
    $('#list-view').click(function(e) {
        e.preventDefault();

        $('#content .product-facebook-layout > .clearfix').remove();

        $('#content .row > .product-facebook-layout').attr('class', 'product-facebook-layout product-list col-sm-12');

        localStorage.setItem('facebook-display', 'list');
    });

    // Product Grid
    $('#grid-view').click(function(e) {
        e.preventDefault();

        $('#content .product-facebook-layout > .clearfix').remove();

        $('#content .product-facebook-layout').attr('class', 'product-facebook-layout product-grid col-sm-3');

        localStorage.setItem('facebook-display', 'grid');
    });

    // Currency
    $('#facebook-currency .currency-select').on('click', function(e) {
        e.preventDefault();

        $('#facebook-currency input[name=\'code\']').attr('value', $(this).attr('name'));

        $('#facebook-currency').submit();
    });

    // Language
    $('#facebook-language a').on('click', function(e) {
        e.preventDefault();

        $('#facebook-language input[name=\'code\']').attr('value', $(this).attr('href'));

        $('#facebook-language').submit();
    });

    // Category
    $('#category-search').on('change', function(e) {
        var filter_category_id = $('#category-search').val();

        url = '<?php echo $home; ?>';

        if (filter_category_id > 0) {
            url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
        }

        location = url;
    });
});

// Search 
$('#search-button').on('click', function(e) {
    e.preventDefault();

    url = $('base').attr('href') + 'index.php?route=product/search';

    var value = $('header input[name=\'search\']').val();

    if (value) {
        url += '&search=' + encodeURIComponent(value);
    }

    window.open(url, '_blank');
});

if (localStorage.getItem('facebook-display') == 'list') {
    $('#list-view').trigger('click');
} else {
    $('#grid-view').trigger('click');
}

window.fbAsyncInit = function() {
    FB.init({
        appId      : '<?php echo $app_id; ?>',
        status     : true,
        cookie     : true,
        xfbml      : true
    });
};

(function(fb){

    var facebook_jsdk, id = 'facebook-jssdk';

    if (fb.getElementById(id)) {
        return;
    }

    facebook_jsdk = fb.createElement('script');

    facebook_jsdk.id = id;

    facebook_jsdk.async = true;

    facebook_jsdk.src = "//connect.facebook.net/en_US/all.js";

    fb.getElementsByTagName('head')[0].appendChild(facebook_jsdk);

}(document));
//--></script>
<?php if ($show_footer) { ?>
<footer>
    <div class="container">
        <div class="row">
            <?php if ($informations) { ?>
            <div class="col-sm-3">
                <h5><?php echo $text_information; ?></h5>
                <ul class="list-unstyled">
                    <?php foreach ($informations as $information) { ?>
                    <li><a href="<?php echo $information['href']; ?>" target="_blank"><?php echo $information['title']; ?></a></li>
                    <?php } ?>
                </ul>
            </div>
            <?php } ?>
            <div class="col-sm-3">
                <h5><?php echo $text_service; ?></h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $contact; ?>" target="_blank"><?php echo $text_contact; ?></a></li>
                    <li><a href="<?php echo $return; ?>" target="_blank"><?php echo $text_return; ?></a></li>
                    <li><a href="<?php echo $sitemap; ?>" target="_blank"><?php echo $text_sitemap; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5><?php echo $text_extra; ?></h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $manufacturer; ?>" target="_blank"><?php echo $text_manufacturer; ?></a></li>
                    <li><a href="<?php echo $voucher; ?>" target="_blank"><?php echo $text_voucher; ?></a></li>
                    <li><a href="<?php echo $affiliate; ?>" target="_blank"><?php echo $text_affiliate; ?></a></li>
                    <li><a href="<?php echo $special; ?>" target="_blank"><?php echo $text_special; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-3">
                <h5><?php echo $text_account; ?></h5>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $account; ?>" target="_blank"><?php echo $text_account; ?></a></li>
                    <li><a href="<?php echo $order; ?>" target="_blank"><?php echo $text_order; ?></a></li>
                    <li><a href="<?php echo $wishlist; ?>" target="_blank"><?php echo $text_wishlist; ?></a></li>
                    <li><a href="<?php echo $newsletter; ?>" target="_blank"><?php echo $text_newsletter; ?></a></li>
                </ul>
            </div>
        </div>
        <hr>
        <p><?php echo $powered; ?></p>
    </div>
</footer>
<?php } ?>
</body>
</html>
<?php } ?>
