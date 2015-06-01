<ul id="menu">
  <li id="dashboard"><a href="<?php echo $dashboard; ?>"><i class="fa fa-dashboard fa-fw"></i> <span><?php echo $text_dashboard; ?></span></a></li>
  <?php if( $preturn_category != false || $preturn_product != false || $preturn_recurring != false || $preturn_filter != false || $preturn_attribute != false || $preturn_attribute_group != false || $preturn_option != false || $preturn_manufacturer != false || $preturn_download != false || $preturn_review != false || $preturn_information != false ) { ?>
  <li id="catalog"><a class="parent"><i class="fa fa-tags fa-fw"></i> <span><?php echo $text_catalog; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_category) { ?>
      <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
      <?php } ?>
      <?php if($preturn_product) { ?>
      <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
      <?php } ?>
      <?php if($preturn_recurring) { ?>
      <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
      <?php } ?>
      <?php if($preturn_filter) { ?>
      <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li>
      <?php } ?>
      <?php if( $preturn_attribute != false || $preturn_attribute_group != false ) { ?>
      <li><a class="parent"><?php echo $text_attribute; ?></a>
        <ul>
          <?php if($preturn_attribute) { ?>
          <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
          <?php } ?>
          <?php if($preturn_attribute_group) { ?>
          <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($preturn_option) { ?>
      <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
      <?php } ?>
      <?php if($preturn_manufacturer) { ?>
      <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
      <?php } ?>
      <?php if($preturn_download) { ?>
      <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
      <?php } ?>
      <?php if($preturn_review) { ?>
      <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
      <?php } ?>
      <?php if($preturn_information) { ?>
      <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_order != false || $preturn_order_recurring != false || $preturn_return != false || $preturn_customer != false || $preturn_customer_group != false || $preturn_customer_ban_ip != false || $preturn_custom_field != false || $preturn_paypal != false ) { ?>
  <li id="sale"><a class="parent"><i class="fa fa-shopping-cart fa-fw"></i> <span><?php echo $text_sale; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_order) { ?>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <?php } ?>
      <?php if($preturn_order_recurring) { ?>
      <li><a href="<?php echo $order_recurring; ?>"><?php echo $text_order_recurring; ?></a></li>
      <?php } ?>
      <?php if($preturn_return) { ?>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <?php } ?>
      <?php if($preturn_paypal) { ?>
      <li><a class="parent"><?php echo $text_paypal ?></a>
        <ul>
          <li><a href="<?php echo $paypal_search ?>"><?php echo $text_paypal_search ?></a></li>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_customer != false || $preturn_customer_group != false || $preturn_customer_ban_ip != false || $preturn_custom_field != false ) { ?>
  <li><a class="parent"><i class="fa fa-user fa-fw"></i> <span><?php echo $text_customer; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_customer) { ?>
      <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
      <?php } ?>
      <?php if($preturn_customer_group) { ?>
      <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
      <?php } ?>
      <?php if($preturn_custom_field) { ?>
      <li><a href="<?php echo $custom_field; ?>"><?php echo $text_custom_field; ?></a></li>
      <?php } ?>
      <?php if($preturn_customer_ban_ip) { ?>
      <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_marketing != false || $preturn_affiliate != false || $preturn_coupon != false || $preturn_voucher != false || $preturn_voucher_theme != false || $preturn_contact != false ) { ?>
  <li><a class="parent"><i class="fa fa-share-alt fa-fw"></i> <span><?php echo $text_marketing; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_marketing) { ?>
      <li><a href="<?php echo $marketing; ?>"><?php echo $text_campaign; ?></a></li>
      <?php } ?>
      <?php if($preturn_affiliate) { ?>
      <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
      <?php } ?>
      <?php if($preturn_coupon) { ?>
      <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
      <?php } ?>
      <?php if( $preturn_voucher != false || $preturn_voucher_theme != false ) { ?>
      <li><a class="parent"><?php echo $text_voucher; ?></a>
        <ul>
          <?php if($preturn_voucher) { ?>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <?php } ?>
          <?php if($preturn_voucher_theme) { ?>
          <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($preturn_contact) { ?>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_sale_order != false || $preturn_sale_tax != false || $preturn_sale_shipping != false || $preturn_sale_return != false || $preturn_sale_coupon != false || $preturn_product_viewed != false || $preturn_product_purchased != false || $preturn_customer_online != false || $preturn_customer_activity != false || $preturn_customer_order != false || $preturn_customer_reward != false || $preturn_customer_credit != false || $preturn_marketing != false || $preturn_affiliate != false || $preturn_affiliate_activity != false ) { ?>
  <li id="reports"><a class="parent"><i class="fa fa-bar-chart-o fa-fw"></i> <span><?php echo $text_reports; ?></span></a>
    <ul class="collapse">
      <?php if( $preturn_sale_order != false || $preturn_sale_tax != false || $preturn_sale_shipping != false || $preturn_sale_return != false || $preturn_sale_coupon != false ) { ?>
      <li><a class="parent"><?php echo $text_sale; ?></a>
        <ul>
          <?php if($preturn_sale_order) { ?>
          <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
          <?php }?>
          <?php if($preturn_sale_tax) { ?>
          <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
          <?php } ?>
          <?php if($preturn_sale_shipping) { ?>
          <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
          <?php }?>
          <?php if($preturn_sale_return) { ?>
          <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
          <?php } ?>
          <?php if($preturn_sale_coupon) { ?>
          <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if( $preturn_product_viewed != false || $preturn_product_purchased != false ) {?>
      <li><a class="parent"><?php echo $text_product; ?></a>
        <ul>
          <?php if($preturn_product_viewed) { ?>
          <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
          <?php } ?>
          <?php if($preturn_product_purchased) { ?>
          <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if( $preturn_customer_online != false || $preturn_customer_activity != false || $preturn_customer_order != false || $preturn_customer_reward != false || $preturn_customer_credit != false ) { ?>
      <li><a class="parent"><?php echo $text_customer; ?></a>
        <ul>
          <?php if($preturn_customer_online) { ?>
          <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
          <?php } ?>
          <?php if($preturn_customer_activity) { ?>
          <li><a href="<?php echo $report_customer_activity; ?>"><?php echo $text_report_customer_activity; ?></a></li>
          <?php } ?>
          <?php if($preturn_customer_order) { ?>
          <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
          <?php } ?>
          <?php if($preturn_customer_reward) { ?>
          <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
          <?php } ?>
          <?php if($preturn_customer_credit) { ?>
          <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php }?>
      <?php if( $preturn_marketing != false || $preturn_affiliate != false || $preturn_affiliate_activity != false ) { ?>
      <li><a class="parent"><?php echo $text_marketing; ?></a>
        <ul>
          <?php if($preturn_marketing) { ?>
          <li><a href="<?php echo $report_marketing; ?>"><?php echo $text_marketing; ?></a></li>
          <?php } ?>
          <?php if($preturn_affiliate) { ?>
          <li><a href="<?php echo $report_affiliate; ?>"><?php echo $text_report_affiliate; ?></a></li>
          <?php } ?>
          <?php if($preturn_affiliate_activity) { ?>
          <li><a href="<?php echo $report_affiliate_activity; ?>"><?php echo $text_report_affiliate_activity; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_appearance_customizer != false ||  $preturn_appearance_layout != false || $preturn_appearance_menu != false ||  $preturn_design_banner != false ) { ?>
  <li id="appearance"><a class="parent"><i class="fa fa-desktop fa-fw"></i> <span><?php echo $text_appearance; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_appearance_customizer) { ?>
      <li><a href="<?php echo $customizer; ?>"><?php echo $text_customizer; ?></a></li>
      <?php } ?>
      <?php if($preturn_appearance_layout) { ?>
      <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li>
      <?php } ?>
      <?php if($preturn_appearance_menu) { ?>
      <li><a href="<?php echo $menu; ?>"><?php echo $text_menu; ?></a></li>
      <?php } ?>
      <?php if($preturn_design_banner) { ?>
      <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if($preturn_marketplace || $preturn_payment != false || $preturn_shipping != false || $preturn_total != false || $preturn_feed != false) { ?>
  <li id="marketplace"><a class="parent" href="<?php echo $marketplace; ?>"><i class="fa fa-puzzle-piece fa-fw"></i> <span><?php echo $text_marketplace; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_payment) { ?>
      <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
      <?php } ?>
      <?php if($preturn_shipping) { ?>
      <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
      <?php } ?>
      <?php if($preturn_total) { ?>
      <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
      <?php } ?>
      <?php if($preturn_feed) { ?>
      <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_localisation != false || $preturn_language != false || $preturn_currency != false || $preturn_order_status != false || $preturn_return_status != false || $preturn_return_action != false || $preturn_return_reason != false || $preturn_tax_rate != false || $preturn_tax_class != false || $preturn_zone != false || $preturn_country != false || $preturn_weight_class != false || $preturn_length_class != false || $preturn_geo_zone != false || $preturn_stock_status != false ) {?>
  <li><a class="parent"><i class="fa fa-flag fa-fw"></i> <span><?php echo $text_localisation; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_localisation) { ?>
      <li><a href="<?php echo $localisation; ?>"><?php echo $text_localisation; ?></a></li>
      <?php } ?>
      <?php if($preturn_language) { ?>
      <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
      <?php } ?>
      <?php if($preturn_currency) { ?>
      <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
      <?php } ?>
      <?php if($preturn_stock_status) { ?>
      <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
      <?php } ?>
      <?php if($preturn_order_status) { ?>
      <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
      <?php } ?>
      <?php if( $preturn_return_status != false || $preturn_return_action != false || $preturn_return_reason != false ) { ?>
      <li><a class="parent"><?php echo $text_return; ?></a>
        <ul>
          <?php if($preturn_return_status) { ?>
          <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
          <?php } ?>
          <?php if($preturn_return_action) { ?>
          <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
          <?php } ?>
          <?php if($preturn_return_reason) { ?>
          <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
          <?php }?>
        </ul>
      </li>
      <?php } ?>
      <?php if($preturn_country) { ?>
      <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
      <?php } ?>
      <?php if($preturn_zone) { ?>
      <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
      <?php } ?>
      <?php if($preturn_geo_zone) { ?>
      <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
      <?php } ?>
      <?php if( $preturn_tax_rate != false || $preturn_tax_class != false ) { ?>
      <li><a class="parent"><?php echo $text_tax; ?></a>
        <ul>
          <?php if($preturn_tax_class) { ?>
          <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
          <?php } ?>
          <?php if($preturn_tax_rate) { ?>
          <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($preturn_length_class) { ?>
      <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
      <?php } ?>
      <?php if($preturn_weight_class) { ?>
      <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_setting != false || $preturn_user != false || $preturn_user_permission != false || $preturn_user_api != false || $preturn_email_template != false || $preturn_language_override != false ) { ?>
  <li id="system"><a class="parent"><i class="fa fa-cog fa-fw"></i> <span><?php echo $text_system; ?></span></a>
    <ul class="collapse">
      <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
      <li><a href="<?php echo $store; ?>"><?php echo $text_store; ?></a></li>
      <?php if( $preturn_user != false || $preturn_user_permission != false || $preturn_user_api != false ) { ?>
      <li><a class="parent"><?php echo $text_users; ?></a>
        <ul>
          <?php if($preturn_user) { ?>
          <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
          <?php } ?>
          <?php if($preturn_user_permission) { ?>
          <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <?php } ?>
      <?php if($preturn_user_api) { ?>
      <li><a href="<?php echo $api; ?>"><?php echo $text_api; ?></a></li>
      <?php }?>
      <?php if($preturn_email_template) { ?>
      <li><a href="<?php echo $email_template; ?>"><?php echo $text_email_template; ?></a></li>
      <?php } ?>
      <?php if($preturn_language_override) { ?>
      <li><a href="<?php echo $language_override; ?>"><?php echo $text_language_override; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <?php if( $preturn_export_import != false || $preturn_backup != false || $preturn_error_log != false || $preturn_file_manager != false || $preturn_upload != false ) { ?>
  <li id="tools"><a class="parent"><i class="fa fa-wrench fa-fw"></i> <span><?php echo $text_tools; ?></span></a>
    <ul class="collapse">
      <?php if($preturn_backup) { ?>
      <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
      <?php } ?>
      <?php if($preturn_export_import) { ?>
      <li><a href="<?php echo $export_import; ?>"><?php echo $text_export_import; ?></a></li>
      <?php } ?>
      <?php if($preturn_file_manager) { ?>
      <li><a href="<?php echo $file_manager; ?>"><?php echo $text_file_manager; ?></a></li>
      <?php } ?>
      <?php if($preturn_upload) { ?>
      <li><a href="<?php echo $upload; ?>"><?php echo $text_upload; ?></a></li>
      <?php } ?>
      <?php if($preturn_error_log) { ?>
      <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
      <?php } ?>
    </ul>
  </li>
  <?php } ?>
  <li id="menu-collapse"><a href="#" onclick="return false;" id="button-menu"><i class="fa fa-play-circle rotate-collapse"></i> <span><?php echo $text_collapse; ?></span></a></li>
</ul>
