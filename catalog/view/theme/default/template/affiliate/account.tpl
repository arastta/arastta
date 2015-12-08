<?php echo $header; ?>
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
                    <li><a href="<?php echo $edit; ?>"><i class="fa fa-user">&nbsp;</i>  <?php echo $text_edit; ?></a></li>
                    <li><a href="<?php echo $password; ?>"><i class="fa fa-key"> </i> <?php echo $text_password; ?></a></li>
                    <li><a href="<?php echo $payment; ?>"><i class="fa fa-usd">&nbsp;</i> <?php echo $text_payment; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-5">
                <h3><?php echo $text_my_tracking; ?></h3>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $tracking; ?>"><i class="fa fa-code"> </i> <?php echo $text_tracking; ?></a></li>
                </ul>
            </div>
            <div class="col-sm-5">
                <h3><?php echo $text_my_commission; ?></h3>
                <ul class="list-unstyled">
                    <li><a href="<?php echo $commission; ?>"><i class="fa fa-money"> </i> <?php echo $text_commission; ?></a></li>
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
<?php echo $footer; ?>
