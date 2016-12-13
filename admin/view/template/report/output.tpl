<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
    <head>
        <meta charset="UTF-8" />
        <title><?php echo $title; ?></title>
        <base href="<?php echo $base; ?>" />
        <link href="view/javascript/bootstrap/css/bootstrap.css" rel="stylesheet" media="all" />
        <script type="text/javascript" src="view/javascript/jquery/jquery-2.1.1.min.js"></script>
        <script type="text/javascript" src="view/javascript/bootstrap/js/bootstrap.min.js"></script>
        <link href="view/javascript/font-awesome/css/font-awesome.min.css" type="text/css" rel="stylesheet" />
        <link type="text/css" href="view/stylesheet/stylesheet.css" rel="stylesheet" media="all" />
    </head>
    <body>
        <?php if ($results) { ?>
        <div class="container">
            <div style="page-break-after: always;">
                <h1><?php echo $title; ?></h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <?php foreach (current($results) as $key => $value) { ?>
                            <?php $column = 'column_' . $key; ?>
                            <td><?php echo $$column; ?></td>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($results as $key => $result) { ?>
                        <tr>
                            <?php foreach ($result as $value) { ?>
                            <td><?php echo $value; ?></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>
    </body>
</html>
    