<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
		<a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
      </div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-option">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-name">
                <tr>
                  <td style="width: 70px;" class="text-center">
                  <div class="bulk-action">
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                      <span>
                        <i class="fa fa-caret-down"></i>
                      </span>
                  </div></td>
                  <td class="text-left"><?php if ($sort == 'od.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
			  <thead class="table-quick-edit">
				<tr>
				  <td style="width: 70px;" class="text-center">
					<div class="bulk-action-activate">
					  <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
					  <span class="item-selected"></span>
						  <span class="bulk-action-button">
							<a class="dropdown-toggle" data-toggle="dropdown" href="#">
							  <b><?php echo $text_bulk_action; ?></b>
							  <span class="caret"></span>
							</a>
							<ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
							  <li class="dropdown-header"><?php echo $text_bulk_action; ?></li>
							  <li><a onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
							</ul>
						  </span>
					</div>
				  </td>
				  <td class="text-left">
					<?php echo $column_name; ?></td>
				</tr>
			  </thead>				  
              <tbody>
                <?php if ($options) { ?>
                <?php foreach ($options as $option) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($option['option_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $option['option_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $option['option_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left">
					<a href="<?php echo $option['edit']; ?>"><i class="fa fa-pencil"></i></a>
					<?php echo $option['name']; ?>
				  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
 <style>
  .table.table-hover td {
    height: 67px;
  }

  .table-quick-edit > tr > td {
    vertical-align: bottom;
    border-bottom: 0px solid #dddddd !important;
    border-top: 0px solid #dddddd !important;
    display: none;
  }

  .table-quick-edit td {
    color: #FFF;
  }

  .item-selected {
    position: absolute;
    color: #000;
    padding-top: 5px;
    padding-left: 35px;
    border: 1px solid #ddd;
    border-radius: 3px;
    border-bottom-right-radius: 0;
    border-top-right-radius: 0;
    padding-right: 10px;
    height: 26px;
    margin-left: -27px;
    margin-top: -1px;
  }

  .bulk-action {
    border: 1px solid #ddd;
    border-radius: 3px;
    -webkit-appearance: none!important;
    -moz-appearance: none!important;
  }

  .bulk-action-activate {
    -webkit-appearance: none!important;
    -moz-appearance: none!important;
    border: 1px solid #ffffff;
  }

  .bulk-action-activate input[type="checkbox"] {
    margin-top: 5px;
  }

  .bulk-action-button a {
    margin-top: -5px;
  }

  .bulk-action-activate input[type="checkbox"] {
    z-index: 999;
  }

  .bulk-action-button {
    position: absolute;
    margin-left: 142px;
    height: 26px;
    margin-top: -1px;
    padding-top: 5px;
    border: 1px solid #ddd;
    border-bottom-right-radius: 3px;
    border-top-right-radius: 3px;
    width: 100px;
  }

  .bulk-action i {
    position: absolute;
    margin-left: 9px;
    margin-top: 6px;
    font-size: 12px;
  }

  input[type="radio"],
  .radio input[type="radio"],
  .radio-inline input[type="radio"],
  input[type="checkbox"],
  .checkbox input[type="checkbox"],
  .checkbox-inline input[type="checkbox"] {
    margin-left: -15px;
    width: 15px !important;
    height: 15px !important;
  }

  input[type="checkbox"]:checked::after,
  .checkbox input[type="checkbox"]:checked::after,
  .checkbox-inline input[type="checkbox"]:checked::after {
    top: -4px !important;
  }

  .bulk-action-image {
    width: 50px;
    height: 50px;
  }
</style> 
<script type="text/javascript"><!--
$(document).ready(function() {
  $('input[type=\'checkbox\']').click (function() {
    var checkboxs = $('#form-option input[type=\'checkbox\']');
    var selected = 0;

    $.each(checkboxs, function( index, value ) {
      var thisCheck = $(value);

      if (thisCheck.is(':checked')) {
        selected = selected + 1;
      }
    });

    if (selected) {
      $('.table-name').hide();
      $('.table-quick-edit tr td').show();
      $('.item-selected').html(selected + ' <?php echo $text_selected_option; ?>');
    } else {
      $('.table-name').show();
      $('.table-quick-edit tr td').hide();
    }
  });
});
//--></script>
<?php echo $footer; ?>