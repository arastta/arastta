<?php if ($comments) { ?>
<?php foreach ($comments as $comment) { ?>
<div id="comment-<?php echo $comment['comment_id']; ?>" class="comment-item">
    <div class="row">
        <div class="col-sm-2">
            <div class="commenter text-center">
                <img class="img-responsive img-circle center-block" src="<?php echo  $comment['image']; ?>" alt="<?php echo $comment['author']; ?>" />
                <p><?php echo $comment['author']; ?></p>
                <p><small><?php echo $comment['date_added']; ?></small></p>
            </div>
        </div>
        <div class="col-sm-10">
            <div class="comment">
                <a href="<?php echo $comment['href']; ?>" class="permalink"><i class="fa fa-link"></i></a>
                <?php echo $comment['text']; ?>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_comments; ?></p>
<?php } ?>
