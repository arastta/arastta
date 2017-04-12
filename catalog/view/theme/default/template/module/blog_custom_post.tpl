<h3 class="module-title"><span><?php echo $heading_title; ?></span></h3>
<?php foreach ($posts as $post) { ?>
<div class="blog-items-module">
    <h4><a href="<?php echo $post['href']; ?>"><?php echo $post['name']; ?></a></h4>
    <p><?php if ($post['thumb']) { ?><img src="<?php echo $post['thumb']; ?>" class="pull-left" style="margin:0 10px 10px 0" alt="<?php echo $post['name']; ?>" /><?php } ?> <?php echo $post['description']; ?></p>
    <div class="pull-right">
        <a href="<?php echo $post['href']; ?>" class="btn btn-sm btn-primary"><?php echo $text_more; ?></a>
    </div>
    <div class="clearfix"></div>
</div>
<?php } ?>
