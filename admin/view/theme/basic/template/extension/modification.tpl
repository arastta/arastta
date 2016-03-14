<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></a>
                <a href="<?php echo $clear; ?>" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-default"><i class="fa fa-eraser"></i></a>
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
        <div class="row">
            <div class="left-col col-sm-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="general">  
                            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-modification">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <td style="width: 70px;" class="text-center">
                                                <div class="bulk-action">
                                                    <input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
                                                    <span class="bulk-caret"><i class="fa fa-caret-down"></i></span>
                                                    <span class="item-selected"></span>
                                                    <span class="bulk-action-button">
                                                      <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                                                          <b><?php echo $text_bulk_action; ?></b>
                                                          <span class="caret"></span>
                                                      </a>
                                                      <ul class="dropdown-menu dropdown-menu-left alerts-dropdown">
                                                          <li class="dropdown-header"><?php echo $text_bulk_action; ?></li>
                                                          <li><a onclick="confirmItem('<?php echo $text_confirm_title; ?>', '<?php echo $text_confirm; ?>');"><i class="fa fa-trash-o"></i> <?php echo $button_delete; ?></a></li>
                                                      </ul>
                                                    </span>
                                                </div></td>
                                            <td class="text-left"><?php if ($sort == 'name') { ?>
                                                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                                                <?php } ?></td>
                                            <td class="text-left"><?php if ($sort == 'author') { ?>
                                                <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                                                <?php } ?></td>
                                            <td class="text-left"><?php if ($sort == 'version') { ?>
                                                <a href="<?php echo $sort_version; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_version; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $sort_version; ?>"><?php echo $column_version; ?></a>
                                                <?php } ?></td>
                                            <td class="text-left"><?php if ($sort == 'type') { ?>
                                                <a href="<?php echo $sort_type; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_type; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $sort_type; ?>"><?php echo $column_type; ?></a>
                                                <?php } ?></td>
                                            <td class="text-left"><?php if ($sort == 'status') { ?>
                                                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                                                <?php } else { ?>
                                                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                                                <?php } ?></td>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if ($modifications) { ?>
                                        <?php foreach ($modifications as $modification) { ?>
                                        <?php if( empty($modification['modification_id']) ) {
                                                  $modification_id = explode('.', $modification['vqmod_id']); $modification_id = $modification_id[0]; 
                                              } else {
                                                  $modification_id = explode('.', $modification['modification_id']) ; $modification_id = $modification_id[0]; 
                                              } ?>
                                        <tr id="remove_<?php echo $modification_id;?>">
                                            <td class="text-center"><?php if (isset($modification['modification_id']) and in_array($modification['modification_id'], $selected)) { ?>
                                                <input type="checkbox" name="selected[]" value="<?php echo  $modification['modification_id'] ; ?>" checked="checked" />
                                                <?php } else if (isset($modification['vqmod_id']) && (in_array($modification['vqmod_id'], $selected))){ ?>
                                                <input type="checkbox" name="selected[]" value="<?php echo $modification['vqmod_id'] ; ?>"  checked="checked" />
                                                <?php }  else { ?>
                                                <input type="checkbox" name="selected[]" value="<?php echo empty($modification['modification_id']) ? $modification['vqmod_id'] : $modification['modification_id']; ?>" />
                                                <?php } ?></td>
                                            <td class="text-left">
                                                <?php if ($modification['link']) { ?>
                                                <a href="<?php echo $modification['link']; ?>" data-toggle="tooltip" title="<?php echo $button_link; ?>" class="btn btn-info btn-sm btn-basic-list" target="_blank"><i class="fa fa-link"></i></a>
                                                <?php } else { ?>
                                                <button type="button" class="btn btn-info btn-sm btn-basic-list" disabled="disabled"><i class="fa fa-link"></i></button>
                                                <?php } ?>
                                                <?php if (!$modification['enabled']) { ?>
                                                <a onClick="changeModificationStatus('<?php echo $modification['enable']; ?>', '<?php echo $modification_id; ?>');" id="<?php echo $modification_id; ?>"  data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success btn-sm btn-basic-list"><i class="fa fa-plus-circle"></i></a>
                                                <?php } else { ?>
                                                <a onClick="changeModificationStatus('<?php echo $modification['disable']; ?>', '<?php echo $modification_id; ?>');" id="<?php echo $modification_id; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-danger btn-sm btn-basic-list"><i class="fa fa-minus-circle"></i></a>
                                                <?php } ?>
                                                <?php echo $modification['name']; ?><br /><div class="text-danger"><?php echo $modification['invalid_xml']; ?></div></td>
                                            <td class="text-left"><?php echo $modification['author']; ?></td>
                                            <td class="text-left"><?php echo $modification['version']; ?></td>
                                            <td class="text-left"><?php echo $modification['type']; ?></td>
                                            <td class="text-left"><?php echo $modification['status']; ?></td>
                                        </tr>
                                        <?php } ?>
                                        <?php } else { ?>
                                        <tr>
                                            <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
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
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $tab_log; ?></h3>
                        <div class="pull-right">
                            <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="log">
                            <p>
                                <textarea wrap="off" rows="15" class="form-control"><?php echo $log ?></textarea>
                            </p>
                            <div class="text-right"><a href="<?php echo $clear_log; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear ?></a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
    $('.panel-chevron').trigger('click');
});

