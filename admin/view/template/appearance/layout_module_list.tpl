<?php foreach($extensions as $modules): ?>
<div class="accordion-heading">
    <i class="fa fa-cubes"></i><span class="module-name"><?php echo $modules['name']; ?></span>
    <div class="btn-group">
        <?php if($modules['instance']): ?>
        <a href="<?php echo $modules['link']; ?>" data-type="iframe" data-toggle="tooltip" title="<?php echo $button_new; ?>" class="btn btn-success btn-edit"><i class="fa fa-plus-circle"></i></a>
        <?php endif; ?>
    </div>
</div>
<?php if($modules['instance']): ?>
<?php if(!empty($modules['module'])): ?>
<div class="accordion-content accordion-content-drag">
    <?php foreach($modules['module'] as $module): ?>
    <div class="module-block ui-draggable" data-code="<?php echo $module['code']; ?>" id="<?php echo str_replace('.', '_', $module['code']); ?>">
        <i class="fa fa-arrows-alt"></i><span class="module-name"><?php echo $module['name']; ?></span>
        <a href="<?php echo $modules['link']; ?>&module_id=<?php echo $module['module_id']; ?>" data-type="iframe" data-toggle="tooltip" style="top:6px!important;font-size:1.2em !important; right: 35px;" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-xs btn-edit btn-group"><i class="fa fa-pencil"></i></a>
        <a onclick="removeModule('<?php echo $module['module_id']; ?>', '<?php echo str_replace('.', '_', $module['code']); ?>');" data-toggle="tooltip" name="reset" style="top:6px!important;font-size:1.2em !important;" title="<?php echo $button_remove; ?>" id="reset<?php echo $module['module_id']; ?>" class="btn btn-danger btn-xs reset"><i class="fa fa-trash-o"></i></a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php else: ?>
<div class="accordion-content accordion-content-drag">
    <div class="module-block ui-draggable" data-code="<?php echo $modules['code']; ?>">
        <i class="fa fa-arrows-alt"></i><span class="module-name"><?php echo $modules['name']; ?></span>
        <a href="<?php echo $modules['link']; ?>" data-type="iframe" data-toggle="tooltip" style="top:6px!important;font-size:1.2em !important;" title="<?php echo $button_edit; ?>" class="btn btn-primary btn-xs btn-edit btn-group"><i class="fa fa-pencil"></i></a>
    </div>
</div>
<?php endif; ?>
<?php endforeach; ?>
