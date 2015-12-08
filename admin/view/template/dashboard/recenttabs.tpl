<div id="recenttabs" class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-th-list fa-lg"></i> <?php echo $heading_title; ?></h3>
    </div>
    <div class="panel-body">
        <nav>
            <ul class="nav nav-pills">
                <li class="active">
                    <a data-toggle="tab" href="#dash_recent_orders">
                        <i class="fa fa-fire"></i>
                        <span class="hidden-inline-xs"><?php echo $text_last_order; ?></span>
                    </a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#dash_best_sellers">
                        <i class="fa fa-trophy"></i>
                        <span class="hidden-inline-xs"><?php echo $text_best_sellers; ?></span>
                    </a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#dash_less_sellers">
                        <i class="fa fa-thumbs-down"></i>
                        <span class="hidden-inline-xs"><?php echo $text_less_sellers; ?></span>
                    </a>
                </li>
                <li class="">
                    <a data-toggle="tab" href="#dash_most_viewed">
                        <i class="fa fa-eye"></i>
                        <span class="hidden-inline-xs"><?php echo $text_most_viewed; ?></span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="tab-content panel">
            <div id="dash_recent_orders" class="tab-pane active">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="text-right"><?php echo $column_order_id; ?></td>
                            <td><?php echo $column_customer; ?></td>
                            <td><?php echo $column_status; ?></td>
                            <td><?php echo $column_date_added; ?></td>
                            <td class="text-right"><?php echo $column_total; ?></td>
                            <td class="text-right"><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($orders) { ?>
                        <?php foreach ($orders as $order) { ?>
                        <tr>
                            <td class="text-right"><?php echo $order['order_id']; ?></td>
                            <td><?php echo $order['customer']; ?></td>
                            <td><?php echo $order['status']; ?></td>
                            <td><?php echo $order['date_added']; ?></td>
                            <td class="text-right"><?php echo $order['total']; ?></td>
                            <td class="text-right">
                                <a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-success">
                                    <i class="fa fa-eye"></i>
                                </a></td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="dash_best_sellers" class="tab-pane">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="text-right"><?php echo $column_product_id; ?></td>
                            <td><?php echo $column_product_name; ?></td>
                            <td><?php echo $column_total; ?></td>
                            <td><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($bestseller) { ?>
                        <?php foreach ($bestseller as $_bestseller) { ?>
                        <tr>
                            <td class="text-right"><?php echo $_bestseller['product_id']; ?></td>
                            <td><?php echo $_bestseller['name']; ?></td>
                            <td><?php echo $_bestseller['total']; ?></td>
                            <td class="text-right">
                                <a href="<?php echo $_bestseller['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="dash_less_sellers" class="tab-pane">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="text-right"><?php echo $column_product_id; ?></td>
                            <td><?php echo $column_product_name; ?></td>
                            <td><?php echo $column_total; ?></td>
                            <td><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($lessseller) { ?>
                        <?php foreach ($lessseller as $_lessseller) { ?>
                        <tr>
                            <td class="text-right"><?php echo $_lessseller['product_id']; ?></td>
                            <td><?php echo $_lessseller['name']; ?></td>
                            <td><?php echo $_lessseller['total']; ?></td>
                            <td class="text-right">
                                <a href="<?php echo $_lessseller['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div id="dash_most_viewed" class="tab-pane">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                        <tr>
                            <td class="text-right"><?php echo $column_product_id; ?></td>
                            <td><?php echo $column_product_name; ?></td>
                            <td><?php echo $column_total; ?></td>
                            <td><?php echo $column_action; ?></td>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if ($viewed) { ?>
                        <?php foreach ($viewed as $_viewed) { ?>
                        <tr>
                            <td class="text-right"><?php echo $_viewed['product_id']; ?></td>
                            <td><?php echo $_viewed['name']; ?></td>
                            <td><?php echo $_viewed['total']; ?></td>
                            <td class="text-right">
                                <a href="<?php echo $_viewed['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                        </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
