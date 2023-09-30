<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Button;
?>
<header class="header">
    <a href="/" class="header__logo"><img src="images/dist/logo.png" alt=""></a>
</header>
<div class="box1">
    Для тех, кто ещё не знаком с миром лотерей: Вы покупаете билет, а затем мы устраиваем  стрим с розыгрышем. И если удача улыбается, именно ваш билет оказывается в центре внимания при вращении барабана, то наши призы отправляются непосредственно вам – и, возможно, даже несколько сразу! Желаю удачи
</div>

<div class="tickets">
    <div class="tickets__title">выберите количество билетов</div>
    <div class="tickets__grid">
        <div class="tickets__item">
            <div class="tickets__count">
                <img src="images/dist/ticket-1.svg" alt="">
                <!-- <span>1</span> -->
            </div>
            <div class="tickets__price">90 руб</div>
        </div>
        <div class="tickets__item">
            <div class="tickets__count">
                <img src="images/dist/ticket-2.svg" alt="">
                <!-- <span>5</span> -->
            </div>
            <div class="tickets__price">450 руб</div>
        </div>
        <div class="tickets__item">
            <div class="tickets__count">
                <img src="images/dist/ticket-3.svg" alt="">
                <!-- <span>10</span> -->
            </div>
            <div class="tickets__price">900 руб</div>
        </div>
    </div>
    <!-- /.tickets__grid -->

    <div class="agree">
        <input type="checkbox" id="agree1">
        <label for="agree1" class="agree__label">я согласен с правилами</label>
    </div>

    <button type="button" data-link="buy" class="btn js-btn">перейти к оплате</button>
</div>