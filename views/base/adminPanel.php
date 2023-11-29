<?php
use yii\helpers\Html;
use app\components\AdminPage;

$this->title = 'Админ панель';
$this->params['breadcrumbs'][] = $this->title;

$adminPageModule = new AdminPage(null, null,
    $uniqueParticipants, $totalTicketCount, $totalTicketCountPaid,
    $totalAmount, $totalAmountPaid);
    /*<button type="button" class="admin-btn" id="hide-all-payments">Скрыть все платежи</button>*/

$html = $adminPageModule->generateAdminCards();
?>
<div class="admin-panel" id="admin-page">
    <div class="table-container">
        <table class="table table-hover">
            <thead>
            <tr><tr>
                <th scope="col" class="col-2 centered">#</th>
                <th scope="col" class="col-4">Дата</th>
                <th scope="col" class="col-4">Номер карты</th>
                <th scope="col" class="col-4">Email</th>
                <th scope="col" class="col-2 centered">Билетов</th>
            </tr>

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
    <button type="button" class="admin-btn" id="download-all">Скачать всё</button>
    <button type="button" class="admin-btn" id="download-10">Скачать от 10 билетов</button>
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
    function sendDownloadRequest(buttonId) {
        const startDateInput = document.getElementById('startDate');
        const endDateInput = document.getElementById('endDate');
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        const xhr = new XMLHttpRequest();

        const url = '/download-all';
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded;charset=UTF-8');

        xhr.onload = function () {
            if (xhr.status >= 200 && xhr.status < 300) {
                const text = xhr.responseText;
                const blob = new Blob([text], { type: 'text/plain' });
                const blobUrl = URL.createObjectURL(blob);

                const downloadLink = document.createElement('a');
                downloadLink.href = blobUrl;
                if (buttonId == 'download-all'){
                    downloadLink.download = `Все с ${startDate} по ${endDate}.txt`;
                }else{
                    downloadLink.download = `От 10 с ${startDate} по ${endDate}.txt`;
                }

                downloadLink.click();
                URL.revokeObjectURL(blobUrl);
            } else {
                console.error('Error:', xhr.status);
            }
        };

        const params = `startDate=${startDate}&endDate=${endDate}&buttonId=${buttonId}`;
        xhr.send(params);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const downloadAll = document.querySelector('#download-all');
        const download10 = document.querySelector('#download-10');
        const hideAllPayments = document.querySelector('#hide-all-payments');

        downloadAll.addEventListener('click', function () {
            sendDownloadRequest('download-all');
        });

        download10.addEventListener('click', function () {
            sendDownloadRequest('download-10');
        });

        hideAllPayments.addEventListener('click', function () {
            sendHideAllPaymentsRequest();
        });
    });


    function sendHideAllPaymentsRequest() {
        fetch('/payment/hide-all', {
            method: 'POST',
        })
            .then(response => response.text())
            .then(data => {
                console.log(data);
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Произошла ошибка при скрытии платежей');
            });
    }
</script>
