<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Button;
?>
<div class="buy-block" id="buy-new">
    <img src="/images/dist/logo.png" class="popup__logo" alt="">
    <div class="popup__title">Поздравляю, вы стали участником лотереи.<br><br>Желаем удачи!</div>
    <a href="/" class="popup-btn">купить еще билет</a>
</div>
<style>
    .popup-btn{
        max-height: 75px;
        border-radius: 10px;
    }
</style>