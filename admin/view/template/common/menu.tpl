<?php if (empty($menu_position) || $menu_position != 'right') { ?>
    <ul id="menu">
<?php } else { ?>
    <ul id="right-menu">
<?php } ?>
        <?php foreach ($menu_items as $id=>$item) { ?>
        <?php if (!isset($item['permission']) || (isset($item['permission']) && $item['permission']) && ((isset($menu_position) && $menu_position == 'right' && $item['position'] == 'right') || ((empty($menu_position) || $menu_position != 'right') && $item['position'] != 'right'))) { ?>
        <li id="<?php echo $id; ?>">
            <a<?php if (!empty($item['href'])) { ?> href="<?php echo $item['href']; ?>"<?php } ?><?php if (isset($item['children']) && count($item['children']) > 0) { ?> class="parent"<?php } ?>><?php if (isset($item['icon'])) { ?><i class="fa <?php echo $item['icon']; ?> fa-fw"></i> <?php } ?> <span><?php echo $item['text']; ?></span></a>
            <?php if (isset($item['children']) && count($item['children']) > 0) { ?>
            <ul class="collapse">
                <?php foreach ($item['children'] as $child) { ?>
                <?php if (!isset($child['permission']) || (isset($child['permission']) && $child['permission'])) { ?>
                <li>
                    <a<?php if (!empty($child['href'])) { ?> href="<?php echo $child['href']; ?>"<?php } ?><?php if (isset($child['children']) && count($child['children']) > 0) { ?> class="parent"<?php } ?>><?php echo $child['text']; ?></a>
                    <?php if (isset($child['children']) && count($child['children']) > 0) { ?>
                    <ul>
                        <?php foreach ($child['children'] as $grandChild) { ?>
                        <?php if (!isset($grandChild['permission']) || (isset($grandChild['permission']) && $grandChild['permission'])) { ?>
                        <li><a<?php if (!empty($grandChild['href'])) { ?> href="<?php echo $grandChild['href']; ?>"<?php } ?>><?php echo $grandChild['text']; ?></a></li>
                        <?php } ?>
                        <?php } ?>
                    </ul>
                    <?php } ?>
                </li>
                <?php } ?>
                <?php } ?>
            </ul>
            <?php } ?>
        </li>
        <?php } ?>
        <?php } ?>
        <?php if (empty($menu_position) || $menu_position != 'right') { ?>
        <li id="menu-collapse"><a href="#" onclick="return false;" id="button-menu"><i class="fa fa-play-circle rotate-collapse"></i> <span><?php echo $text_collapse; ?></span></a></li>
        <?php } else { ?>
        <li id="menu-collapse-right"><a href="#" onclick="return false;" id="button-menu-right"><i class="fa fa-play-circle"></i> <span><?php echo $text_collapse; ?></span></a></li>
        <?php } ?>
    </ul>
