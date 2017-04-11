<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <?php if ($description) { ?>
            <p class="lead"><?php echo $description; ?></p>
            <?php } ?>
            <hr>
            <?php if ($featured_posts) { ?>
            <div class="blog-slide owl-theme">
                <?php foreach ($featured_posts as $featured_post) { ?>
                <div class="item">
                  <div class="row">
                    <?php if ($featured_post['thumb']) { ?>
                    <?php $class_featured_post = 'col-sm-8'; ?>
                    <?php } else { ?>
                    <?php $class_featured_post = 'col-sm-12'; ?>
                    <?php } ?>
                    <?php if ($featured_post['thumb']) { ?>
                    <div class="col-sm-4">
                        <a href="<?php echo $featured_post['href']; ?>" title="<?php echo $featured_post['name']; ?>">
                            <img src="<?php echo $featured_post['thumb']; ?>" class="img-responsive" title="<?php echo $featured_post['name']; ?>" alt="<?php echo $featured_post['name']; ?>" />
                        </a>
                    </div>
                    <?php } ?>
                    <div class="<?php echo $class_featured_post; ?>" style="padding-right:10px;">
                      <h3><a href="<?php echo $featured_post['href']; ?>" title="<?php echo $featured_post['name']; ?>"><?php echo $featured_post['name']; ?></a></h3>
                      <p><?php echo $featured_post['description']; ?></p>
                    </div>
                  </div>
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            <?php if ($posts) { ?>
            <?php foreach ($posts as $post) { ?>
            <div class="ar-blog-cat-item">
              <h3><a href="<?php echo $post['href']; ?>"><?php echo $post['name']; ?></a></h3>
              <div class="meta">
                <?php if ($author) { ?>
                <a class="blog-author" href="<?php echo $post['author_href']; ?>"><i class="fa fa-user"></i> <?php echo $post['author']; ?></a>
                <?php } ?>
                <?php if ($category) { ?>
                <a class="blog-category" href="<?php echo $post['category_href']; ?>"><i class="fa fa-bookmark"></i> <?php echo $post['category']; ?></a>
                <?php } ?>
                <?php if ($date_added) { ?>
                <span class="blog-date"><i class="fa fa-calendar"></i> <?php echo $post['date_added']; ?></span>
                <?php } ?>
                <?php if ($viewed) { ?>
                <span class="blog-view"><i class="fa fa-eye"></i> <?php echo $post['viewed']; ?></span>
                <?php } ?>
                <?php if ($viewed) { ?>
                <span class="blog-comment"><i class="fa fa-comments"></i> <?php echo $post['comment_total']; ?></span>
                <?php } ?>
              </div>
              <div class="row">
                <?php if ($post['thumb']) { ?>
                <?php $class_post = 'col-sm-8'; ?>
                <?php } else { ?>
                <?php $class_post = 'col-sm-12'; ?>
                <?php } ?>
                <?php if ($post['thumb']) { ?>
                <div class="col-sm-4">
                    <a href="<?php echo $post['href']; ?>" title="<?php echo $post['name']; ?>">
                        <img src="<?php echo $post['thumb']; ?>" width="300" class="img-responsive" alt="<?php echo $post['name']; ?>" />
                    </a>
                </div>
                <?php } ?>
                <div class="<?php echo $class_post; ?>">
                  <div class="intro">
                    <p><?php echo $post['description']; ?></p>
                    <div class="blog-readmore pull-right">
                      <a href="<?php echo $post['href']; ?>" class="btn btn-lg btn-primary"><?php echo $text_more; ?></a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php } ?>
            <?php if ($pagination) { ?>
            <div class="row">
                <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
                <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
            <?php } ?>
            <?php } ?>
            <?php if (!$featured_posts && !$posts) { ?>
            <p><?php echo $text_empty; ?></p>
            <div class="buttons">
                <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
            </div>
            <?php } ?>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$('.blog-slide').owlCarousel({
    loop:true,
    items: 1,
    nav:true,
    autoPlay:3000
})
//--></script>
<?php echo $footer; ?>
