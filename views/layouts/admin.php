<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use app\assets\AppAssetAdmin;

AppAssetAdmin::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" itemscope itemtype="http://schema.org/WebSite">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Админ панель',
        'brandUrl' => '/admin-panel',
        'options' => [
            'class' => 'navbar navbar-expand-lg fixed-top navbar-dark bg-primary',
        ],
    ]);

    $currentRoute = Yii::$app->controller->getRoute();
    if ($currentRoute != 'pages/login') {
        $menuItems = [];

        if (Yii::$app->user->isGuest) {
            $menuItems[] = ['label' => 'Войти', 'url' => ['/login']];
        } else {
            $menuItems[] = '<li class="nav-item">'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Выйти (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'nav-link btn btn-link logout']
                )
                . Html::endForm()
                . '</li>';
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav ml-auto'],
            'items' => $menuItems,
        ]);
    }

    NavBar::end();
    ?>
    <div class="container main-container">
        <?= $content ?>
    </div>
</div>
<footer id="footer">
    <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <ul class="list-unstyled">
                <li class="float-end top"><a href="#top">На верх</a></li>
            </ul>
        </div>
    </div>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
