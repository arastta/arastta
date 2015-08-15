<h2><?php echo $text_instruction; ?></h2>
<p><b><?php echo $text_description; ?></b></p>
<div class="well well-sm">
  <p><?php echo $bank; ?></p>
  <p><?php echo $text_payment; ?></p>
</div>
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
		url: 'index.php?route=payment/bank_transfer/confirm',
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