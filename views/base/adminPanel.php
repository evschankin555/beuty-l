<?php
use yii\helpers\Html;

$this->title = 'Админ панель';
$this->params['breadcrumbs'][] = $this->title;
function generateTableRows($rowCount = 100) {
    $rows = '';
    for ($i = 1; $i <= $rowCount; $i++) {
        $id = $i;
        $cardNumber = rand(10000, 99999); // Генерируем случайный номер карты
        $email = "example{$i}@example.com";
        $ticketCount = [1, 5, 10][array_rand([1, 5, 10])]; // Выбираем случайное значение из [1, 5, 10]

        $rowClass = ''; // Пустой класс по умолчанию
        if ($ticketCount == 10) {
            $rowClass = 'table-primary-2'; // Красный цвет для $ticketCount = 10
        } elseif ($ticketCount == 5) {
            $rowClass = 'table-secondary-2'; // Зеленый цвет для $ticketCount = 5
        }

        $rows .= "
            <tr class='$rowClass'>
                <th scope='row'>$id</th>
                <td>$cardNumber</td>
                <td>$email</td>
                <td>$ticketCount</td>
            </tr>
        ";
    }
    return $rows;
}
function generatePaymentTable($payments)
{
    $table = '';
    foreach ($payments as $payment) {
        $id = $payment->id;
        $cardNumber = $payment->card_number;
        $email = $payment->email;
        $ticketCount = $payment->ticket_count;

        $rowClass = '';
        if ($ticketCount == 10) {
            $rowClass = 'table-primary-2';
        } elseif ($ticketCount == 5) {
            $rowClass = 'table-secondary-2';
        }

        $table .= "
            <tr class='$rowClass'>
                <th scope='row'>$id</th>
                <td>$cardNumber</td>
                <td>$email</td>
                <td>$ticketCount</td>
            </tr>
        ";
    }

    return $table;
}

?>
<div class="admin-panel">
    <table class="table table-hover">
        <thead>
        <tr>
            <th scope="col" class="col-2 centered ">ID</th>
            <th scope="col" class="col-4">Номер карты</th>
            <th scope="col" class="col-4">Email</th>
            <th scope="col" class="col-2 centered ">Количество билетов</th>
        </tr>
        </thead>
        <tbody>
        <?=generatePaymentTable($payments);?>
        </tbody>
    </table>
</div>
