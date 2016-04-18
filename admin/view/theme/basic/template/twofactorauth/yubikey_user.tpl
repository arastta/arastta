<?php if ($new) { ?>
<div class="form-group">
    <label class="col-sm-12" for="input-code"><?php echo $entry_code; ?></label>
    <div class="col-sm-12">
        <input type="text" name="params[twofactorauth][yubikey][code]" value="" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
    </div>
</div>
<?php } ?>