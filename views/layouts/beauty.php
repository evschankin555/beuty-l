<?php


/** @var yii\web\View $this */

/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title></title>

    <meta name="keywords" content="" />
    <link rel="icon" type="image/png" href="images/dist/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="images/dist/favicon-16x16.png" sizes="16x16">
    <link rel="stylesheet" href="css/main.min.css">
</head>

<body>

<div class="wrapper">
    <?= $content ?>
    <footer class="footer">
        <div class="footer__author">ИП Кирилова К.И.</div>

        <div class="footer__politics">
ПОЛЬЗОВАТЕЛЬСКОЕ СОГЛАШЕНИЕ ПОЛИТИКА ОБРАБОТКИ ПЕРСОНАЛЬНЫХ ДАННЫХ СОГЛАСИЕ НА ОБРАБОТКУ ПЕРсоНАльных ДАННЫх ОТказ от ОТвЕтСтвЕнности
</div>

    </footer>
</div>
<!-- /.wrapper -->

<!--<div class="overlay js-overlay"></div>-->
<div class="popup js-popup" id="buy">
    <div class="popup__title">Посла оплаты Вы автоматически становитесь участником лотереи.</div>
    <form action="#" class="myform">
        <label for="email" class="myform__label">Укажите ваш Email</label>
        <input type="email" id="email" class="myform__input" required>
        <input type="submit" value="Оплатить" class="myform__submit">
    </form>
</div>
<!-- /.popup -->

<div class="popup js-popup" id="success">
   <img src="images/dist/logo.png" class="popup__logo" alt="">
    <div class="popup__title">Поздравляю, вы стали участником лотереи.<br><br>
Желаем удачи!</div>
    <a href="" class="popup-btn">купить еще билет</a>
</div>
<!-- /.popup -->

<div class="popup js-popup" id="mistake">
    <img src="images/dist/logo.png" class="popup__logo" alt="">
    <div class="popup__title">Что-то пошло не так!</div>
    <a href="" class="popup-btn">Попробовать еще раз!</a>
    <a href="" class="popup-btn">Связь с поддержкой!</a>
</div>
<!-- /.popup -->
<script src="js/jquery.min.js"></script>
<script src="js/app.js"></script>
</body>
</html>

