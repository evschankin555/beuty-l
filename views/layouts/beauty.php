<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => 'images/dist/favicon-32x32.ico']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/png', 'href' => 'images/dist/favicon-16x16.ico']);

$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrapper">
        <?= $content ?>
        <?= $this->render('//blocks/_footer') ?>
    </div>
    <?= $this->render('//blocks/_popups') ?>
    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>