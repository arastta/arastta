<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" onclick="save('save')" form="form-email-template" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
		<button type="submit" form="form-email-template" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
  			<div class="form-group">
  				<label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_shortcodes; ?>"><?php echo $text_shortcodes; ?></span></label>
					<div class="col-sm-10" style="margin-bottom: 20px; min-height: 50px; padding: 7px; border: 1px solid #EBEBEB; border-radius: 5px;">
					<div id="shortCodes"></div>
					</div>
				</div>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-email-template" class="form-horizontal">
           <div class="tab-pane active in" id="tab-general">
              <ul class="nav nav-tabs" id="language">
                <?php foreach ($languages as $language) { ?>
                <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                <?php } ?>
              </ul>
              <div class="tab-content">
                <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="email_template_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($email_template_description[$language['language_id']]) ? $email_template_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                      <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-description<?php echo $language['language_id']; ?>"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <textarea name="email_template_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($email_template_description[$language['language_id']]) ? $email_template_description[$language['language_id']]['description'] : ''; ?></textarea>
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
           </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
	<?php if( $text_editor == 'summernote' ) { ?>
		$('#input-description<?php echo $language['language_id']; ?>').summernote({
			height: 300
		});
	<?php } else if ( $text_editor == 'tinymce' ) { ?>
		$('#input-description<?php echo $language['language_id']; ?>').tinymce({
			script_url : 'view/javascript/tinymce/tinymce.min.js',
			plugins: "visualblocks,textpattern,table,media,pagebreak,link,image",
		      target_list: [
		       {title: 'None', value: ''},
		       {title: 'Same page', value: '_self'},
		       {title: 'New page', value: '_blank'},
		       {title: 'LIghtbox', value: '_lightbox'}
		      ],
			height : 500  
		});
	<?php } ?>
<?php } ?>

$('#language a:first').tab('show');

jQuery(document).ready(function(){ 
	function getShortCodes() {
		var html = '';
		var value = '<?php echo $context; ?>';

		switch( value ) {
			case 'admin_forgotten':
				html += '<?php echo $text_shortcode_admin_forgotten; ?>';
				break;
			case 'admin_login':
				html += '<?php echo $text_shortcode_admin_login; ?>';
				break;
			case 'affiliate_affiliate_approve':
				html += '<?php echo $text_shortcode_affiliate_affiliate_approve; ?>';
				break;
			case 'affiliate_order':
				html += '<?php echo $text_shortcode_affiliate_order; ?>';
				break;
			case 'affiliate_add_commission':
				html += '<?php echo $text_shortcode_affiliate_add_commission; ?>';
				break;
			case 'affiliate_register':
				html += '<?php echo $text_shortcode_affiliate_register; ?>';
				break;
			case 'affiliate_approve':
				html += '<?php echo $text_shortcode_affiliate_approve; ?>';
				break;
			case 'affiliate_password_reset':
				html += '<?php echo $text_shortcode_affiliate_password_reset; ?>';
				break;
			case 'contact_confirmation':
				html += '<?php echo $text_shortcode_contact_confirmation; ?>';
				break;
			case 'customer_credit':
				html += '<?php echo $text_shortcode_customer_credit; ?>';
				break;
			case 'customer_voucher':
				html += '<?php echo $text_shortcode_customer_voucher; ?>';
				break;
			case 'customer_approve':
				html += '<?php echo $text_shortcode_customer_approve; ?>';
				break;
			case 'customer_password_reset':
				html += '<?php echo $text_shortcode_customer_password_reset; ?>';
				break;
			case 'customer_register_approval':
				html += '<?php echo $text_shortcode_customer_register_approval; ?>';
				break;
			case 'customer_register':
				html += '<?php echo $text_shortcode_customer_register; ?>';
				break;
			case 'order_status_voided':
			case 'order_status_shipped':
			case 'order_status_reversed':
			case 'order_status_denied':
			case 'order_status_expired':
			case 'order_status_failed':
			case 'order_status_pending':
			case 'order_status_processed':
			case 'order_status_processing':
			case 'order_status_refunded':
			case 'order_status_cancelled':
				html += '<?php echo $text_shortcode_order_status_voided; ?>';
				break;
		}
		$('#shortCodes').html(html);
	};

	getShortCodes();
});

function save(type){
	var input = document.createElement('input');
	input.type = 'hidden';
	input.name = 'button';
	input.value = type;
	form = $("form[id^='form-']").append(input);
	form.submit();
}
//--></script>
<?php echo $footer; ?>
