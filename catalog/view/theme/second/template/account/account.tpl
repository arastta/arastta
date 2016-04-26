<?php echo $header; ?>
<?php if($top) : ?>
<div id="top-block">
    <?php echo $top; ?>
</div>
<?php endif; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <div class="col-sm-5">
                <h3><?php echo $text_my_account; ?></h3>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $edit; ?>"><i class="fa fa-user">&nbsp;</i> <?php echo $text_edit; ?></a></li>
                    <li><a href="<?php echo $password; ?>"><i class="fa fa-key"> </i> <?php echo $text_password; ?></a></li>
                    <li><a href="<?php echo $address; ?>"><i class="fa fa-home"> </i> <?php echo $text_address; ?></a></li>
                    <li><a href="<?php echo $wishlist; ?>"><i class="fa fa-heart"> </i> <?php echo $text_wishlist; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-5">
                <h3><?php echo $text_my_orders; ?></h3>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $order; ?>"><i class="fa fa-shopping-cart"> </i> <?php echo $text_order; ?></a></li>
                    <li><a href="<?php echo $download; ?>"><i class="fa fa-download"> </i> <?php echo $text_download; ?></a></li>
                    <?php if ($reward) { ?>
                    <li><a href="<?php echo $reward; ?>"><i class="fa fa-gift"> </i> <?php echo $text_reward; ?></a></li>
                    <?php } ?>
                    <li><a href="<?php echo $return; ?>"><i class="fa fa-reply"> </i> <?php echo $text_return; ?></a></li>
                    <li><a href="<?php echo $credit; ?>"><i class="fa fa-credit-card"> </i> <?php echo $text_credit; ?></a></li>
                    <li><a href="<?php echo $recurring; ?>"><i class="fa fa-repeat"> </i> <?php echo $text_recurring; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-5">
                <h3><?php echo $text_my_newsletter; ?></h3>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $newsletter; ?>"><i class="fa fa-envelope"> </i> <?php echo $text_newsletter; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-5">
                <h3><?php echo $text_my_logout; ?></h3>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $logout; ?>"><i class="fa fa-sign-out"> </i> <?php echo $text_logout; ?></a></li>
                </ul>
            </div>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
<?php if($bottom_a) : ?>
<div id="bottom-a-block">
    <div class="container">
        <?php echo $bottom_a; ?>
    </div>
</div>
<?php endif; ?>
<?php if($bottom_b) : ?>
<div id="bottom-b-block">
    <div class="container">
        <?php echo $bottom_b; ?>
    </div>
</div>
<?php endif; ?>
<?php if($bottom_c) : ?>
<div id="bottom-c-block">
    <div class="container">
        <?php echo $bottom_c; ?>
    </div>
</div>
<?php endif; ?>
<?php echo $footer; ?>
