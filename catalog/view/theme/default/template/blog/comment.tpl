<?php if ($comments) { ?>
<?php foreach ($comments as $comment) { ?>
<div class="comment-item">
	<div class="row">
		<div class="col-sm-2">
			<div class="commenter text-center">
				<img width="100" class="img-responsive img-circle center-block" src="http://xcdn.easyblog.stackideas.com/images/easyblog_avatar/565_blair.jpg" alt="" />
				<p>Enes Ertuğrul</p>
				<p><small>22.03.2017</small></p>
			</div>
		</div>
		<div class="col-sm-10">
			<div class="comment">
				<a href="#<?php echo $comment['comment_id']; ?>" class="permalink"><i class="fa fa-link"></i></a>
				Using color to add meaning only provides a visual indication, which will not be conveyed to users of assistive technologies – such as screen readers. Ensure that information denoted by the color is either obvious from the content itself (the contextual colors are only used to reinforce meaning that is already present in the text/markup), or is included through alternative means, such as additional text hidden with the .sr-only class.
			</div>
		</div>
	</div>
</div>
<?php } ?>
<div class="text-right"><?php echo $pagination; ?></div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>
