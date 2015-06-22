<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-language-override" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
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
							<label class="control-label" for="input-text"><?php echo $entry_text; ?></label>
                            <input type="text" name="filter_text" value="<?php echo $filter_text; ?>" placeholder="<?php echo $entry_text; ?>" id="input-text" class="form-control" autocomplete="off" />
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-path"><?php echo $column_path; ?></label>
                            <select name="filter_path" id="input-path" class="form-control">
								<option value=""><?php echo $text_all; ?></option>
								<?php
								foreach( $paths as $k => $v ) { ?>
									<option value="<?php echo $v; ?>"<?php echo ( $v == $filter_path ) ? ' selected="selected"' : ''; ?>><?php echo $v; ?></option>
									<?php
								} ?>
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-folder"><?php echo $column_folder; ?></label>
                            <select name="filter_folder" id="input-folder" class="form-control">
								<option value=""><?php echo $text_all; ?></option>
								<?php
								foreach( $folders as $k => $v ) { ?>
									<option value="<?php echo $v; ?>"<?php echo ( $v == $filter_folder ) ? ' selected="selected"' : ''; ?>><?php echo $v; ?></option>
									<?php
								} ?>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-language"><?php echo $column_language; ?></label>
							<select name="filter_language" id="input-language" class="form-control">
                                <option value="*"><?php echo $text_all; ?></option>
	                            <?php foreach ($languages as $language) { ?>
                                <option value="<?php echo $language; ?>"<?php echo ($language == $filter_language) ? ' selected="selected"' : ''; ?>><?php echo $language; ?></option>
                                <?php } ?>
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<label class="control-label" for="input-client"><?php echo $column_client; ?></label>
							<select name="filter_client" id="input-client" class="form-control">
								<option value="*"><?php echo $text_all; ?></option>
								<option value="admin"<?php echo ($filter_client == 'admin') ? ' selected="selected"' : ''; ?>><?php echo $text_admin; ?></option>
								<option value="catalog"<?php echo ($filter_client == 'catalog') ? ' selected="selected"' : ''; ?>><?php echo $text_catalog; ?></option>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="form-group">
							<label class="control-label" for="input-limit"><?php echo $entry_limit; ?></label>
							<select name="filter_limit" id="input-limit" class="form-control">
								<option value="5"<?php echo ($filter_limit == 5) ? ' selected="selected"' : ''; ?>>5</option>
								<option value="20"<?php echo ($filter_limit == 20) ? ' selected="selected"' : ''; ?>>20</option>
								<option value="50"<?php echo ($filter_limit == 50) ? ' selected="selected"' : ''; ?>>50</option>
								<option value="100"<?php echo ($filter_limit == 100) ? ' selected="selected"' : ''; ?>>100</option>
								<option value="<?php echo $total; ?>"><?php echo $text_all; ?></option>
							</select>
						</div>
					</div>
					<div class="col-sm-2">
						<div class="pull-right">
							<button type="button" data-status="show" data-toggle="tooltip" title="<?php echo $button_show_help; ?>" class="btn btn-info btn-sm toggleHelp" id="showhelp"><i class="fa fa-eye"></i></button>
				<button type="button" data-status="hide" data-toggle="tooltip" title="<?php echo $button_hide_help; ?>" class="btn btn-info btn-sm toggleHelp" id="hidehelp" style="display: none;"><i class="fa fa-eye-slash"></i></button>
							<button type="button" id="button-filter" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
						</div>
					</div>
				</div>
				<div class="help" style="display: none;"><?php echo $help_common; ?></div>
			</div>
	        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-language-override">
	          <div class="table-responsive">
	            <table class="table table-bordered table-hover">
	              <thead>
	                <tr>
	                    <td class="text-left"><?php echo $column_text; ?></td>
	                    <td class="text-left" style="width: 1px;"><?php echo $column_constant; ?></td>
	                    <td class="text-left" style="width: 1px;"><?php echo $column_path; ?></td>
	                    <td class="text-left" style="width: 1px;"><?php echo $column_language; ?></td>
	                    <td class="text-left" style="width: 1px;"><?php echo $column_client; ?></td>
	                </tr>
	              </thead>
	              <tbody>
	                <?php
					if ($files) {
	                	foreach ($files as $key => $strings) {
		                    foreach ($strings as $var => $value) {
		                        $temp	= explode('_', $key);
		                        $client = array_shift( $temp );

		                        if (empty($temp[0]) || empty($temp[1])) {
		                            continue;
		                        }

		                        $language	= $temp[0];
		                        $path		= $temp[1] . ( !empty($temp[2] ) ? '/' . $temp[2] : '' ) . ( !empty( $temp[3] ) ? '_' . $temp[3] : '' ) . '.php';
								$len		= mb_strlen( $value ); ?>
			                    <tr>
			                        <td class="text-left">
									<?php
									if( $len > 115 ) {
										$rows = (int) ( $len / 115 ) + 1; ?>
										<textarea name="lstrings[<?php echo $key; ?>][<?php echo $var; ?>]" class="form-control input-full-width" rows="<?php echo $rows; ?>"><?php echo $value; ?></textarea>
										<?php
									}else{ ?>
										<input type="text" name="lstrings[<?php echo $key; ?>][<?php echo $var; ?>]" value="<?php echo $value; ?>" class="form-control input-full-width" />
										<?php
									} ?>
									</td>
			                        <td class="text-left assignText" data-target="text"><?php echo $var; ?></td>
			                        <td class="text-left assignSelect" data-target="path"><?php echo $path; ?></td>
			                        <td class="text-left assignSelect" data-target="language"><?php echo $language; ?></td>
			                        <td class="text-left assignSelect" data-target="client"><?php echo $client; ?></td>
			                    </tr>
			                    <?php
							}
		                }
	                } else { ?>
		                <tr>
		                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
		                </tr>
		                <?php
					} ?>
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
<script type="text/javascript">
	/* <![CDATA[ */
	jQuery('#input-text').on('keydown', function(e) {
		if( e.keyCode == 13 ) {
			jQuery('#button-filter').trigger('click');
		}
	});

	jQuery('#input-limit').on('change', function(e) {
		jQuery('#button-filter').trigger('click');
	});

	jQuery('.toggleHelp').click(function(){
		var status = jQuery(this).data('status');
		var target = jQuery(this).data('target');

		if( !target ) {
			target = 'help';
		}

		jQuery('.' + target).slideToggle();

		switch(status){
			case 'show':
				jQuery('#show' + target).hide();
				jQuery('#hide' + target).show();
				break;
			case 'hide':
				jQuery('#show' + target).show();
				jQuery('#hide' + target).hide();
				break;
		}
	});

	jQuery('.assignText').on('click',function(){
		var string = jQuery(this).text();
		var target = jQuery(this).data('target');

		jQuery('#input-' + target).val(string);
	});

	jQuery('.assignSelect').on('click',function(){
		var string = jQuery(this).text();
		var target = jQuery(this).data('target');

		jQuery('#input-' + target + ' option[value="' + string + '"]').prop('selected',true);
	});

	jQuery('#button-filter').on('click', function() {
	    url = 'index.php?route=system/language_override';

	    var filter_text = jQuery('input[name="filter_text"]').val();

	    if (filter_text) {
	        url += '&filter_text=' + encodeURIComponent(filter_text);
	    }

	    var filter_client = jQuery('select[name="filter_client"]').val();

	    if (filter_client) {
	        url += '&filter_client=' + encodeURIComponent(filter_client);
	    }

	    var filter_language = jQuery('select[name="filter_language"]').val();

	    if (filter_language != '*') {
	        url += '&filter_language=' + encodeURIComponent(filter_language);
	    }

	    var filter_folder = jQuery('select[name="filter_folder"]').val();

	    if (filter_folder) {
	        url += '&filter_folder=' + encodeURIComponent(filter_folder);
	    }

	    var filter_path = jQuery('select[name="filter_path"]').val();

	    if (filter_path) {
	        url += '&filter_path=' + encodeURIComponent(filter_path);
	    }

	    var filter_limit = jQuery('select[name="filter_limit"]').val();

	    if (filter_limit) {
	        url += '&filter_limit=' + encodeURIComponent(filter_limit);
	    }

	    location = url + '&token=<?php echo $token; ?>';
	});
	/* ]]> */
</script>
<?php echo $footer; ?>
