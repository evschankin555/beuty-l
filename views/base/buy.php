<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Button;
?>
<div class="buy-block" id="buy-new">
    <div class="popup__title">После оплаты вы получите доступ</div>
    <form action="/create-pay" class="myform" method="post">
        <label for="email" class="myform__label">Укажите ваш Email</label>
        <input type="email" id="email" name="email"  class="myform__input" required="">
        <!-- Добавляем скрытое поле для суммы оплаты -->
        <input type="hidden" name="amount" value="<?= $amount ?>">
        <input type="submit" value="Оплатить" class="myform__submit">
    </form>
</div>