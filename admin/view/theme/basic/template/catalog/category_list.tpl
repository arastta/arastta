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
                <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $column_name; ?></div></button>
                <ul class="dropdown-menu">
                  <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_name; ?>', 'filter_name');"><?php echo $column_name; ?></a></li>
                  <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_status; ?>', 'filter_status');"><?php echo $column_status; ?></a></li>
                </ul>
              </div>
              <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" id="input-name" class="form-control filter">
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
            </div>
          </div>
        </div>
      </div>
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-category">
        <div class="table-responsive">
          <table class="table table-hover ">
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
						<li><a onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i> <?php echo $button_enable; ?></a></li>
						<li><a onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i> <?php echo $button_disable; ?></a></li>
						<li><a onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-category').submit() : false;"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
					  </ul>
					</span>
                  </div>
				</td>
                <td class="text-left"><?php if ($sort == 'name') { ?>
                  <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                  <?php } ?>
                </td>
				<td class="text-right"><?php if ($sort == 'status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?>
				 </td>
              </tr>
            </thead>
            <tbody>
              <?php if ($categories) { ?>
              <?php foreach ($categories as $category) { ?>
              <tr>
                <td class="text-center"><?php if (in_array($category['category_id'], $selected)) { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="selected[]" value="<?php echo $category['category_id']; ?>" />
                  <?php } ?></td>
                <td class="text-left">
                  <a href="<?php echo $category['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
                  <?php
                  $category_name = explode('&nbsp;&nbsp;&gt;&nbsp;&nbsp;', $category['name']);
                  $is_subcategory = count($category_name);

                  if ($is_subcategory > 1) {
                    for ($i = 0; $i < $is_subcategory - 1; $i++) {
                      echo $category_name[$i] . '&nbsp;&nbsp;&gt;&nbsp;&nbsp;' ;
                    }
                  }
                  ?>
                  <span class="category-name" id="name[<?php echo $category['category_id']; ?>]"><?php echo end($category_name); ?>
                  </span>
                </td>
				<td class="text-right">
                  <span class="category-status" data-prepend="<?php echo $text_select; ?>" data-source="{'1': '<?php echo $text_enabled; ?>', '0': '<?php echo $text_disabled; ?>'}"><?php echo $category['status']; ?></span>
                </td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
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
<script type="text/javascript"><!--
$(document).ready(function() {
  $.fn.editable.defaults.mode = 'inline';

  $('.category-name').editable({
    url: function (params) {
      $.ajax({
        type: 'post',
        url: 'index.php?route=catalog/category/inline&token=<?php echo $token; ?>&category_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
        data: {name: params.value},
        async: false,
        error: function (xhr, ajaxOptions, thrownError) {
          return false;
        }
      })
    },
    showbuttons: false,
  });

  $('.category-status').editable({
    type: 'select',
    url: function (params) {
      $.ajax({
        type: 'post',
        url: 'index.php?route=catalog/category/inline&token=<?php echo $token; ?>&category_id=' + $(this).parent().parent().find( "input[name*=\'selected\']").val(),
        data: {status: params.value},
        async: false,
        error: function (xhr, ajaxOptions, thrownError) {
          return false;
        }
      })
    },
    showbuttons: false,
  });

  $('input[type=\'checkbox\']').click (function() {
    var checkboxes = $('#form-category input[type=\'checkbox\']');
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

$('input[name=\'filter_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['index'],
						name: item['name'],
						value: item['category_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_name\']').val(item['name']);
	}
});
//--></script>
<script type="text/javascript"><!--
function filter() {
  url = 'index.php?route=catalog/category&token=<?php echo $token; ?>';

  var filter_name = $('input[name=\'filter_name\']').val();

  if (filter_name) {
    url += '&filter_name=' + encodeURIComponent(filter_name);
  }

  var filter_sortorder = $('input[name=\'filter_sortorder\']').val();

  if (filter_sortorder) {
    url += '&filter_sortorder=' + encodeURIComponent(filter_sortorder);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != '*') {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
}

function changeFilterType(text, filter_type) {
  $('.filter-type').text(text);

  $('.filter').addClass('hidden');
  $('input[name=\'' + filter_type + '\']').removeClass('hidden');
  $('select[name=\'' + filter_type + '\']').removeClass('hidden');
}

function changeStatus(status){
  $.ajax({
    url: 'index.php?route=common/edit/changeStatus&type=category&status='+ status +'&token=<?php echo $token; ?>',
    dataType: 'json',
    data: $("form[id^='form-']").serialize(),
    success: function(json) {
      if(json){
        $('.panel.panel-default').before('<div class="alert alert-warning"><i class="fa fa-warning"></i> ' + json.warning + '<button type="button" class="close" data-dismiss="alert">×</button></div>');
      }
      else{
        location.reload();
      }
    }
  });
}
//--></script>
<?php echo $footer; ?>
<link href="view/theme/basic/stylesheet/basic.css" type="text/css" rel="stylesheet" />
<link href="view/javascript/bootstrap3-editable/css/bootstrap-editable.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/bootstrap3-editable/js/bootstrap-editable.js" ></script>