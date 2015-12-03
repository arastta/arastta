<?php if ($text_footer or $text_version) { ?>
	<footer id="footer">
	<?php if ($text_footer) { ?>
		<div class="footer-text">
			<?php echo $text_footer; ?>
		</div>
	<?php } ?>
	<?php if ($text_version) { ?>
		<div class="footer-version">
			<?php echo $text_version; ?>
		</div>
	<?php } ?>
	</footer>
<?php } ?></div>

<script>
var text_yes = '<?php echo $text_yes; ?>';
var text_no = '<?php echo $text_no; ?>';
var text_selected = '<?php echo $text_selected; ?>';
</script>

</body></html>