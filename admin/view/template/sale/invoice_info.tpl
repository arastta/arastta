<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $email; ?>" data-toggle="tooltip" title="<?php echo $button_email; ?>" class="btn btn-success"><i class="fa fa-envelope"></i></a>
                <a href="<?php echo $pdf; ?>" data-toggle="tooltip" title="<?php echo $button_pdf; ?>" class="btn btn-warning"><i class="fa fa-file-pdf-o"></i></a>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_invoice; ?></h3>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tab-details" data-toggle="tab"><?php echo $tab_details; ?></a></li>
                    <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab-details">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td colspan="2"><?php echo $text_order_detail; ?></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td style="width: 50%;">
                                    <address>
                                        <strong><?php echo $invoice['store_name']; ?></strong><br />
                                        <?php echo $invoice['store_address']; ?>
                                    </address>
                                    <b><?php echo $text_telephone; ?></b> <?php echo $invoice['store_telephone']; ?><br />
                                    <?php if ($invoice['store_fax']) { ?>
                                    <b><?php echo $text_fax; ?></b> <?php echo $invoice['store_fax']; ?><br />
                                    <?php } ?>
                                    <b><?php echo $text_email; ?></b> <?php echo $invoice['store_email']; ?><br />
                                    <b><?php echo $text_website; ?></b> <a href="<?php echo $invoice['store_url']; ?>"><?php echo $invoice['store_url']; ?></a>
                                </td>
                                <td style="width: 50%;">
                                    <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice['invoice_number']; ?><br />
                                    <b><?php echo $text_invoice_date; ?></b> <?php echo $invoice['invoice_date']; ?><br />
                                    <b><?php echo $text_order_id; ?></b> <a href="<?php echo $order; ?>"><?php echo $invoice['order_id']; ?></a><br />
                                    <b><?php echo $text_order_date; ?></b> <?php echo $invoice['order_date']; ?><br />
                                    <b><?php echo $text_payment_method; ?></b> <?php echo $invoice['payment_method']; ?><br />
                                    <?php if ($invoice['shipping_method']) { ?>
                                    <b><?php echo $text_shipping_method; ?></b> <?php echo $invoice['shipping_method']; ?><br />
                                    <?php } ?>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td style="width: 50%;"><b><?php echo $text_to; ?></b></td>
                                <td style="width: 50%;"><b><?php echo $text_ship_to; ?></b></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><address>
                                    <?php echo $invoice['payment_address']; ?>
                                </address></td>
                                <td><address>
                                    <?php echo $invoice['shipping_address']; ?>
                                </address></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td><b><?php echo $column_product; ?></b></td>
                                <td><b><?php echo $column_model; ?></b></td>
                                <td class="text-right"><b><?php echo $column_quantity; ?></b></td>
                                <td class="text-right"><b><?php echo $column_price; ?></b></td>
                                <td class="text-right"><b><?php echo $column_total; ?></b></td>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($invoice['product'] as $product) { ?>
                            <tr>
                                <td><?php echo $product['name']; ?>
                                    <?php foreach ($product['option'] as $option) { ?>
                                    <br />
                                    &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                                    <?php } ?></td>
                                <td><?php echo $product['model']; ?></td>
                                <td class="text-right"><?php echo $product['quantity']; ?></td>
                                <td class="text-right"><?php echo $product['price']; ?></td>
                                <td class="text-right"><?php echo $product['total']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php foreach ($invoice['voucher'] as $voucher) { ?>
                            <tr>
                                <td><?php echo $voucher['description']; ?></td>
                                <td></td>
                                <td class="text-right">1</td>
                                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                            </tr>
                            <?php } ?>
                            <?php foreach ($invoice['total'] as $total) { ?>
                            <tr>
                                <td class="text-right" colspan="4"><b><?php echo $total['title']; ?></b></td>
                                <td class="text-right"><?php echo $total['text']; ?></td>
                            </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                        <?php if ($invoice['comment']) { ?>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <td><b><?php echo $column_comment; ?></b></td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><?php echo $invoice['comment']; ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <?php } ?>
                    </div>
                    <div class="tab-pane" id="tab-history">
                        <div id="history"></div>
                        <br />
                        <fieldset>
                            <legend><?php echo $text_history; ?></legend>
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-notify"><?php echo $entry_notify; ?></label>
                                    <div class="col-sm-10">
                                        <input type="checkbox" name="notify" value="1" id="input-notify" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
                                    <div class="col-sm-10">
                                        <textarea name="comment" rows="8" id="input-comment" class="form-control"></textarea>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button id="button-history" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i> <?php echo $button_history_add; ?></button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript"><!--
    $('#history').delegate('.pagination a', 'click', function(e) {
        e.preventDefault();

        $('#history').load(this.href);
    });

    $('#history').load('index.php?route=sale/invoice/gethistories&token=<?php echo $token; ?>&invoice_id=<?php echo $invoice_id; ?>');

    $('#button-history').on('click', function() {
        if(typeof verifyStatusChange == 'function'){
            if(verifyStatusChange() == false){
                return false;
            }
        }

        $.ajax({
            url: 'index.php?route=sale/invoice/addhistory&token=<?php echo $token; ?>&invoice_id=<?php echo $invoice_id; ?>',
            type: 'post',
            dataType: 'json',
            data: 'notify=' + ($('input[name=\'notify\']').prop('checked') ? 1 : 0) + '&append=' + ($('input[name=\'append\']').prop('checked') ? 1 : 0) + '&comment=' + encodeURIComponent($('textarea[name=\'comment\']').val()),
            beforeSend: function() {
                $('#button-history').button('loading');
            },
            complete: function() {
                $('#button-history').button('reset');
            },
            success: function(json) {
                $('.alert').remove();

                if (json['error']) {
                    $('#history').before('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                if (json['success']) {
                    $('#history').load('index.php?route=sale/invoice/gethistories&token=<?php echo $token; ?>&invoice_id=<?php echo $invoice_id; ?>');

                    $('#history').before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    $('textarea[name=\'comment\']').val('');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });
    //--></script></div>
<?php echo $footer; ?> 
