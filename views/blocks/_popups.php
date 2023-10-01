<div class="overlay js-overlay"></div>

<div class="popup js-popup" id="buy">
    <div class="popup__title">После оплаты Вы автоматически становитесь участником лотереи.</div>
    <form action="#" class="myform">
        <label for="email" class="myform__label">Укажите ваш Email</label>
        <input type="email" id="email" class="myform__input" required>
        <input type="submit" value="Оплатить" class="myform__submit" id="emailPopupBtn">
    </form>
</div>

<div class="popup js-popup" id="success">
    <img src="images/dist/logo.png" class="popup__logo" alt="">
    <div class="popup__title">Поздравляю, вы стали участником лотереи.<br><br>Желаем удачи!</div>
    <a href="" class="popup-btn">купить еще билет</a>
</div>

<div class="popup js-popup" id="mistake">
    <img src="images/dist/logo.png" class="popup__logo" alt="">
    <div class="popup__title">Что-то пошло не так!</div>
    <a href="" class="popup-btn">Попробовать еще раз!</a>
    <a href="" class="popup-btn">Связь с поддержкой!</a>
</div>
