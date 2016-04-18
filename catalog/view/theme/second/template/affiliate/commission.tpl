<?php echo $header; ?>
<?php if($top) : ?>
<div id="top-block">
    <?php echo $top; ?>
</div>
<?php endif; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"> <?php echo $breadcrumb['text']; ?> </a> </li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <p><?php echo $text_balance; ?> <strong><?php echo $balance; ?></strong>.</p>
            <div class="table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        <td class="text-left"><?php echo $column_date_added; ?></td>
                        <td class="text-left"><?php echo $column_description; ?></td>
                        <td class="text-right"><?php echo $column_amount; ?></td>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if ($commissions) { ?>
                    <?php foreach ($commissions  as $commission) { ?>
                    <tr>
                        <td class="text-left"><?php echo $commission['date_added']; ?></td>
                        <td class="text-left"><?php echo $commission['description']; ?></td>
                        <td class="text-right"><?php echo $commission['amount']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                        <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="text-right"><?php echo $pagination; ?></div>
            <div class="buttons clearfix">
                <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
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
