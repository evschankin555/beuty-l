<?php

/* @var $this \yii\web\View */
/* @var $content string */
/* @var $model \app\models\SearchForm */

use yii\helpers\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\widgets\Breadcrumbs;
use yii\widgets\ActiveForm;
use app\assets\AppAssetAdmin;

AppAssetAdmin::register($this);

$modelUser = new \app\models\User();

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
            'class' => 'navbar navbar-expand-lg fixed-top navbar-dark bg-primary  ',
        ],
    ]);
?>
    <div class="collapse navbar-collapse center">
        </div>
            <?php
            $currentRoute = Yii::$app->controller->getRoute();

            if ($currentRoute != 'pages/login') {
                echo Nav::widget([
                    'options' => ['class' => 'navbar-nav ml-auto'],
                    'items' => [
                        Yii::$app->user->isGuest ? (
                        ['label' => 'Войти', 'url' => ['/login']]
                        ) : (
                            '<li class="nav-item">'
                            . UserMenuButtonWidget::widget()
                            . '</li>'
                        )
                    ],
                ]);
            }

    NavBar::end();
    ?>
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
