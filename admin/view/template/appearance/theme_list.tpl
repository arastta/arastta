<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-success"><i class="fa fa-plus"></i></a>
                <a href="<?php echo $upload; ?>" data-toggle="tooltip" title="<?php echo $button_upload; ?>" class="btn btn-default"><i class="fa fa-upload"></i></a>
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
        <form action="<?php echo $add; ?>" method="post" enctype="multipart/form-data" id="form-theme">
            <div class="theme-browser">
                <?php if ($themes) { ?>
                <?php foreach ($themes as $theme) { ?>
                <div class="theme<?php if ( $active_theme == $theme['code'] ) echo ' active'; ?>" id ="theme-<?php echo $theme['code']; ?>">
                    <div class="theme-screenshot">
                        <img src="<?php echo $theme['thumb']; ?>" alt="" />
                    </div>

                    <span class="more-details" id="more-details-<?php echo $theme['code']; ?>"><?php echo $text_details; ?></span>
                    <div class="theme-author"><?php echo $theme['author']; ?></div>

                    <?php if ( $active_theme == $theme['code'] ) { ?>
                    <h3 class="theme-name" id="theme-name-<?php echo $theme['code']; ?>"><?php echo $theme['name']; ?></h3>
                    <?php } else { ?>
                    <h3 class="theme-name" id="theme-name-<?php echo $theme['code']; ?>"><?php echo $theme['name']; ?></h3>
                    <?php } ?>

                    <div class="theme-actions">
                        <?php if ( $active_theme == $theme['code'] ) { ?>
                        <a class="btn btn-primary btn-sm customize load-customize hide-if-no-customize" href="<?php echo $theme['customizer']; ?>"><?php echo $button_customize; ?></a>
                        <?php if ($theme['action']) { ?>
                        <?php foreach ($theme['action'] as $action) { ?>
                        <a class="btn btn-default btn-sm " href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a>
                        <?php } ?>
                        <?php } ?>
                        <?php } else { ?>
                        <a class="btn btn-default btn-sm activate" href="<?php echo $theme['default']; ?>"><?php echo $button_default; ?></a>
                        <a class="btn btn-primary btn-sm load-customize hide-if-no-customize" href="<?php echo $theme['customizer']; ?>"><?php echo $button_preview; ?></a>
                        <?php } ?>
                    </div>
                    <input type="hidden" name="theme-<?php echo $theme['code']; ?>" id="<?php echo $theme['code']; ?>" value="<?php echo $theme['code']; ?>">
                </div>
                <?php } ?>
                <?php } ?>
                <div class="theme add-new-theme">
                    <a href="<?php echo $add; ?>"><div class="theme-screenshot"><span></span></div><h3 class="theme-name"><?php echo $button_new_theme; ?></h3></a>
                </div>
                <br class="clear" />
            </div>
    </div>
    <div class="theme-overlay"></div>
    </form>
</div>
</div>
<script type="text/javascript"><!--
function previous(code) {
    var theme = $('#theme-' + code).prev().attr('id');

    theme = theme.replace("theme-", "");

    $.ajax({
        url: 'index.php?route=appearance/theme/info&token=<?php echo $token; ?>',
        type: 'post',
        data: {theme: theme},
        dataType: 'html',
        success: function(html) {
            if (html) {
                $('.tooltip.fade.top.in').remove();
                $('.theme-overlay .theme-overlay').remove();
                $('.theme-overlay').append(html);
            } else {
                alert(html);
            }
        }
    });
}

function next(code) {
    var theme = $('#theme-' + code).next().attr('id');

    theme = theme.replace("theme-", "");

    $.ajax({
        url: 'index.php?route=appearance/theme/info&token=<?php echo $token; ?>',
        type: 'post',
        data: {theme: theme},
        dataType: 'html',
        success: function(html) {
            if (html) {
                $('.tooltip.fade.top.in').remove();
                $('.theme-overlay .theme-overlay').remove();
                $('.theme-overlay').append(html);
            } else {
                alert(html);
            }
        }
    });
}

$(".themes .theme .theme-screenshot, .more-details, .theme-name").click(function () {
    var theme = $(this).parent().find('input').attr('id');

    $.ajax({
        url: 'index.php?route=appearance/theme/info&token=<?php echo $token; ?>',
        type: 'post',
        data: {theme: theme},
        dataType: 'html',
        success: function(html) {
            if (html) {
                $('.theme-overlay').append(html);
            } else {
                alert(html);
            }
        }
    });
});
//--></script>
<?php echo $footer; ?>
