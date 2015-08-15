<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" data-loading-text="<?php echo $text_order_processing; ?>" data-reset-text="<?php echo $text_order_complete; ?>" id="button-confirm" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
    if (!checkTerms()) { return false; }

    $.ajax({
		type: 'get',
		url: 'index.php?route=payment/cod/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},		
		success: function() {
			location = '<?php echo $continue; ?>';
		}		
	});
});
//--></script>