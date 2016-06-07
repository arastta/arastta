<?php echo '<?xml version="1.0" encoding="utf-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">
    <?php foreach ($results as $result) { ?>
    <url>
        <loc><?php echo $result['url']; ?></loc>
        <?php if (isset($result['date'])) { ?>
        <lastmod><?php echo date('Y-m-d', strtotime($result['date'])); ?></lastmod>
        <?php } else { ?>
        <lastmod><?php echo date('Y-m-d'); ?></lastmod>
        <?php } ?>
        <changefreq>weekly</changefreq>
        <?php if (isset($result['prior'])) { ?>
        <priority><?php echo $result['prior']; ?></priority>
        <?php } else { ?>
        <priority>0.5</priority>
        <?php } ?>
        <?php if (isset($result['img'])) { ?>
        <image:image>
            <image:loc><?php echo $result['img']; ?></image:loc>
            <image:caption><?php echo $result['name']; ?></image:caption>
            <image:title><?php echo $result['name']; ?></image:title>
        </image:image>
        <?php } ?>
    </url>
    <?php } ?>
</urlset>
