<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
        <div class="btn-group product-navigation pull-right hidden-xs">
            <button type="button" data-toggle="tooltip" class="btn btn-default" onclick="window.location.href='<?php echo !empty($previous["href"]) ? $previous["href"] : ''; ?>';" title="<?php echo !empty($previous['name']) ? $previous['name'] : ''; ?>" <?php echo empty($previous["href"]) ? 'disabled' : ''; ?>><i class="fa fa-arrow-left"></i></button>
            <button type="button" data-toggle="tooltip" class="btn btn-default" onclick="window.location.href='<?php echo !empty($next["href"]) ? $next["href"] : ''; ?>';" title="<?php echo !empty($next['name']) ? $next['name'] : ''; ?>" <?php echo empty($next["href"]) ? 'disabled' : ''; ?>><i class="fa fa-arrow-right"></i></button>
        </div>
    </ul>
    <div class="btn-group product-navigation-xs pull-right visible-xs">
        <button type="button" data-toggle="tooltip" class="btn btn-default" onclick="window.location.href='<?php echo !empty($previous["href"]) ? $previous["href"] : ''; ?>';" title="<?php echo !empty($previous['name']) ? $previous['name'] : ''; ?>" <?php echo empty($previous["href"]) ? 'disabled' : ''; ?>><i class="fa fa-arrow-left"></i></button>
        <button type="button" data-toggle="tooltip" class="btn btn-default" onclick="window.location.href='<?php echo !empty($next["href"]) ? $next["href"] : ''; ?>';" title="<?php echo !empty($next['name']) ? $next['name'] : ''; ?>" <?php echo empty($next["href"]) ? 'disabled' : ''; ?>><i class="fa fa-arrow-right"></i></button>
    </div>
    <div class="clearfix"></div>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <div class="ar-blog-item">
                <h1><a href="#"><?php echo $heading_title; ?></a></h1>
                <div class="meta">
                    <?php if ($author) { ?>
                    <a class="blog-author" href="#"><i class="fa fa-user"></i> <?php echo $author; ?></a>
                    <?php } ?>
                    <a class="blog-category" href="#"><i class="fa fa-bookmark"></i> General</a>
                    <?php if ($date_added) { ?>
                    <span class="blog-date"><i class="fa fa-calendar"></i> <?php echo $date_added; ?></span>
                    <?php } ?>
                    <?php if ($viewed) { ?>
                    <span class="blog-view"><i class="fa fa-eye"></i> <?php echo $viewed; ?></span>
                    <?php } ?>
                </div>
                <?php if ($thumb) { ?>
                <div class="blog-image">
                    <img src="<?php echo $thumb; ?>" width="300" class="img-responsive" alt="<?php echo $heading_title; ?>" />
                </div>
                <?php } ?>
                <div class="intro">
                    <?php echo $description; ?>
                </div>
                <?php if ($tags) { ?>
                <div class="ar-blog-tags">
                    <ul class="list-inline">
                        <?php foreach ($tags as $tag) { ?>
                        <li><a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a></li>
                        <?php } ?>
                    </ul>
                </div>
                <?php } ?>
                <?php if ($comment_status) { ?>
                <div class="ar-blog-comments">
                    <h3><i class="fa fa-comments"></i> <?php echo $text_comments; ?></h3>
                    <hr>
                    <div id="comment"></div>
                    <div class="comment-add">
                        <h3><i class="fa fa-pencil"></i> <?php echo $text_add_comment; ?></h3>
                        <form class="form-group" id="form-comment">
                            <?php if ($comment_guest) { ?>
                            <div class="row">
                                <div class="col-xs-3 form-group required">
                                    <label class="control-label"><?php echo $text_name; ?></label>
                                    <input type="text" name="name" value="<?php echo $comment_name; ?>" id="comment-name" class="form-control" placeholder="<?php echo $text_name; ?>">
                                </div>
                                <div class="col-xs-3 form-group required">
                                    <label class="control-label"><?php echo $text_email; ?></label>
                                    <input type="text" name="email" value="<?php echo $comment_email; ?>" id="comment-email" class="form-control" placeholder="<?php echo $text_email; ?>">
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-sm-6 form-group required">
                                    <label class="control-label"><?php echo $text_comment; ?></label>
                                    <textarea name="text" id="comment-text" class="form-control" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                <?php if ($captcha) { ?>
                                <?php echo $captcha; ?>
                                <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="buttons clearfix">
                                        <div class="pull-right">
                                            <button type="button" id="button-comment" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"><?php echo $button_continue; ?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } else { ?>
                            <?php echo $text_login; ?>
                            <?php } ?>
                        </form>
                    </div>
                </div>
                <?php } ?>
            </div>
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>
<script type="text/javascript"><!--
$('#comment').delegate('.pagination a', 'click', function(e) {
    e.preventDefault();

    $('#comment').fadeOut('slow');

    $('#comment').load(this.href);

    $('#comment').fadeIn('slow');
});

$(document).ready(function() {
    $.ajax({
        url: 'index.php?route=blog/post/comment&post_id=<?php echo $post_id; ?>',
        type: 'post',
        dataType: 'html',
        success: function(html) {
            $('#comment').html(html);

            hash = location.hash.replace('#','');

            if (hash != '') {
                $('html, body').animate({
                    scrollTop: $('#comment-' + hash).offset().top
                }, 1000);
            }
        }
    }).done(function() {

    });
});

$('#button-comment').on('click', function() {
    $.ajax({
        url: 'index.php?route=blog/post/write&post_id=<?php echo $post_id; ?>',
        type: 'post',
        dataType: 'json',
        data: $("#form-comment").serialize(),
        beforeSend: function() {
            $('#button-comment').button('loading');
        },
        complete: function() {
            $('#button-comment').button('reset');
        },
        success: function(json) {
            $('.alert-success, .alert-danger').remove();

            if (json['error']) {
                $('#comment').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
            }

            if (json['captcha_extension'] && (json['captcha_extension'] == 'basic')) {
                $('#input-basic-captcha').parent().parent().after(json['captcha_content']);
                $('#input-basic-captcha').parent().parent().remove();
            }

            if (json['success']) {
                $('#comment').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                $('input[name=\'name\']').val('');
                $('input[name=\'email\']').val('');
                $('textarea[name=\'text\']').val('');
            }
        }
    });
});
//--></script>
<?php echo $footer; ?>
