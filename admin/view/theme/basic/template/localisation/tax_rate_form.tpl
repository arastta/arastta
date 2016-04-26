<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-tax-rate" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-tax-rate" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <button type="submit" onclick="save('new')" form="form-tax-rate" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
            </div>
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tax-rate" class="form-horizontal">
            <div class="row">
                <div class="left-col col-sm-12">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="general">
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-name"><?php echo $entry_name; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                                        <?php if ($error_name) { ?>
                                        <div class="text-danger"><?php echo $error_name; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group required">
                                    <label class="col-sm-12" for="input-rate"><?php echo $entry_rate; ?></label>
                                    <div class="col-sm-12">
                                        <input type="text" name="rate" value="<?php echo $rate; ?>" placeholder="<?php echo $entry_rate; ?>" id="input-rate" class="form-control" />
                                        <?php if ($error_rate) { ?>
                                        <div class="text-danger"><?php echo $error_rate; ?></div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-type"><?php echo $entry_type; ?></label>
                                    <div class="col-sm-12">
                                        <select name="type" id="input-type" class="form-control">
                                            <?php if ($type == 'P') { ?>
                                            <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                                            <?php } else { ?>
                                            <option value="P"><?php echo $text_percent; ?></option>
                                            <?php } ?>
                                            <?php if ($type == 'F') { ?>
                                            <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                                            <?php } else { ?>
                                            <option value="F"><?php echo $text_amount; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12"><?php echo $entry_customer_group; ?></label>
                                    <div class="col-sm-12">
                                        <?php foreach ($customer_groups as $customer_group) { ?>
                                        <div class="checkbox">
                                            <label>
                                                <?php if (in_array($customer_group['customer_group_id'], $tax_rate_customer_group)) { ?>
                                                <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                                                <?php echo $customer_group['name']; ?>
                                                <?php } else { ?>
                                                <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                                                <?php echo $customer_group['name']; ?>
                                                <?php } ?>
                                            </label>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-12" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
                                    <div class="col-sm-12">
                                        <select name="geo_zone_id" id="input-geo-zone" class="form-control">
                                            <?php foreach ($geo_zones as $geo_zone) { ?>
                                            <?php  if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
                                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                                            <?php } else { ?>
                                            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                                            <?php } ?>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<?php echo $footer; ?>
