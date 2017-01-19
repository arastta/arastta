<div class="module <?php echo $type; ?><?php echo ($module_class) ? ' ' . $module_class : ''; ?>">
    <?php if ($show_title) { ?>
    <h3 class="module-title"><span><?php echo !empty($title) ? $title : $heading_title; ?></span></h3>
    <?php } ?>
    <div class="row">
        <?php foreach ($products as $product) { ?>
        <div class="col-md-<?php echo $bootstrap_module_column; ?>">
            <div class="product-thumb transition">
                <?php if ($product_image) { ?>
                <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
                <?php } ?>
                <?php if ($product_name || $product_description || $product_rating || $product_price) { ?>
                <div class="caption">
                    <?php if ($product_name) { ?>
                    <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                    <?php } ?>
                    <?php if ($product_description) { ?>
                    <p><?php echo $product['description']; ?></p>
                    <?php } ?>
                    <?php if ($product['rating'] && $product_rating) { ?>
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
                    <?php if ($product['price'] && $product_price) { ?>
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
                </div>
                <?php } ?>
                <?php if ($add_to_cart || $wish_list || $compare) { ?>
                <div class="button-group">
                    <?php if ($add_to_cart) { ?>
                    <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $button_cart; ?></span></button>
                    <?php } ?>
                    <?php if ($wish_list) { ?>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart"></i></button>
                    <?php } ?>
                    <?php if ($compare) { ?>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>