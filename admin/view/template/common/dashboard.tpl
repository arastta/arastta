<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <h1><?php echo $heading_title; ?></h1>
            <ul class="breadcrumb" style="display:none">
                <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_install) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if (!empty($sale) || !empty($order) || !empty($customer) || !empty($online)) { ?>
        <div class="row" id="sum_widgets">
            <?php if (!empty($sale)) { ?>
            <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $sale; ?></div>
            <?php }
        if (!empty($order)) { ?>
            <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $order; ?></div>
            <?php }
        if (!empty($customer)) { ?>
            <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $customer; ?></div>
            <?php }
        if (!empty($online)) { ?>
            <div class="col-lg-3 col-md-3 col-sm-6"><?php echo $online; ?></div>
            <?php } ?>
        </div>
        <?php }
        if (!empty($charts)) { ?>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sx-12 col-sm-12"><?php echo $charts; ?></div>
        </div>
        <?php }
        if (!empty($map) || !empty($recenttabs)) { ?>
        <div class="row">
            <?php if (!empty($map)) { ?>
            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $map; ?></div>
            <?php }
        if (!empty($recenttabs)) { ?>
            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $recenttabs; ?></div>
            <?php } ?>
        </div>
        <?php } ?>
        <?php if (!empty($activity)) { ?>
        <div class="row">
            <div class="col-lg-6 col-md-12 col-sx-12 col-sm-12"><?php echo $activity; ?></div>
        </div>
        <?php } ?>
    </div>
</div>
<?php echo $footer; ?>
