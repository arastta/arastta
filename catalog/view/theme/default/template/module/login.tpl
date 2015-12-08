<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
<div class="row">
    <div class="">
        <div class="well">
            <?php if (!$logged) { ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label class="control-label" for="input-email"><?php echo $entry_email_address; ?></label>
                    <input type="text" name="email" placeholder="<?php echo $entry_email_address; ?>" id="input-email" class="form-control"/>
                </div>
                <div class="form-group">
                    <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                    <input type="password" name="password" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control"/>
                    <input type="submit" value="<?php echo $button_login; ?>" class="btn btn-primary"/>
                    <a href="<?php echo $register; ?>" class="btn btn-primary"><span><?php echo $button_create; ?></span></a>
                </div>
            </form>
            <?php } else { ?>
            <div class="middle" id="information" style="text-align: left;">
                <?php echo $text_greeting; ?>
                <div id="information" style="margin-top: 10px;">
                    <p style="margin:0;"><b><?php echo $text_my_account; ?></b></p>
                    <ul>
                        <li><a href="<?php echo $edit; ?>"><?php echo $text_information; ?></a></li>
                        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
                        <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
                    </ul>
                    &nbsp;
                    <p style="margin:0;"><b><?php echo $text_my_orders; ?></b></p>
                    <ul>
                        <li><a href="<?php echo $order; ?>"><?php echo $text_history; ?></a></li>
                        <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
                    </ul>
                    &nbsp;
                    <p style="margin:0;"><b><?php echo $text_my_newsletter; ?></b></p>
                    <ul>
                        <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
                    </ul>
                </div>
            </div>
            <div style="text-align: center;border-top:1px solid #ccc;padding: 15px 0;">
                <a href="<?php echo $logout; ?>" class="btn btn-primary"><span><?php echo $button_logout; ?></span></a>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
