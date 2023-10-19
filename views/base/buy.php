<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Button;
?>
<div class="buy-block" id="buy">
    <div class="popup__title">Послы оплаты вы получите доступ</div>
    <form action="#" class="myform">
        <label for="email" class="myform__label">Укажите ваш Email</label>
        <input type="email" id="email" class="myform__input" required="">
        <input type="submit" value="Оплатить" class="myform__submit" id="emailPopupBtn">
    </form>
</div>