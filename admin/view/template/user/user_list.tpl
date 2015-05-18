<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-user').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-username"><?php echo $entry_username; ?></label>
                <input type="text" name="filter_user_name" value="<?php echo $filter_user_name; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-user-group"><?php echo $entry_user_group; ?></label>
                <input type="text" name="filter_user_group" value="<?php echo $filter_user_group; ?>" placeholder="<?php echo $entry_user_group; ?>" id="input-user-group" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-firstname"><?php echo $entry_firstname; ?></label>
                <input type="text" name="filter_first_name" value="<?php echo $filter_first_name; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-lastname"><?php echo $entry_lastname; ?></label>
                <input type="text" name="filter_last_name" value="<?php echo $filter_last_name; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                <input type="text" name="filter_email" value="<?php echo $filter_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
              </div>			
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
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
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>	  
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-user">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'username') { ?>
                    <a href="<?php echo $sort_username; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_username; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_username; ?>"><?php echo $column_username; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($users) { ?>
                <?php foreach ($users as $user) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($user['user_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $user['user_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $user['username']; ?></td>
                  <td class="text-left"><?php echo $user['status']; ?></td>
                  <td class="text-left"><?php echo $user['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $user['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
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
$('#button-filter').on('click', function() {
	var url = 'index.php?route=user/user&token=<?php echo $token; ?>';

	var filter_user_name = $('input[name=\'filter_user_name\']').val();

	if (filter_user_name) {
		url += '&filter_user_name=' + encodeURIComponent(filter_user_name);
	}

	var filter_user_group = $('input[name=\'filter_user_group\']').val();

	if (filter_user_group) {
		url += '&filter_user_group=' + encodeURIComponent(filter_user_group);
	}

	var filter_first_name = $('input[name=\'filter_first_name\']').val();
	
	if (filter_first_name) {
		url += '&filter_first_name=' + encodeURIComponent(filter_first_name);
	}
	
	var filter_last_name = $('input[name=\'filter_last_name\']').val();
	
	if (filter_last_name) {
		url += '&filter_last_name=' + encodeURIComponent(filter_last_name);
	}
	
	var filter_email = $('input[name=\'filter_email\']').val();
	
	if (filter_email) {
		url += '&filter_email=' + encodeURIComponent(filter_email);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	location = url;
});
//--></script>
  <script type="text/javascript"><!--
$('input[name=\'filter_user_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_user_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['username'],
						value: item['user_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_user_name\']').val(item['label']);
	}
});

$('input[name=\'filter_user_group\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_user_group=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['user_group'],
						value: item['user_group_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_user_group\']').val(item['label']);
	}
});

$('input[name=\'filter_first_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_first_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['firstname'],
						value: item['user_id']
					}
				}));
				
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_first_name\']').val(item['label']);
	}
});

$('input[name=\'filter_last_name\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_last_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['lastname'],
						value: item['user_id']
					}
				}));
				
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_last_name\']').val(item['label']);
	}
});

$('input[name=\'filter_email\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=user/user/autocomplete&token=<?php echo $token; ?>&filter_email=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['email'],
						value: item['user_id']
					}
				}));
				
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_email\']').val(item['label']);
	}
});
//--></script>
<?php echo $footer; ?> 