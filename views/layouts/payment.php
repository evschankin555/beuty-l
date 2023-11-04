<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;

AppAsset::register($this);
$this->beginPage();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-param" content="_csrf">
    <meta name="csrf-token" content="F90-6COGIoGy3bFxOCbhVk5uUDdME63Hgm-EKoTiW7hihwq7Fvx269GXiBdXEqsdHgwVBn94yK3mPdJb9KUIjQ==">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link type="image/png" href="images/dist/favicon-32x32.ico" rel="icon">
    <link type="image/png" href="images/dist/favicon-16x16.ico" rel="icon">
    <link href="/css/main.min.css?v=1696022303" rel="stylesheet">
    <link href="/css/site.css?v=1697595128" rel="stylesheet"></head>
<style>
    body{
        background: url(../images/dist/popup-bg.png) no-repeat right bottom, linear-gradient(130deg, #eb95bc 66.9%, #fff 105.99%) !important;
    }
    .buy-block{
        left: 50%;
        position: fixed;
        right: 0;
        top: 50%;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        -ms-transform: translate(-50%, -50%);
        -o-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        text-align: center;
        width: calc(100% - 20px);
        z-index: 90;
        display: block;
    }
</style>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrapper">
        <?= $content ?>
    </div>
    <?php $this->endBody() ?>
<script src="/js/jquery.min.js"></script>
</body>
</html>
<?php $this->endPage() ?>