<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
		<button type="button" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn btn-default" onclick="$('#form-product').attr('action', '<?php echo $copy; ?>').submit()"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-default" onclick="changeStatus(1)"><i class="fa fa-check-circle text-success"></i></button>
		<button type="button" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-default" onclick="changeStatus(0)"><i class="fa fa-times-circle text-danger"></i></button>
		<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-product').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
            <div class="col-lg-12">
              <div class="input-group">
                <div class="input-group-btn">
                  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <button type="button" onclick="filter();" class="btn btn-default"><div class="filter-type"><?php echo $entry_name; ?></div></button>
                  <ul class="dropdown-menu">
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_name; ?>', 'filter_name');"><?php echo $entry_name; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_model; ?>', 'filter_model');"><?php echo $entry_model; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $column_category; ?>', 'filter_category');"><?php echo $column_category; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_price; ?>', 'filter_price');"><?php echo $entry_price; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_quantity; ?>', 'filter_quantity');"><?php echo $entry_quantity; ?></a></li>
                    <li><a class="filter-list-type" onclick="changeFilterType('<?php echo $entry_status; ?>', 'filter_status');"><?php echo $entry_status; ?></a></li>
                  </ul>
                </div>
                <input type="text" name="filter_model"  value="<?php echo $filter_model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control hidden">
                <input type="text" name="filter_name"  value="<?php echo $filter_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control">
                <select name="filter_category" id="input-category" class="form-control hidden">
                  <option value="*"></option>
                  <?php foreach ($categories as $category) { ?>
                  <?php if ($category['category_id'] == $filter_category) { ?>
                  <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
                <input type="text" name="filter_price" value="<?php echo $filter_price; ?>" placeholder="<?php echo $entry_price; ?>" id="input-price" class="form-control hidden" />
                <input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" placeholder="<?php echo $entry_quantity; ?>" id="input-quantity" class="form-control hidden" />
                <select name="filter_status" id="input-status" class="form-control hidden">
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
            </div>
          </div>
        </div>
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-product">
          <div class="table-responsive">
            <table class="table table-hover">
              <thead class="table-name">
                <tr>
                  <td style="width: 70px;" class="text-center">
                    <div class="bulk-action">
                      <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                      <span>
                        <i class="fa fa-caret-down"></i>
                      </span>
                    </div></td>
                  <td class="text-center"><?php echo $column_image; ?></td>
                  <td class="text-left"><?php if ($sort == 'pd.name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($sort == 'p.quantity') { ?>
                    <a href="<?php echo $sort_quantity; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_quantity; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_quantity; ?>"><?php echo $column_quantity; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php if ($sort == 'p.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?></td>
                </tr>
              </thead>
              <thead class="table-quick-edit">
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-center" colspan="5"></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($products) { ?>
                <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($product['product_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-center"><?php if ($product['image']) { ?>
                    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" class="img-thumbnail" />
                    <?php } else { ?>
                    <span class="img-thumbnail list"><i class="fa fa-camera fa-2x"></i></span>
                    <?php } ?></td>
                  <td class="text-left">
                    <div class="col-sm-1">
                      <a href="<?php echo $product['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><i class="fa fa-pencil"></i></a>
                    </div>
                    <div class="col-sm-11">
                      <span class="product-name">
                        <?php echo $product['name']; ?>
                      </span>
                    </div>
                  </td>
                  <td class="text-left"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <div class="text-danger"><?php echo $product['special']; ?></div>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
                  <td class="text-right"><?php if ($product['quantity'] <= 0) { ?>
                    <span class="label label-warning"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span class="label label-danger"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span class="label label-success"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $product['status']; ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="9"><?php echo $text_no_results; ?></td>
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
  <style>
    .table-quick-edit > tr > td {
      vertical-align: bottom;
      border-bottom: 0px solid #dddddd !important;
      border-top: 0px solid #dddddd !important;
      display: none;
    }

    .bulk-action {
      border: 1px solid #ddd;
      border-radius: 3px;
      -webkit-appearance: none!important;
      -moz-appearance: none!important;
    }

    .bulk-action i {
      position: absolute;
      margin-left: 9px;
      margin-top: 6px;
      font-size: 12px;
    }

    input[type="radio"],
    .radio input[type="radio"],
    .radio-inline input[type="radio"],
    input[type="checkbox"],
    .checkbox input[type="checkbox"],
    .checkbox-inline input[type="checkbox"] {
      margin-left: -15px;
      width: 15px !important;
      height: 15px !important;
    }

    input[type="checkbox"]:checked::after,
    .checkbox input[type="checkbox"]:checked::after,
    .checkbox-inline input[type="checkbox"]:checked::after {
      top: -4px !important;
    }
  </style>
  <script type="text/javascript"><!--
  $('.bulk-action').on('click', function() {
  //  var check = $(this).children().find('input[type=\'checkbox\']').checked();

    if ($('.bulk-action input[type=\'checkbox\']').is(':checked')) {
      $('.bulk-action input[type=\'checkbox\']').prop('checked', false);
      $('input[name*=\'selected\']').prop('checked', false);
    } else {
      $('.bulk-action input[type=\'checkbox\']').prop('checked', true);
      $('input[name*=\'selected\']').prop('checked', true);

    }
  });

  function filter() {
    var url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

    var filter_name = $('input[name=\'filter_name\']').val();

    if (filter_name) {
      url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_model = $('input[name=\'filter_model\']').val();

    if (filter_model) {
      url += '&filter_model=' + encodeURIComponent(filter_model);
    }

    var filter_category = $('select[name=\'filter_category\']').val();

    if (filter_category != '*') {
      url += '&filter_category=' + encodeURIComponent(filter_category);
    }

    var filter_price = $('input[name=\'filter_price\']').val();

    if (filter_price) {
      url += '&filter_price=' + encodeURIComponent(filter_price);
    }

    var filter_quantity = $('input[name=\'filter_quantity\']').val();

    if (filter_quantity) {
      url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    }

    var filter_status = $('select[name=\'filter_status\']').val();

    if (filter_status != '*') {
      url += '&filter_status=' + encodeURIComponent(filter_status);
    }

    location = url;
  }

  function changeFilterType(text, filter_type) {
      $('.filter-type').text(text);

      if (filter_type == 'filter_name') {
        $('input[name=\'filter_text\']').addClass('hidden');
        $('input[name=\'filter_model\']').addClass('hidden');
        $('input[name=\'filter_name\']').removeClass('hidden');
      } else if (filter_type == 'filter_model') {
        $('input[name=\'filter_text\']').addClass('hidden');
        $('input[name=\'filter_name\']').addClass('hidden');
        $('input[name=\'filter_model\']').removeClass('hidden');
      } else {
        $('input[name=\'filter_name\']').addClass('hidden');
        $('input[name=\'filter_model\']').addClass('hidden');
        $('input[name=\'filter_text\']').removeClass('hidden');
      }
  }
//--></script> 
  <script type="text/javascript"><!--
  $('input[name=\'filter_name\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['name'],
              value: item['product_id']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'filter_name\']').val(item['label']);
    }
  });

  $('input[name=\'filter_model\']').autocomplete({
    'source': function(request, response) {
      $.ajax({
        url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
        dataType: 'json',
        success: function(json) {
          response($.map(json, function(item) {
            return {
              label: item['model'],
              value: item['product_id']
            }
          }));
        }
      });
    },
    'select': function(item) {
      $('input[name=\'filter_model\']').val(item['label']);
    }
  });
//--></script></div>
  <script type="text/javascript"><!--
    function changeStatus(status){
      $.ajax({
        url: 'index.php?route=common/edit/changeStatus&type=product&status='+ status +'&token=<?php echo $token; ?>',
        dataType: 'json',
        data: $("form[id^='form-']").serialize(),
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
<?php echo $footer; ?>