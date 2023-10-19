<?php

/* @var $this yii\web\View */

use yii\helpers\Html;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\Nav;
use yii\widgets\ActiveForm;
use yii\bootstrap5\Button;
?>
<header class="header">
    <a href="/" class="header__logo" style="padding: 30px;"><img src="images/dist/title2.png" alt=""></a>
</header>
<div class="box1">
    Дословно «чек-лист» переводится как «проверочный/контрольный список». Стандартно это перечень пунктов, напротив которых ставятся галочки — когда тот или иной будет выполнен. Я подобрала вам чек лист одежды на зиму, вы получите список красивой одежды и их картинки, вы можете приобрести ниже.
</div>

<div class="tickets">
    <form id="ticketsForm">
    <div class="tickets__wrap">
        <div class="tickets__title">выберите желаемый чек-лист</div>
            <div class="tickets__grid">
                <div class="tickets__item">
                    <div class="tickets__count" data-ticket="1" data-ticket-id="1">
                        <img src="images/dist/brash-1.png" alt="">
                         <span>№1</span>
                    </div>
                    <div class="tickets__price">90 руб</div>
                </div>
                <div class="tickets__item active">
                    <div class="tickets__count" data-ticket="5" data-ticket-id="2">
                        <img src="images/dist/brash-2.png" alt="">

                        <span>№2</span>
                    </div>
                    <div class="tickets__price">450 руб</div>
                </div>
                <div class="tickets__item">
                    <div class="tickets__count" data-ticket="10" data-ticket-id="3">
                        <img src="images/dist/brash-3.png" alt="">

                        <span>№3</span>
                    </div>
                    <div class="tickets__price">900 руб</div>
                </div>
            </div>
        <!-- /.tickets__grid -->
    </div>
        <div class="box1 box-2">
            <p><span>чек-лист № 1</span> содержит в себе гардеробную капсулу из набора одежды, обуви и аксессуаров!</p>

            <p><span>чек-лист № 2</span> содержит в себе 2 гардеробные капсулы из набора одежды, обуви и аксессуаров!</p>

            <p><span>чек-лист № 3</span> содержит в себе 3 гардеробные капсулы из набора одежды, обуви и аксессуаров!</p>
        </div>
        <div class="agree">
            <input type="checkbox" id="agree1" required>
            <label for="agree1" class="agree__label">я согласен с правилами</label>
        </div>
        <button type="button" id="btn-pay" data-link="buy" class="btn">перейти к оплате</button>
    </form>
</div>