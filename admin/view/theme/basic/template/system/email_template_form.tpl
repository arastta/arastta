<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" onclick="save('save')" form="form-email-template" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-success" data-original-title="Save"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-email-template" data-toggle="tooltip" title="<?php echo $button_saveclose; ?>" class="btn btn-default" data-original-title="Save & Close"><i class="fa fa-save text-success"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-times-circle text-danger"></i></a>
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
        <div class="col-sm-8 left-col">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-email-template" class="form-horizontal">
                <div class="row">
                    <div class="left-col col-sm-12">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="general">
                                    <ul class="nav nav-tabs" id="language">
                                        <?php foreach ($languages as $language) { ?>
                                        <li><a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                    <div class="tab-content">
                                        <?php foreach ($languages as $language) { ?>
                                        <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                                            <div class="form-group required">
                                                <label class="col-sm-12" for="input-name<?php echo $language['language_id']; ?>"><?php echo $entry_name; ?></label>
                                                <div class="col-sm-12">
                                                    <input type="text" name="email_template_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($email_template_description[$language['language_id']]) ? $email_template_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name<?php echo $language['language_id']; ?>" class="form-control input-full-width" />
                                                    <?php if (isset($error_name[$language['language_id']])) { ?>
                                                    <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-12" for="input-description<?php echo $language['language_id']; ?>">
                                                    <?php echo $entry_description; ?>
                                                    <div class="html-button"><a href="<?php echo $html_preview . '&language_id=' . $language['language_id']; ?>" class="popup btn btn-primary btn-xs"><?php echo $text_html_preview; ?></a></div>
                                                </label>
                                                <div class="col-sm-12">
                                                    <textarea name="email_template_description[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="form-control"><?php echo isset($email_template_description[$language['language_id']]) ? $email_template_description[$language['language_id']]['description'] : ''; ?></textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-sm-4 right-col">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-code"></i> <?php echo $text_short_codes; ?></h3>
                    <div class="pull-right">
                        <div class="panel-chevron"><i class="fa fa-chevron-up rotate-reset"></i></div>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div>
                                <table class="table table-striped">
                                <?php foreach ($short_codes as $code) { ?>
                                    <tr>
                                    <td><span class="pull-right"><b><?php echo $code['code']; ?></b></span></td>
                                    <td><span style="margin-right: 5px;">=></span> <?php echo $code['text']; ?></td>
                                    </tr>
                                <?php } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css"><!--
    .jumbotron {
        padding-left: 20px !important;
        padding-right: 20px !important;
        padding-top: 20px;
        padding-bottom: 5px;
    }

    .html-button {
        margin-top: 10px;
    }

    @media (min-width: 768px) {
        .modal-dialog {
            width: 725px !important;
            margin: 30px auto;
        }
    }

//--></style>
<script type="text/javascript"><!--
$(document).ready(function() {
<?php foreach ($languages as $language) { ?>
textEditor('#input-description<?php echo $language['language_id']; ?>');
<?php } ?>
});

function shortCode(button) {
    $(".jumbotron").slideToggle();
}
//--></script>
<script type="text/javascript"><!--
$('#language a:first').tab('show');
//--></script>
<?php echo $footer; ?>
