<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $extension_installer; ?>" data-toggle="tooltip" title="<?php echo $button_installer; ?>" class="btn btn-default" data-original-title="<?php echo $button_installer; ?>"><i class="fa fa-upload"></i></a>
        <a href="<?php echo $extension_modifications; ?>" data-toggle="tooltip" title="<?php echo $button_modifications; ?>" class="btn btn-default" data-original-title="<?php echo $button_modifications; ?>"><i class="fa fa-random"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <?php if (empty($data['api_key']) or isset($data['error']) or $data['changeApiKey']) { ?>
          <form action="<?php echo $action; ?>" method="post" class="form-horizontal" enctype="multipart/form-data" id="form-api_key">
            <fieldset>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-api_key"><span data-toggle="tooltip" title="<?php echo $help_api_key; ?>"><a href="<?php echo $api_key_href; ?>" target="_blank"><?php echo $entry_api_key; ?></a></span></label>
                <div class="col-sm-10">
                  <input type="password" name="api_key" placeholder="<?php echo $entry_api_key; ?>" id="input-api_key" class="form-control" />
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                  <input type="submit" class="btn btn-primary" value="<?php echo $button_continue; ?>" />
                </div>
              </div>
            </fieldset>
          </form>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  apiBaseUrl = '<?php echo $apiBaseUrl; ?>';
  baseUrl = '<?php echo HTTP_SERVER; ?>';
  apps_version = '<?php echo VERSION; ?>';
  token = '<?php echo $token; ?>';
  <?php if ($error_warning or $data['changeApiKey']) { ?>
    error = true;
  <?php } else { ?>
    error = false;
  <?php } ?>

  $(document).ready(function() {
      if (!Marketplace.apps.loaded && !error) {
        Marketplace.apps.initialize();
        checkMenu();
      }
  });
  function checkMenu() {
    if (Marketplace.apps.loaded && !error) {
      if($('#marketplace-header').is(':visible')) {
        if (sessionStorage.getItem('marketplace-menu')) {
          // Sets active and open to selected page in the left column menu.
          $('#marketplace-menu a[onclick="'+sessionStorage.getItem('marketplace-menu')+'"]').parents('li').addClass('active');
          $('#marketplace-menu a[onclick="'+sessionStorage.getItem('marketplace-menu')+'"]').trigger('click');
        }
      } else {
        setTimeout(checkMenu, 50);
      }
    }
  }

</script>

<?php echo $footer; ?>
