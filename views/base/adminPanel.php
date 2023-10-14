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
        $amount = $payment->amount;
        $datetime = $payment->datetime;
        $status = $payment->status;

        $rowClass = '';
        if ($ticketCount == 10) {
            $rowClass = 'table-primary-2';
        } elseif ($ticketCount == 5) {
            $rowClass = 'table-secondary-2';
        }

        $statusEmoji = ''; // Переменная для смайлика

        // Добавляем смайлик в зависимости от статуса
        if ($status == 'начата оплата') {
            $statusEmoji = '🟡';
        } elseif ($status == 'оплачено') {
            $statusEmoji = '✅';
        } elseif ($status == 'отменено') {
            $statusEmoji = '❌';
        }

        $table .= "
            <tr class='$rowClass'>
                <th scope='row' title='$datetime'>$id</th>
                <td title='$status'>$statusEmoji $cardNumber</td>
                <td>$email</td>
                <td title='$amount ₽'>$ticketCount</td>
            </tr>
        ";
    }

    return $table;
}

?>
<div class="admin-panel">
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
            <tbody>
            <?=generatePaymentTable($payments);?>
            </tbody>
        </table>
    </div>
    <div class="row mt-3 statistic-block">
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Фильтр по дате</div>
                <div class="card-body pt-2 pb-2">
                    <form>
                        <input type="date" id="startDate" class="form-control form-control-sm" name="startDate" value="<?php echo date('Y-m-d'); ?>">
                        <input type="date" id="endDate" class="form-control form-control-sm mt-1" name="endDate" value="<?php echo date('Y-m-d'); ?>">
                    </form>

                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Количество участников</div>
                <div class="card-body">
                    <h4 class="card-title"><?= $uniqueParticipants ?></h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Всего куплено билетов</div>
                <div class="card-body">
                    <h4 class="card-title" title="не подтвержденные - <?= $totalTicketCount ?>">
                        <?= $totalTicketCountPaid ?>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Сумма</div>
                <div class="card-body">
                    <h4 class="card-title" title="не подтвержденные - <?= number_format($totalAmount, 0, '.', ' ') . '₽' ?>">
                        <?= number_format($totalAmountPaid, 0, '.', ' ') . '₽' ?>
                    </h4>
                </div>
            </div>
        </div>
    </div>
    <button type="button" class="admin-btn">Скачать всё</button>
    <button type="button" class="admin-btn">Скачать от 10 билетов</button>
</div>
