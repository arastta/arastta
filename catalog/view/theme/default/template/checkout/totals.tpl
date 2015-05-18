<table class="table table-responsive borderless">
    <?php foreach ($totals as $total) { ?>
    <tr>
        <td class="text-left"><?php echo $total['title']; ?>:</td>
        <td class="text-right"><?php echo $total['text']; ?></td>
    </tr>
    <?php } ?>
</table>