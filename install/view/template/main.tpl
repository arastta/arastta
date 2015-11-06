<?php echo $header; ?>
<!-- Arastta Installation -->
<div id="content">
	<div class="container">
		<div class="row">
			<div class="logo">
				<img src="view/image/logo.png" alt="" width="60px" height="72px">
			</div>
			<div class="install-center">
				<div class="panel panel-default">
					<div class="panel-body">
						<div class="lead text-center row install-wizard">
							<div id="step-database" class="col-xs-4 install-step" data-install-step="1">
								<div class="progress"><div class="progress-bar"></div></div>
								<div class="fa-stack">
									<i class="fa fa-circle fa-stack-2x install-header-circle"></i>
									<i class="fa fa-database fa-stack-1x fa-inverse"></i>
								</div>
								<div class="fa-title">
									<span><?php echo $text_database; ?></span>
								</div>
							</div>
							<div id="step-settings" class="col-xs-4 install-step" data-install-step="2">
								<div class="progress"><div class="progress-bar"></div></div>
								<div class="fa-stack">
									<i class="fa fa-circle fa-stack-2x install-header-circle"></i>
									<i class="fa fa-cog fa-stack-1x fa-inverse"></i>
								</div>
								<div class="fa-title">
									<span><?php echo $text_settings; ?></span>
								</div>
							</div>
							<div id="step-finish" class="col-xs-4 install-step" data-install-step="3">
								<div class="progress"><div class="progress-bar"></div></div>
								<div class="fa-stack">
									<i class="fa fa-circle fa-stack-2x install-header-circle"></i>
									<i class="fa fa-thumbs-up fa-stack-1x fa-inverse"></i>
								</div>
								<div class="fa-title">
									<span><?php echo $text_finish; ?></span>
								</div>
							</div>
						</div>

						<div id="install-body">
							<?php if ($requirements) { ?>
								<div class="col-xs-12">
									<?php foreach ($requirements as $requirement) { ?>
									<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $requirement; ?>
										<button type="button" class="close" data-dismiss="alert">&times;</button>
									</div>
									<?php } ?>
									<button id="refresh-page" onclick="location.reload();" class="btn btn-primary pull-right"><i class="fa fa-refresh"></i> <?php echo $button_refresh; ?></button>
								</div>
							<?php } ?>
							<div id="install-content"></div>
							<div id="install-loading"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
<?php if ($requirements) { ?>
	$(document).on('ready', function() {
		$('#step-database').addClass('text-muted');
		$('#step-settings').addClass('text-muted');
		$('#step-finish').addClass('text-muted');
	});
<?php } else { ?>
	$(document).on('ready', function() {
		displayDatabase();
	});

	// Display Database
	function displayDatabase() {
		$('#step-settings').removeClass('text-primary');
		$('#step-settings').addClass('text-muted');
		$('#step-database').removeClass('text-success');
		$('#step-database').addClass('text-primary');
		$('#step-finish').addClass('text-muted');

		$.ajax({
			url: 'index.php?route=database&lang=<?php echo $lang; ?>',
			dataType: 'json',
			type: 'post',
			beforeSend: function() {
				$('#install-loading').html('<span class="loading-bar"><span class="loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
			},
			complete: function() {
				$('.loading-bar').delay(500).fadeOut('slow');
			},
			success: function(json) {
				$('#install-content').html(json['output']);
        $('.loading-bar').css({"height": $('.panel-body').height()-84});
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// Save Database
	function saveDatabase() {
		$.ajax({
			url: 'index.php?route=database/save&lang=<?php echo $lang; ?>',
			dataType: 'json',
			type: 'post',
			data: $('#install-content input[type=\'text\'], input[type=\'password\'], select'),
			beforeSend: function() {
				$('#install-loading').html('<span class="loading-bar"><span class="loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
				$('.loading-bar').css({"height": $('.panel-body').height()-84});
			},
			complete: function() {
				$('.loading-bar').delay(500).fadeOut('slow');
			},
			success: function(json) {
				$('.text-danger').remove();
				$('.alert-danger').remove();
				$('.form-group').children().removeClass('has-error');

				if (json['error']) {
					for (i in json['error']) {
						var element = $('#input-database-' + i.replace(/_/g, '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}

					if (json['error']['config']) {
							$('#install-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'][i] + '</div>');
					}

					// Highlight any found errors
					$('.text-danger').parent().addClass('has-error');

					// Reset the height of loading bar
					$('.loading-bar').css({"height": $('.panel-body').height()-84});
				} else if (json['output']) {
					$('.loading-bar').css({"height": "118%"});

					$('#step-database').addClass('text-success');
					$('#step-settings').addClass('text-primary');
					$('#step-finish').addClass('text-muted');

					$('#install-content').html(json['output']);
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}

	// Save Settings
	function saveSettings() {
		$.ajax({
			url: 'index.php?route=setting/save&lang=<?php echo $lang; ?>',
			dataType: 'json',
			type: 'post',
			data: $('#install-content input[type=\'text\'], input[type=\'password\'], input[type=\'checkbox\']:checked'),
			beforeSend: function() {
				$('#install-loading').html('<span class="loading-bar"><span class="loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
				$('.loading-bar').css({"height": $('.panel-body').height()-84});
			},
			complete: function() {
				$('.loading-bar').delay(500).fadeOut('slow');
			},
			success: function(json) {
				$('.text-danger').remove();
				$('.form-group').removeClass('has-error');

				if (json['error']) {
					for (i in json['error']) {
						var element = $('#input-' + i.replace(/_/g, '-'));

						if ($(element).parent().hasClass('input-group')) {
							$(element).parent().after('<div class="text-danger">' + json['error'][i] + '</div>');
						} else {
							$(element).after('<div class="text-danger">' + json['error'][i] + '</div>');
						}
					}

					// Highlight any found errors
					$('.text-danger').parent().addClass('has-error');

					// Reset the height of loading bar
					$('.loading-bar').css({"height": $('.panel-body').height()-84});
				} else if (json['output']) {
					$('#step-database').addClass('text-success');
					$('#step-settings').addClass('text-success');
					$('#step-finish').addClass('text-success');

					$('#install-content').html(json['output']);
					$('.loading-bar').css({"height": $('.panel-body').height()-84});
					$('.loading-spin').css({"padding": "5% 40%"});

					$.ajax({
						url: 'index.php?route=finish/removeInstall&lang=<?php echo $lang; ?>',
						type: 'post',
						dataType: 'json',
						success: function(json) {
							if (!json['success']) {
								$('#error-remove-install').css('display', 'block');
							}
						},
						error: function() {
							$('#error-remove-install').css('display', 'block');
						}
					});
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
<?php } ?>
</script>