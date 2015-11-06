<?php echo $header; ?>
<div id="content">
  <div class="container">
    <div class="row">
      <div class="logo">
        <img src="view/image/logo.png" alt="" width="60px" height="72px">
      </div>
      <div class="install-center">
        <div class="panel panel-default">
          <div class="panel-body">
            <div id="install-body">
              <div id="install-content">
                <div class="form-group">
                  <div class="col-xs-12">
                    <select name="lang_code" id="lang_code" size="20" class="form-control lang_select">
                      <option value="en-GB" selected="selected" data-next="Next">English (UK)</option>
                      <?php foreach ($languages as $language) { ?>
                      <option value="<?php echo $language['crowdin_code']; ?>" data-next="<?php echo $language['native_next']; ?>"><?php echo $language['native_name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <div class="col-xs-4 col-xs-offset-8 text-right">
                    <button type="button" onclick="saveLanguage();" class="btn btn-success" id="btn-lang"><?php echo $button_next; ?> <i class="fa fa-arrow-right"></i></button>
                  </div>
                </div>
              </div>
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
  $('select').on('change', function() {
    $('#btn-lang').html($(this).find(':selected').attr('data-next') + ' <i class="fa fa-arrow-right"></i>');
  });

  function saveLanguage() {
    $.ajax({
      url: 'index.php?route=language/save',
      dataType: 'json',
      type: 'post',
      data: $('#install-content select'),
      beforeSend: function() {
        $('#install-loading').html('<span class="loading-bar"><span class="loading-spin"><i class="fa fa-spinner fa-spin"></i></span></span>');
        $('.loading-bar').css({"height": $('.install-center').height(), "margin-top": '-10px'});
      },
      complete: function() {
        $('.loading-bar').delay(500).fadeOut('slow');
      },
      success: function(json) {
        $('.alert-danger').remove();

        if (json['error']) {
          if (json['error']['lang_download']) {
            $('#install-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['lang_download'] + '</div>');
          }

          if (json['error']['lang_code']) {
            $('#install-body').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['lang_code'] + '</div>');
          }

          // Reset the height of loading bar
          $('.loading-bar').css({"height": $('.panel-body').height()-84});
        } else if (json['lang']) {
          window.location = 'index.php?lang='+json['lang'];
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }

  $('#install-body select').keydown(function(e) {
    if (e.keyCode == 13) {
      saveLanguage();
    }
  });
</script>