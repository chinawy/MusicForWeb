<?php
use yii\helpers\Html;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>YiizzzzzzzzzziiY</title>
    <!--引入插件-->
    <?= Html::jsFile('@web/public/jquery/jquery.min.js') ?>
    <?= Html::cssFile('@web/public/bootstrap/css/bootstrap.min.css') ?>
    <?= Html::jsFile('@web/public/bootstrap/js/bootstrap.min.js') ?>
    <?= Html::jsFile('@web/public/layer/layer.js') ?>
    <?= Html::jsFile('@web/public/layer/extend/layer.ext.js') ?>
</head>
<body scrollbars="no" scroll="no">
<?= $content ?>
</body>
</html>