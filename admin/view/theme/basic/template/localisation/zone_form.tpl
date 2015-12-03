<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-zone" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
		<button type="submit" form="form-zone" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
		<button type="submit" onclick="save('new')" form="form-zone" data-toggle="tooltip" title="<?php echo $button_savenew; ?>" class="btn btn-default" data-original-title="Save & New"><i class="fa fa-plus text-success"></i></button>		
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
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
	<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-zone" class="form-horizontal">
	  <div class="row">
		<div class="left-col col-sm-8">
			<div class="panel panel-default">
			  <div class="panel-body">
				<div class="general">
				  <div class="form-group required">
					<label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
					<div class="col-sm-10">
					  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
					  <?php if ($error_name) { ?>
					  <div class="text-danger"><?php echo $error_name; ?></div>
					  <?php } ?>
					</div>
				  </div>
				  <div class="form-group">
					<label class="col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>
					<div class="col-sm-10">
					  <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
					</div>
				  </div>
				  <div class="form-group">
					<label class="col-sm-2 control-label" for="input-country"><?php echo $entry_country; ?></label>
					<div class="col-sm-10">
					  <select name="country_id" id="input-country" class="form-control">
						<?php foreach ($countries as $country) { ?>
						<?php if ($country['country_id'] == $country_id) { ?>
						<option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
						<?php } ?>
						<?php } ?>
					  </select>
					</div>
				  </div>
				</div>
			  </div>
			</div>
		</div>
		<div class="right-col col-sm-4">
			<div class="panel panel-default">
			  <div class="panel-heading">
				<h3 class="panel-title"><?php echo $text_publish; ?></h3>
				<div class="pull-right">
					<div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
				</div>
			  </div>
			  <div class="panel-body">
				<div class="publish">
					<div class="form-group">
						<label class="col-sm-3 control-label"><?php echo $text_enabled; ?></label>
						<div class="col-sm-9">
							<label class="radio-inline">
								<?php if ($status) { ?>
								<input type="radio" name="status" value="1" checked="checked" />
								<?php echo $text_enabled; ?>
								<?php } else { ?>
								<input type="radio" name="status" value="1" />
								<?php echo $text_enabled; ?>
								<?php } ?>
							</label>
							<label class="radio-inline">
								<?php if (!$status) { ?>
								<input type="radio" name="status" value="0" checked="checked" />
								<?php echo $text_disabled; ?>
								<?php } else { ?>
								<input type="radio" name="status" value="0" />
								<?php echo $text_disabled; ?>
								<?php } ?>
							</label>
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
<script type="text/javascript"><!--
function save(type){
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}
//--></script>
<?php echo $footer; ?>
<link href="view/theme/basic/stylesheet/basic.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="view/theme/basic/javascript/basic.js" ></script>