<div style="width: 680px;"><p> {store_logo} </p>
    <p><?php echo $data['order_5_thank_you']; ?></p>
    <p><?php echo $data['view_order']; ?></p>
    <p> {order_href} </p>
    <div class="table-responsive">
        <table class="table" style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
            <thead>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;" colspan="2"><?php echo $data['order_details']; ?></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
                    <?php echo $data['order_id']; ?>
                    <br>
                    <?php echo $data['date_added']; ?>
                    <br>
                    <?php echo $data['payment_method']; ?>
                    <br>
                    <?php echo $data['shipping_method']; ?>
                </td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;">
                    <?php echo $data['email']; ?>
                    <br>
                    <?php echo $data['telephone']; ?>
                    <br>
                    <?php echo $data['ip_address']; ?>
                    <br>
                    <?php echo $data['oder_status']; ?>
                    <br>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="table-responsive">
        <table class="table" style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
            <thead>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $data['payment_address']; ?></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $data['shipping_address']; ?></td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"> {payment_address}</td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"> {shipping_address}</td>
            </tr>
            </tbody>
        </table>
    </div>
    {comment:start}
    <div class="table-responsive">
        <table class="table" style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
            <tbody>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"> {comment}</td>
            </tr>
            </tbody>
        </table>
    </div>
    {comment:stop}
    <div class="table-responsive">
        <table class="table" style="border-collapse: collapse; width: 100%; border-top: 1px solid #DDDDDD; border-left: 1px solid #DDDDDD; margin-bottom: 20px;">
            <thead>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $data['image']; ?></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $data['product']; ?></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: left; padding: 7px; color: #222222;"><?php echo $data['model']; ?></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $data['quantity']; ?></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $data['price']; ?></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; background-color: #EFEFEF; font-weight: bold; text-align: right; padding: 7px; color: #222222;"><?php echo $data['total']; ?></td>
            </tr>
            </thead>
            <tbody>
            <tr class="hidden">
                <td colspan="6">{product:start}</td>
            </tr>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"> {product_image}</td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"> {product_name}</td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: left; padding: 7px;"> {product_model}</td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"> {product_quantity}</td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"> {product_price}</td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"> {product_total}</td>
            </tr>
            <tr class="hidden">
                <td colspan="6">{product:stop}</td>
            </tr>
            </tbody>
            <tfoot>
            <tr class="hidden">
                <td colspan="6">{total:start}</td>
            </tr>
            <tr>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;" colspan="5">
                    <b>{total_title}:</b></td>
                <td style="font-size: 12px; border-right: 1px solid #DDDDDD; border-bottom: 1px solid #DDDDDD; text-align: right; padding: 7px;"> {total_value}</td>
            </tr>
            <tr class="hidden">
                <td colspan="6">{total:stop}</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <p><?php echo $data['reply_email']; ?></p>
</div>
