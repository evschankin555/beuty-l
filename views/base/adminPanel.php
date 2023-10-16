<?php
use yii\helpers\Html;
use app\components\AdminPage;

$this->title = 'Админ панель';
$this->params['breadcrumbs'][] = $this->title;

$adminPageModule = new AdminPage(null, null, $uniqueParticipants, $totalTicketCount, $totalTicketCountPaid, $totalAmount, $totalAmountPaid);

$html = $adminPageModule->generateAdminCards();
?>
<div class="admin-panel" id="admin-page">
    <div class="table-container">
        <table class="table table-hover">
            <thead>
            <tr>
                <th scope="col" class="col-2 centered ">#</th>
                <th scope="col" class="col-4">Номер карты</th>
                <th scope="col" class="col-4">Email</th>
                <th scope="col" class="col-2 centered ">Билетов</th>
            </tr>
            </thead>
            <tbody id="table-one">
            <?=$adminPageModule->generatePaymentTable($payments)?>
            </tbody>
        </table>
    </div>
    <div id="statistics-one">
        <?=$adminPageModule->generateAdminCards()?>
    </div>
    <button type="button" class="admin-btn">Скачать всё</button>
    <button type="button" class="admin-btn">Скачать от 10 билетов</button>
</div>


<script>
    // Получаем ссылку на элементы формы
    const form = document.getElementById('form-one'); // Замените 'form-id' на ID вашей формы

    const parentElement = document.getElementById('admin-page');
    parentElement.addEventListener('change', function (event) {
        if (event.target.id === 'startDate' || event.target.id === 'endDate') {
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const startDate = startDateInput.value;
            let endDate = endDateInput.value;

            // Проверяем, если конечная дата меньше начальной, делаем их равными
            if (new Date(endDate) < new Date(startDate)) {
                endDate = startDate;
                endDateInput.value = startDate; // Обновляем значение в поле ввода
            }
            sendRequest();
        }
    });

    function sendRequest() {
        // Получаем значения выбранных дат
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        // Создаем объект XMLHttpRequest
        const xhr = new XMLHttpRequest();

        // Формируем URL для отправки запроса
        const url = `/get-payments?startDate=${startDate}&endDate=${endDate}`;

        // Настраиваем запрос
        xhr.open('GET', url, true);

        // Определяем обработчик для события загрузки
        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const tableOne = document.getElementById('table-one');
                    const statisticsOne = document.getElementById('statistics-one');
                    tableOne.innerHTML = response.html;
                    statisticsOne.innerHTML = response.cards;
                } else {
                    console.error('Ошибка при выполнении запроса');
                }
            } else {
                console.error('Ошибка при выполнении запроса:', xhr.status);
            }
        };

        // Отправляем запрос
        xhr.send();
    }
    document.addEventListener('DOMContentLoaded', function () {
        const downloadButton = document.querySelector('.admin-btn');

        downloadButton.addEventListener('click', function () {
            // Получаем значения выбранных дат
            const startDateInput = document.getElementById('startDate');
            const endDateInput = document.getElementById('endDate');
            const startDate = startDateInput.value;
            const endDate = endDateInput.value;

            // Формируем имя файла с датами
            const fileName = `Все с ${startDate} по ${endDate}.txt`;

            // Формируем URL для скачивания
            const url = `/download-all?startDate=${startDate}&endDate=${endDate}`;

            // Создаем скрытую ссылку для скачивания
            const downloadLink = document.createElement('a');
            downloadLink.href = url;
            downloadLink.download = fileName;

            // Автоматически кликаем по ссылке для скачивания
            downloadLink.click();
        });
    });


</script>
