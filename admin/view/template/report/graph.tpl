<div id="tab_toolbar" class="panel-body" style="width: 100%; display: table; color: #555555;">
	<?php $i = 0; ?>
	<?php foreach ($graph as $key => $value) { ?>
	<dl onclick="getChart(this, '<?php echo $key; ?>');" class="col-xs-4 col-lg-2<?php echo empty($i) ? ' active' : ' passive'; ?>" style="background-color: <?php echo $value['color']; ?>">
		<dt><?php echo $value['title']; ?></dt>
		<dd class="data_value size_l"><span id="<?php echo $key; ?>_score"></span></dd>
	</dl>
	<?php $i++; ?>
	<?php } ?>
</div>
<div id="charts" class="panel-body">
	<?php $i = 0; ?>
	<?php foreach ($graph as $key => $value) { ?>
	<div id="chart-<?php echo $key; ?>" class="chart<?php echo empty($i) ? ' chart_active' : ''; ?>"></div>
	<?php $i++; ?>
	<?php } ?>
</div