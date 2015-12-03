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
		  <div class="pull-right">
			<button type="button" data-toggle="tooltip" title="<?php echo $button_show_filter; ?>" class="btn btn-primary btn-sm" id="showFilter"><i class="fa fa-eye"></i></button>
			<button type="button" data-toggle="tooltip" title="<?php echo $button_hide_filter; ?>" class="btn btn-primary btn-sm" id="hideFilter"><i class="fa fa-eye-slash"></i></button>
		  </div>		
      </div>
      <div class="panel-body">
        <div class="well" style="display:none;">
          <div class="row">
            <div class="col-lg-12">
              <div class="input-group">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                  </button>
                  <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_name; ?></div></button>
                  <ul class="dropdown-menu">
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_name; ?>', 'filter_name');"><?php echo $entry_name; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_email; ?>', 'filter_email');"><?php echo $entry_email; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_approved; ?>', 'filter_approved');"><?php echo $entry_approved; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_date_added; ?>', 'filter_date_end');"><?php echo $entry_date_added; ?></a></li>
                  </ul>
                </div>
                <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control filter">
                <input type="text" name="filter_email"  value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control filter hidden">
                <select name="filter_status" id="input-status" class="form-control filter hidden">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!$filter_status && !is_null($filter_status)) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select>
				<select name="filter_approved" id="input-approved" class="form-control filter hidden">
                  <option value="*"></option>
                  <?php if ($filter_approved) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <?php } ?>
                  <?php if (!$filter_approved && !is_null($filter_approved)) { ?>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
			    <div class="input-group date filter hidden">
                  <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" placeholder="<?php echo $entry_date_end; ?>" data-date-format="YYYY-MM-DD" id="input-date-end" class="form-control filter hidden" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
          </div>		  
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-affiliate">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead>
                <tr>
                  <td style="width: 70px;" class="text-center">
                  <div class="bulk-action">
                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                    <span class="bulk-caret"><i class="fa fa-caret-down"></i></span>
					<span class="item-selected"></span>
					<span class="bulk-action-button">
					  <a class="dropdown-toggle" data-toggle="dropdown" href="#">
						<b><?php echo $text_bulk_action; ?></b>
						<span class="caret"></span>
					  </a>
					  <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
						<li class="dropdown-header"><?php echo $text_bulk_action; ?></li>
						<li><a onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-affiliate').submit() : false;"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
					  </ul>
					</span>
                  </div></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.email') { ?>
                    <a href="<?php echo $sort_email; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_email; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_email; ?>"><?php echo $column_email; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_balance; ?></td>
                  <td class="text-left"><?php if ($sort == 'c.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'c.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>		  
              <tbody>
                <?php if ($affiliates) { ?>
                <?php foreach ($affiliates as $affiliate) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($affiliate['affiliate_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $affiliate['affiliate_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left">
					<?php if ($affiliate['approve']) { ; ?>
                    <a href="<?php echo $affiliate['approve']; ?>" data-toggle="tooltip" title="<?php echo $button_approve; ?>"><i class="fa fa-thumbs-o-up"></i></a>
                    <?php } else { ?>
                    <a disabled><i class="fa fa-thumbs-o-up"></i></a>
                    <?php } ?>
                    <?php if ($affiliate['unlock']) { ; ?>
                    <a href="<?php echo $affiliate['unlock']; ?>" data-toggle="tooltip" title="<?php echo $button_unlock; ?>"><i class="fa fa-unlock"></i></a>
                    <?php } else { ?>
                    <a disabled><i class="fa fa-unlock"></i></a>
                    <?php } ?>                   
                    <a href="<?php echo $affiliate['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
					<?php echo $affiliate['name']; ?></td>
                  <td class="text-left"><?php echo $affiliate['email']; ?></td>
                  <td class="text-right"><?php echo $affiliate['balance']; ?></td>
                  <td class="text-left"><?php echo $affiliate['status']; ?></td>
                  <td class="text-left"><?php echo $affiliate['date_added']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=marketing/affiliate&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_email = $('input[name=\'filter_email\']').val();
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}
		
	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status); 
	}	
	
	var filter_approved = $('select[name=\'filter_approved\']').val();
	
	if (filter_approved != '*') {
		url += '&filter_approved=' + encodeURIComponent(filter_approved);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	location = url;
}

function changeFilterType(text, filter_type) {
	$('.filter-type').text(text);

	$('.filter').addClass('hidden');
	$('input[name=\'' + filter_type + '\']').removeClass('hidden');
	$('select[name=\'' + filter_type + '\']').removeClass('hidden');
	if (filter_type == 'filter_date_start' || filter_type == 'filter_date_end') {
      $('.well .input-group-btn').removeClass('hidden');
      $('.well .input-group').removeClass('hidden');
    }
}
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=marketing/affiliate/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['affiliate_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['label']);
	}	
});

$('input[name=\'filter_email\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=marketing/affiliate/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
			dataType: 'json',			
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['email'],
						value: item['affiliate_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_email\']').val(item['label']);
	}	
});

$(document).ready(function() {
  $('input[type=\'checkbox\']').click (function() {
    var checkboxes = $('#form-affilate input[type=\'checkbox\']');
    var selected = 0;

    $.each(checkboxes, function( index, value ) {
      var thisCheck = $(value);

      if (thisCheck.is(':checked')) {
        selected = selected + 1;
      }
    });

    if (selected) {
      $('.bulk-caret').hide();
      $('.bulk-action').addClass('bulk-action-activate');
      $('.bulk-action-activate').removeClass('bulk-action');
	  
      $('thead td:not(:first)').hide();
      $('.item-selected').css('display', 'inline');
      $('.bulk-action-button').css('display', 'inline');
      $('.item-selected').html(selected + ' <?php echo $text_selected; ?>');
    } else {
	  $('thead td').show();
      $('.item-selected').css('display', 'none');
      $('.bulk-action-button').css('display', 'none');
	  $('.bulk-caret').show();
	  $('.bulk-action-activate').addClass('bulk-action');
      $('.bulk-action').removeClass('bulk-action-activate');
    }
  });  
});
//--></script> 
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
//--></script></div>
<?php echo $footer; ?>
<link href="view/theme/basic/stylesheet/basic.css" type="text/css" rel="stylesheet" />