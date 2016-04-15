<?php if ($manufacturers) { ?>
<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
<div class="">
    <select class="manufacturer" onchange="location=this.options[this.selectedIndex].value;">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($manufacturers as $manufacturer) { ?>
        <option value="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></option>
        <?php } ?>
    </select>
</div>
<?php } ?>
