<div class="theme-overlay">
	<div class="theme-backdrop"></div>
	<div class="theme-wrap">
		<div class="theme-header">
			<button type="button" class="left" onclick="previous('<?php echo $theme['code']; ?>');" data-toggle="tooltip" data-original-title="<?php echo( 'Show previous theme' ); ?>"><i class="fa fa-chevron-left"></i></button>
			<button type="button" class="right" onclick="next('<?php echo $theme['code']; ?>');" data-toggle="tooltip" data-original-title="<?php echo( 'Show next theme' ); ?>"><i class="fa fa-chevron-right"></i></button>
			<button type="button" class="close" data-toggle="tooltip" data-original-title="<?php echo( 'Close overlay' ); ?>"><i class="fa fa-times"></i></button>
		</div>
		<div class="theme-about">
			<div class="theme-screenshots">
				<div class="screenshot"><img src="<?php echo $theme['thumb']; ?>" alt="" /></div>
			</div>

			<div class="theme-info">
				<?php if ($active_theme == $theme['code']) { ?>
					<span class="label label-success"><?php echo( 'Current Theme' ); ?></span>
				<?php } ?>
				<h3 class="theme-name"><?php echo $theme['name']; ?><span class="theme-version"><?php echo $theme['version']; ?></span></h3>
				<h4 class="theme-author"><?php echo $theme['author']; ?></h4>
				<hr>
				<p class="theme-description"><?php echo $theme['description']; ?></p>

				<?php /*if ( data.parent ) { ?>
					<p class="parent-theme"><?php printf( __( 'This is a child theme of %s.' ), '<strong>{{{ data.parent }}}</strong>' ); ?></p>
				<?php } ?>

				<?php if ( data.tags ) { ?>
					<p class="theme-tags"><span><?php echo( 'Tags:' ); ?></span> {{{ data.tags }}}</p>
				<?php }*/ ?>
			</div>
		</div>

		<div class="theme-actions">
		<?php if ( $active_theme == $theme['code'] ) { ?>
			<div class="active-theme">
				<a href="<?php echo $theme['customizer']; ?>" class="btn btn-primary btn-sm customize load-customize hide-if-no-customize"><?php echo $entry_customize; ?></a>
				<?php if ($action) { ?>
				<?php foreach ($action as $link) { ?>
					<a href="<?php echo $link['href']; ?>" class="btn btn-<?php echo $link['class']; ?> btn-sm"><?php echo $link['text']; ?></a>
				<?php } ?>
				<?php } ?>
			</div>
		<?php } else { ?>
			<div class="inactive-theme">
				<a href="<?php echo $theme['activate']; ?>" class="btn btn-default btn-sm activate"><?php echo $text_active; ?></a>
				<a href="<?php echo $theme['customizer']; ?>" class="btn btn-primary btn-sm load-customize hide-if-no-customize"><?php echo $text_preview; ?></a>
			</div>
			<?php } ?>

			<a href="<?php echo $theme['delete']; ?>" class="btn btn-danger btn-sm delete-theme"><?php echo $text_delete; ?></a>
		</div>
	</div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	var previous_theme = $('#theme-' + '<?php echo $theme["code"]; ?>').prev().attr('id');

	if (previous_theme == undefined) {
		$('.theme-header .left').addClass('disabled');
	}

	var next_theme = $('#theme-' + '<?php echo $theme["code"]; ?>').next().attr('id');

	if (next_theme == undefined) {
		$('.theme-header .right').addClass('disabled');
	}
});

$(".theme-backdrop, .theme-header .close").click(function () {
	$('.tooltip.fade.top.in').remove();
	$('.theme-overlay .theme-overlay').remove();
});
//--></script>