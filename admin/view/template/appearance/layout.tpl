<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo  $extension_module; ?>" data-toggle="tooltip" title="<?php echo $button_module; ?>" class="btn btn-default" data-original-title="<?php echo $button_module; ?>"><i class="fa fa-cubes"></i></a>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-layout" class="form-horizontal">
          <div class="row layout-builder" id="layout-builder">
            <div class="col-md-3 col-sm-4 hidden-xs pull-left" style="padding-left: 40px;">
              <div class="row">
                <div class="layout">
                  <div class="col-md-7 col-sm-7 layout-list">
                    <select type="text" name="change_layouts" id="change_layouts" class="form-control with-nav">
                      <option value="0"><?php echo $entry_addnew;?></option>
                      <?php foreach($layouts as $layout) { ?>
                      <option value="<?php echo $layout['layout_id']; ?>" <?php if( $change_layouts == $layout['layout_id'] ) { echo "selected=selected"; $name = $layout['name']; } ?>><?php echo $layout['name']; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                  <div class="col-sm-5 layout-buttons">
                    <a class="btn btn-default edit-layout" href="<?php echo $edit . '&layout_id=' . $layout_id ; ?>" data-toggle="tooltip" title="<?php echo $text_edit;?>"><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-primary add-layout" href="<?php echo $add ; ?>" data-toggle="tooltip" title="<?php echo $text_add;?>"><i class="fa fa-plus"></i></a>
                    <button type="button" data-toggle="tooltip" title="" class="btn btn-danger" onclick="confirm('Are you sure?') ? removeLayout('<?php echo $layout_id; ?>') : false;" data-original-title="Layout Delete"><i class="fa fa-trash-o"></i></button>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="left-module-block">
                  <div id="module_list" class="module_list" data-text-confirm="<?php echo $text_confirm;?>" data-text-edit="<?php echo $text_edit;?>">
                    <div class="heading-accordion">&nbsp;<?php echo $text_module; ?></div>
                    <div class="module_accordion accordion">
                      <?php echo $module_list_html; ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-9 col-sm-8" style="top: 5px;">
              <div class="accordion-content-drop">
                <div class="container-fluid">
                  <div class="row colsliders">
                    <div class="col-md-12 position">
                      <div class="layout-page-header layout-page-header-bg"><span><?php echo $text_header;?></span></div>
                    </div>
                  </div>
                  <?php $module_id = 0;?>
                  <div class="row colsliders">
                    <div class="col-md-3 position sidebar_column">
                      <div class="layout-page-header"><span><?php echo $text_column_left; ?></span>
                        <div class="btn-group hidden-lg hidden-md">
                          <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=column_left&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                        </div>
                      </div>
                      <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="column_left">
                        <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                        <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules']['column_left'])) { ?>
                        <?php foreach($layouts[$layout_id]['modules']['column_left'] as $column_left) { ?>
                        <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $column_left['code']; ?>" style="display: block;">
                          <div class="mblock-header">
                            <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $column_left['name']; ?></div>
                          </div>
                          <div class="mblock-control-menu ui-sortable-handle">
                            <div class="mblock-action">
                              <a class="btn btn-xs btn-edit" href="<?php echo $column_left['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                            </div>
                            <div class="mblock-action pull-right">
                              <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $column_left['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                            </div>
                          </div>
                          <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $column_left['code']; ?>">
                          <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $column_left['position']; ?>">
                          <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $column_left['sort_order']; ?>" class="sort">
                          <?php $module_id++; ?>
                        </div>
                        <?php } ?>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="row">
                        <div class="col-md-12 position main_column">
                          <div class="layout-page-header"><span><?php echo $text_content_top; ?></span>
                            <div class="btn-group hidden-lg hidden-md">
                              <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=content_top&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                            </div>
                          </div>
                          <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="content_top">
                            <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                            <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules']['content_top'])) { ?>
                            <?php foreach($layouts[$layout_id]['modules']['content_top'] as $content_top) { ?>
                            <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $content_top['code']; ?>" style="display: block;">
                              <div class="mblock-header">
                                <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $content_top['name']; ?></div>
                              </div>
                              <div class="mblock-control-menu ui-sortable-handle">
                                <div class="mblock-action">
                                  <a class="btn btn-xs btn-edit" href="<?php echo $content_top['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                </div>
                                <div class="mblock-action pull-right">
                                  <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $content_top['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                </div>
                              </div>
                              <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $content_top['code']; ?>">
                              <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $content_top['position']; ?>">
                              <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $content_top['sort_order']; ?>" class="sort">
                              <?php $module_id++; ?>
                            </div>
                            <?php } ?>
                            <?php } ?>
                          </div>
                        </div>
                        <div class="col-md-12 position main_column">
                          <div class="layout-page-header"><span><?php echo $text_content_bottom; ?></span>
                            <div class="btn-group hidden-lg hidden-md">
                              <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=content_bottom&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                            </div>
                          </div>
                          <div class="dashed dashed-module-list ui-droppable ui-sortable" data-position="content_bottom">
                            <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                            <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules']['content_bottom'])) { ?>
                            <?php foreach($layouts[$layout_id]['modules']['content_bottom'] as $content_bottom) { ?>
                            <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $content_bottom['code']; ?>" style="display: block;">
                              <div class="mblock-header">
                                <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $content_bottom['name']; ?></div>
                              </div>
                              <div class="mblock-control-menu ui-sortable-handle">
                                <div class="mblock-action">
                                  <a class="btn btn-xs btn-edit" href="<?php echo $content_bottom['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                                </div>
                                <div class="mblock-action pull-right">
                                  <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $content_bottom['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                                </div>
                              </div>
                              <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $content_bottom['code']; ?>">
                              <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $content_bottom['position']; ?>">
                              <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $content_bottom['sort_order']; ?>" class="sort">
                              <?php $module_id++; ?>
                            </div>
                            <?php } ?>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-3 position sidebar_column">
                      <div class="layout-page-header"><span><?php echo $text_column_right; ?></span>
                        <div class="btn-group hidden-lg hidden-md">
                          <a class="btn btn-xs btn-success btn-res-edit" href="<?php echo $responsive_module . '&position=column_right&layout_id=' . $layout_id; ?>" data-type="iframe" data-title="<?php echo $entry_modules;?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_add_modules ;?>"><i class="fa fa-plus"></i></a>
                        </div>
                      </div>
                      <div class="dashed ui-droppable ui-sortable" data-position="column_right">
                        <div class="pull-center"><i><?php echo $text_drag_and_drop; ?></i></div>
                        <?php if(!empty($layout_id) && !empty($layouts[$layout_id]['modules']['column_right'])) { ?>
                        <?php foreach($layouts[$layout_id]['modules']['column_right'] as $column_right) { ?>
                        <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $column_right['code']; ?>" style="display: block;">
                          <div class="mblock-header">
                            <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i> <?php echo $column_right['name']; ?></div>
                          </div>
                          <div class="mblock-control-menu ui-sortable-handle">
                            <div class="mblock-action">
                              <a class="btn btn-xs btn-edit" href="<?php echo $column_right['link']; ?>" data-toggle="tooltip" title="" data-original-title="<?php echo $entry_edit_modules; ?>"><i class="fa fa-cog"></i></a>
                            </div>
                            <div class="mblock-action pull-right">
                              <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_confirm;?>') ? removeLayoutModule('<?php echo $layout_id; ?>', '<?php echo $column_right['layout_module_id']; ?>', $(this)) : false;"><i class="fa fa-trash-o"></i></a>
                            </div>
                          </div>
                          <input type="hidden" name="layout_module[<?php echo $module_id; ?>][code]" value="<?php echo $column_right['code']; ?>">
                          <input type="hidden" name="layout_module[<?php echo $module_id; ?>][position]" class="layout_position" value="<?php echo $column_right['position']; ?>">
                          <input type="hidden" name="layout_module[<?php echo $module_id; ?>][sort_order]" value="<?php echo $column_right['sort_order']; ?>" class="sort">
                          <?php $module_id++; ?>
                        </div>
                        <?php } ?>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="row colsliders">
                    <div class="col-md-12 position">
                      <div class="layout-page-header layout-page-header-bg"><span><?php echo $text_footer; ?></span></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div id="data_index" data-index="<?php echo $module_id;?>"></div>

          <input type="hidden" name="layout_id" value="<?php echo $layout_id; ?>">
          <input type="hidden" name="name" value="<?php echo !empty($name)? $name : ''; ?>">
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $(document).ready(function() {
    Layout.init();

    $("#change_layouts").change(function() {
      window.location.href = '<?php echo $refresh; ?>&layout_id=' + $(this).val();
    });

    var moduleListHeigth = ($('.accordion').height()) - 30;

    var moduleCol = (moduleListHeigth / 2) - 27;

    $('.col-md-3 .dashed').attr('style','min-height:' + moduleListHeigth + 'px !important;');
    $('.col-md-6 .dashed').attr('style','min-height:' + moduleCol + 'px !important;');

    $('.btn-res-edit').on('click', function(event) {
      event.preventDefault();
      var data_href = $(this).attr('href');
      $('#res-model-large').attr('src',data_href);
      $('#res-module-modal').modal('show');
    });

    $('#res-model-large').on('load', function(event) {
      event.preventDefault();
      var iframe = $('#res-model-large');
      var current_url = document.getElementById("res-model-large").contentWindow.location.href;

      if(current_url != 'about:blank'){
        $('#res-module-modal-loading').addClass('loading_iframe');

        iframe.contents().find('.page-header [href]').on('click', function(event) {
          $('#res-module-modal-loading').addClass('loading_iframe');
        });

        iframe.contents().find('form').on('submit', function(event) {
          $('#res-module-modal-loading').addClass('loading_iframe');
        });

        if (current_url.indexOf('position') < 0) {
          $('#res-module-modal').modal('hide');
          $('body').removeClass('modal-open');

          window.location.href='<?php echo str_replace("&amp;","&",$refresh); ?>&layout_id=<?php echo $layout_id; ?>';
        } else {
          iframe.contents().find('html,body').css({
            height: 'auto'
          });

          iframe.contents().find('#header, #content .page-header .breadcrumb, #column-left, #footer').hide();
          iframe.contents().find('#content').css({marginLeft: '0px'});
          iframe.contents().find('#content').css({padding: '10px 0 0 0'});

          $('#res-module-modal-loading').removeClass('loading_iframe');
        }
      }
    });
  });

  function removeModule(module_id, id) {
    $.ajax({
      url: 'index.php?route=appearance/layout/removeModule&token=<?php echo $token;?>',
      type: 'post',
      data: {module_id: module_id},
      dataType: 'json',
      cache: false,
      success: function(json) {
        if (json['success'] == 1) {
          $('#' + id).remove();
        } else {
          alert(json['error_warning']);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }

  function removeLayoutModule(layout_id, layout_module_id, module) {
    $.ajax({
      url: 'index.php?route=appearance/layout/removeLayoutModule&token=<?php echo $token;?>',
      type: 'post',
      data: {layout_id: layout_id, layout_module_id: layout_module_id},
      dataType: 'json',
      cache: false,
      success: function(json) {
        if (json['success'] == 1) {
          module.parents('.mblock').remove();
        } else {
          alert(json['error_warning']);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }

  function removeLayout(layout_id) {
    $.ajax({
      url: 'index.php?route=appearance/layout/removeLayout&token=<?php echo $token;?>',
      type: 'post',
      data: {layout_id: layout_id},
      dataType: 'json',
      cache: false,
      success: function(json) {
        if (json['success'] == 1) {
          window.location.href = '<?php echo $refresh; ?>';
        } else {
          alert(json['error_warning']);
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }

  function save(type) {
    var input = document.createElement('input');
    input.type = 'hidden';
    input.name = 'button';
    input.value = type;
    form = $("form[id^='form-']").append(input);
    form.submit();
  }

  var action_url = '<?php echo $action; ?>';
</script>
<div id="layout-add" class="modal-box modal fade">
  <div class="modal-dialog iframe-width">
    <div class="modal-content">
      <div class="modal-body">
        <iframe id="layout-add-iframe" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
      </div>
      <div id="layout-add-loading"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
      </div>
    </div>
  </div>
</div>
<div id="layout-edit" class="modal-box modal fade">
  <div class="modal-dialog iframe-width">
    <div class="modal-content">
      <div class="modal-body">
        <iframe id="layout-edit-iframe" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
      </div>
      <div id="layout-edit-loading"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
      </div>
    </div>
  </div>
</div>
<div id="module-modal" class="modal-box modal fade">
  <div class="modal-dialog iframe-width">
    <div class="modal-content">
      <div class="modal-body">
        <iframe id="model-large" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
      </div>
      <div id="module-modal-loading"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
      </div>
    </div>
  </div>
</div>
<div id="res-module-modal" class="modal-box modal fade">
  <div class="modal-dialog iframe-width">
    <div class="modal-content">
      <div class="modal-body">
        <iframe id="res-model-large" class="model-large" frameborder="0" allowtransparency="true" seamless=""></iframe>
      </div>
      <div id="res-module-modal-loading"></div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal"><?php echo $text_close; ?></button>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>