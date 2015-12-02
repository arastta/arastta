<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
        <div class="table-responsive">
        <form id="form" method="post">
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
						<li><a onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i> <?php echo $button_enable; ?></a></li>
						<li><a onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i> <?php echo $button_disable; ?></a></li>
					  </ul>
					</span>
                  </div></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td></td>
                <td class="text-left"><?php echo $column_status; ?></td>
              </tr>
            </thead>			
            <tbody>
              <?php if ($extensions) { ?>
              <?php foreach ($extensions as $extension) { ?>
              <tr>
            	<td style="text-align: center;"><input type="checkbox" name="selected[]" value="<?php echo $extension['extension']; ?>" /></td>
                <td class="text-left">
				  <?php if (!$extension['installed']) { ?>
                  <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>"><i class="fa fa-plus-circle"></i></a>
                  <?php } else { ?>
                  <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>"><i class="fa fa-minus-circle"></i></a>
                  <?php } ?>
                  <?php if ($extension['installed']) { ?>
                  <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
                  <?php } ?>
				  <?php echo $extension['name']; ?></td>
                <td class="text-center"><?php echo $extension['link'] ?></td>
                <td class="text-left"><?php echo $extension['status'] ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
   	   </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function changeStatus(status){
	$.ajax({
		url: 'index.php?route=common/edit/changeStatus&type=payment&status='+ status +'&token=<?php echo $token; ?>',
		dataType: 'json',
		data: $("form").serialize(),
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
<script type="text/javascript"><!--
$(document).ready(function() {
  $('input[type=\'checkbox\']').click (function() {
    var checkboxes = $('#form input[type=\'checkbox\']');
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
<?php echo $footer; ?>
<link href="view/theme/basic/stylesheet/basic.css" type="text/css" rel="stylesheet" />