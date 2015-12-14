<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-facebook-store" data-toggle="tooltip" title="" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-facebook-store" data-toggle="tooltip" title="" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
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
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $help_ssl; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-facebook-store" class="form-horizontal">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                        <li><a href="#tab-items" data-toggle="tab"><?php echo $tab_items; ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-general">

                        </div>
                        <div class="tab-pane" id="tab-items">
                            <div class="form-group">
                                <div class="col-sm-6">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tab-modules" data-toggle="tab"><?php echo $tab_modules; ?></a></li>
                                        <li><a href="#tab-products" data-toggle="tab"><?php echo $tab_products; ?></a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab-modules">
                                            <?php foreach ($modules as $module) { ?>
                                            <div class="module-block ui-draggable ui-draggable-handle" data-code="<?php echo $module['code'] . '-' . $module['module_id']; ?>">
                                                <i class="fa fa-arrows-alt"></i><span class="module-name"><?php echo $module['name']; ?></span>
                                            </div>
                                            <?php } ?>
                                        </div>
                                        <div class="tab-pane" id="tab-products">
                                            <div id="search" class="input-group">
                                                <input type="text" name="search" value="" placeholder="Search" class="form-control input-lg" autocomplete="off"><ul class="dropdown-menu" style="padding: 2px; display: none;"></ul>
                                                <span class="input-group-btn">
                                                  <button type="button" disabled class="btn btn-default btn-lg"><i class="fa fa-search"></i></button>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="dashed dashed-module-list ui-droppable ui-sortable">
                                        <div class="pull-center"><i>Drag &amp; drop modules here</i></div>
                                        <?php foreach ($feeds as $feed) { ?>
                                        <div class="mblock ui-draggable ui-draggable-handle" data-code="<?php echo $feed['code'] . '-' . $feed['id'] ; ?>">
                                            <div class="mblock-header">
                                                <div class="mblock-header-title"><i class="fa fa-arrows-alt"></i>
                                                    <span class="module-name"><?php echo $feed['name'] ; ?></span>
                                                </div>
                                            </div>
                                            <div class="mblock-control-menu ui-sortable-handle">
                                                <div class="mblock-action pull-right">
                                                    <a class="btn btn-xs btn-remove" onclick="confirm('<?php echo $text_remove_feed; ?>') ? removeFeed($(this)):false;"><i class="fa fa-trash-o"></i></a>
                                                </div>
                                            </div>
                                            <input type="hidden" name="facebook_store_feed[]" value="<?php echo $feed['code'] . '-' . $feed['id'] ; ?>">
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript"><!--
var confirm_text = '<?php echo $text_remove_feed; ?>';

$(document).ready(function() {
    FaceBook.init();
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
