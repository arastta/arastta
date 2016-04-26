<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $code; ?>">
<head>
    <meta charset="UTF-8" />
    <title><?php echo $text_invoice; ?></title>
    <base href="<?php echo $base; ?>" />
    <script type="text/javascript" src="catalog/view/javascript/jquery/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="catalog/view/javascript/bootstrap/js/bootstrap.min.js"></script>
    <link type="text/css" href="catalog/view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
    <link type="text/css" href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
</head>
<body>
<div id="content">
    <div class="container-fluid">
        <div class="col-lg-12 text-left">
            <img src="image/<?php echo $logo; ?>" title="<?php echo $order['store_name']; ?>" alt="<?php echo $order['store_name']; ?>" class="img-responsive invoice-logo">
        </div>
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th colspan="2">
                        <?php echo $text_order_detail; ?>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="invoice-50">
                        <address>
                            <strong><?php echo $order['store_name']; ?></strong><br />
                            <?php echo $order['store_address']; ?>
                        </address>
                        <b><?php echo $text_telephone; ?></b> <?php echo $order['store_telephone']; ?><br />
                        <?php if ($order['store_fax']) { ?>
                        <b><?php echo $text_fax; ?></b> <?php echo $order['store_fax']; ?><br />
                        <?php } ?>
                        <b><?php echo $text_email; ?></b> <?php echo $order['store_email']; ?><br />
                        <b><?php echo $text_website; ?></b> <a href="<?php echo $order['store_url']; ?>"><?php echo $order['store_url']; ?></a>
                    </td>
                    <td class="invoice-50">
                        <b><?php echo $text_invoice_no; ?></b> <?php echo $order['invoice_number']; ?><br />
                        <b><?php echo $text_invoice_date; ?></b> <?php echo $order['invoice_date']; ?><br />
                        <b><?php echo $text_order_id; ?></b> <?php echo $order['order_id']; ?><br />
                        <b><?php echo $text_order_date; ?></b> <?php echo $order['order_date']; ?><br />
                        <b><?php echo $text_payment_method; ?></b> <?php echo $order['payment_method']; ?><br />
                        <?php if ($order['shipping_method']) { ?>
                        <b><?php echo $text_shipping_method; ?></b> <?php echo $order['shipping_method']; ?><br />
                        <?php } ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th class="invoice-50">
                        <b><?php echo $text_to; ?></b>
                    </th>
                    <th class="invoice-50">
                        <b><?php echo $text_ship_to; ?></b>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <address>
                            <?php echo $order['payment_address']; ?>
                        </address>
                    </td>
                    <td>
                        <address>
                            <?php echo $order['shipping_address']; ?>
                        </address>
                    </td>
                </tr>
                </tbody>
            </table>
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>
                        <b><?php echo $column_product2; ?></b>
                    </th>
                    <th>
                        <b><?php echo $column_model; ?></b>
                    </th>
                    <th class="text-right">
                        <b><?php echo $column_quantity; ?></b>
                    </th>
                    <th class="text-right">
                        <b><?php echo $column_price2; ?></b>
                    </th>
                    <th class="text-right">
                        <b><?php echo $column_total; ?></b>
                    </th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($order['product'] as $product) { ?>
                <tr>
                    <td>
                        <?php echo $product['name']; ?>
                        <?php foreach ($product['option'] as $option) { ?>
                        <br />
                        &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?>
                    </td>
                    <td>
                        <?php echo $product['model']; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $product['quantity']; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $product['price']; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $product['total']; ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach ($order['voucher'] as $voucher) { ?>
                <tr>
                    <td>
                        <?php echo $voucher['description']; ?>
                    </td>
                    <td></td>
                    <td class="text-right">
                        1
                    </td>
                    <td class="text-right">
                        <?php echo $voucher['amount']; ?>
                    </td>
                    <td class="text-right">
                        <?php echo $voucher['amount']; ?>
                    </td>
                </tr>
                <?php } ?>
                <?php foreach ($order['total'] as $total) { ?>
                <tr>
                    <td class="text-right" colspan="4">
                        <b><?php echo $total['title']; ?></b>
                    </td>
                    <td class="text-right">
                        <?php echo $total['text']; ?>
                    </td>
                </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
        <?php if ($order['comment']) { ?>
        <div class="col-lg-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <td>
                        <b><?php echo $column_comment; ?></b>
                    </td>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <?php echo $order['comment']; ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</div>
</body>
</html>
