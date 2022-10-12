<?php

use yii\web\View;
use yii\helpers\Html;
use common\assets\AppAsset;
use rmrevin\yii\fontawesome\AssetBundle;
use hail812\adminlte3\assets\AdminLteAsset;

/* @var $this View */
/* @var $content string */

AdminLteAsset::register($this);
AppAsset::register($this);
AssetBundle::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="icon" href="<?= Yii::getAlias('@assets') ?>/img/favicon/32x32.png" sizes="32x32"/>
    <link rel="icon" href="<?= Yii::getAlias('@assets') ?>/img/favicon/192x192.png" sizes="192x192"/>
    <?php $this->head() ?>
</head>

<body class="sidebar-collapse">
<?php $this->beginBody() ?>

<div class="wrapper">

    <!-- Content Wrapper. Contains page content -->
    <?= $content ?>
    <!-- /.content-wrapper -->

</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
