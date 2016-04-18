<div class="form-group">
    <label class="col-sm-12"><?php echo $entry_account; ?></label>
    <div class="col-sm-12"><?php echo $email; ?></div>
</div>
<div class="form-group">
    <label class="col-sm-12"><?php echo $entry_key; ?></label>
    <div class="col-sm-12"><?php echo $key; ?></div>
    <input type="hidden" name="params[twofactorauth][googleauth][key]" value="<?php echo $key; ?>" />
</div>
<div class="form-group">
    <label class="col-sm-12"><?php echo $entry_qrcode; ?></label>
    <div class="col-sm-12"><?php echo '<img src="data:image/png;base64,'.base64_encode($qrcode).'"/>'; ?></div>
</div>
<?php if ($new) { ?>
<div class="form-group">
    <label class="col-sm-12" for="input-code"><?php echo $entry_code; ?></label>
    <div class="col-sm-12">
        <input type="text" name="params[twofactorauth][googleauth][code]" value="" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
    </div>
</div>
<?php } ?>