function changeModificationStatus(url ,id) {
    var html = "";
    $.ajax({
        url: url,
        dataType: 'json',
        beforeSend: function() {
            $('#button-clear').button('loading');
        },
        complete: function() {
            $('#button-clear').button('reset');
        },
        success: function(json) {
            $('.alert').remove();

            if (json['error'] != 0) {
                $('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
            }

            if (json['success'] != 0) {
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                $('#button-clear').prop('disabled', true);
            }

            if (json['status'] == 1) {
                if(json['enable'] == 1) {
                    html  = '<a onClick="changeModificationStatus(\'' + json['link'] + '\', \'' + id + '\');"';
                    html += 'id="' + id + '"';
                    html += 'data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-danger btn-sm btn-basic-list">';
                    html += '<i class="fa fa-minus-circle"></i></a>';
                    $('#'+id).after(html);
                    $('#'+id).remove();
                }

                if(json['disable'] == 1) {
                    html  = '<a onClick="changeModificationStatus(\'' + json['link'] + '\', \'' + id + '\');"';
                    html += 'id="' + id + '"';
                    html += 'data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success btn-sm btn-basic-list">';
                    html += '<i class="fa fa-plus-circle"></i></a>';
                    $('#'+id).after(html);
                    $('#'+id).remove();
                }
            }

            var loading  = '<div class="loading modification_loading"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;   margin-top: 20% !important;"></i>';
            loading += '<div class="alert alert-info" style="width:60% !important; margin-left: 20%;"><i class="fa fa-info-circle"></i> <?php echo $text_refresh; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>';
            loading += '</div>';

            $('#content > .container-fluid').prepend(loading);

            $.ajax({
                url: 'index.php?route=extension/modification/refresh&extensionInstaller=1&token=<?php echo $token; ?>',
                dataType: 'json',
                success: function(json) {
                    $('.alert').remove();

                    if (json['notice']) {
                        $('.modification_loading').remove();
                        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        $('#button-clear').prop('disabled', true);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });

}

function deleteFile() {
    $.ajax({
        url: 'index.php?route=extension/modification/delete&extensionInstaller=1&token=<?php echo $token; ?>',
        type: 'POST',
        data: $('#form-modification').serialize(),
        dataType: 'json',
        success: function(json) {
            $('.alert').remove();

            if (json['success']) {
                $('.modification_loading').remove();
                $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                $('#button-clear').prop('disabled', true);
            }

            if(json['files']) {
                $.each( json['files'], function( i, val ) {
                    files_id = val.split('.xml');
                    files_id = files_id[0];
                    $('#remove_'+files_id).remove();
                });
            }

            var loading  = '<div class="loading modification_loading"><i class="fa fa-spinner fa-spin" style="font-size: 16em !important;   margin-top: 20% !important;"></i>';
            loading += '<div class="alert alert-info" style="width:60% !important; margin-left: 20%;"><i class="fa fa-info-circle"></i> <?php echo $text_refresh; ?><button type="button" class="close" data-dismiss="alert">&times;</button></div>';
            loading += '</div>';

            $('#content > .container-fluid').prepend(loading);

            $.ajax({
                url: 'index.php?route=extension/modification/refresh&extensionInstaller=1&token=<?php echo $token; ?>',
                dataType: 'json',
                success: function(json) {
                    $('.alert').remove();

                    if (json['notice']) {
                        $('.modification_loading').remove();
                        $('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                        $('#button-clear').prop('disabled', true);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}
//--></script>
<?php echo $footer; ?>
