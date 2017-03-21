<div style="width:695px;">
    <p style="margin-top:0px;margin-bottom:20px"><?php echo $data['return_1_return_request']; ?></p>
    <table style="border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px">
        <thead>
        <tr>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222" colspan="2"><?php echo $data['order_details']; ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px">
                <b><?php echo $data['order_id']; ?>:&nbsp;</b>
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{order_id}</span>
                <br>
                <b><?php echo $data['date_ordered']; ?>:</b>&nbsp;<span style="font-size: 13px; line-height: 18px; text-align: right;">{date_ordered}</span>
                <br>
                <b style="color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;"><?php echo $data['customer']; ?>:</b>&nbsp;<span style="font-size: 13px; line-height: 18px; text-align: right;">{firstname} {lastname}</span>
                <br>
                <b style="color: rgb(0, 0, 0); font-family: arial, sans-serif; line-height: normal;"><?php echo $data['email']; ?>:</b>&nbsp;<span style="font-size: 13px; line-height: 18px; text-align: right;">{email}</span>
                <br>
                <b><?php echo $data['telephone']; ?>:</b>&nbsp;<span style="font-size: 13px; line-height: 18px; text-align: right;">{telephone}</span>
                <br>
            </td>
        </tr>
        </tbody>
    </table>
    <table style="border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px">
        <thead>
        <tr>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222"><?php echo $data['product']; ?></td>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222"><?php echo $data['model']; ?></td>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align: right;padding:7px;color:#222222"><?php echo $data['quantity']; ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 40%;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px">
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{product}</span><br></td>
            <td style="width: 40%;font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px">
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{model}</span></td>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align: right;padding:7px">
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{quantity}</span></td>
        </tr>
        </tbody>
    </table>
    <p></p>
    <p></p>
    <table style="border-collapse:collapse; width: 690px;border-top:1px solid #dddddd;border-left:1px solid #dddddd;margin-bottom:20px">
        <thead>
        <tr>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;background-color:#efefef;font-weight:bold;text-align:left;padding:7px;color:#222222" colspan="2"><?php echo $data['return_1_return_details']; ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="font-size:12px;border-right:1px solid #dddddd;border-bottom:1px solid #dddddd;text-align:left;padding:7px">
                <b><?php echo $data['return_1_return_reason']; ?>:&nbsp;</b>
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{return_reason}</span> <br>
                <b><?php echo $data['return_1_opened']; ?>:&nbsp;</b>
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{opened}</span> <br><br>
                <span style="font-size: 13px; line-height: 18px; text-align: right;">{comment}</span></td>
        </tr>
        </tbody>
    </table>
</div>
