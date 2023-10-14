<?php


namespace app\components;

use Yii;

class AdminPage
{
    private $startDate;
    private $endDate;
    private $uniqueParticipants;
    private $totalTicketCount;
    private $totalTicketCountPaid;
    private $totalAmount;
    private $totalAmountPaid;
    /**
     * Метод для генерации карточек с информацией о фильтре, участниках, билетах и сумме
     * @param string $startDate Начальная дата
     * @param string $endDate Конечная дата
     * @param int $uniqueParticipants Количество уникальных участников
     * @param int $totalTicketCount Общее количество купленных билетов
     * @param int $totalTicketCountPaid Общее количество оплаченных билетов
     * @param float $totalAmount Общая сумма
     * @param float $totalAmountPaid Общая сумма для оплаченных заказов
     * @return string Сгенерированный HTML-код
     */
    public function __construct($startDate = null, $endDate = null, $uniqueParticipants, $totalTicketCount, $totalTicketCountPaid, $totalAmount, $totalAmountPaid)
    {
        if(empty($startDate)){
            $startDate = date('Y-m-d');
        }
        if(empty($endDate)){
            $endDate = date('Y-m-d');
        }
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->uniqueParticipants = $uniqueParticipants;
        $this->totalTicketCount = $totalTicketCount;
        $this->totalTicketCountPaid = $totalTicketCountPaid;
        $this->totalAmount = $totalAmount;
        $this->totalAmountPaid = $totalAmountPaid;
    }

    /**
     * Метод для генерации строк таблицы
     * @param int $rowCount Количество строк для генерации
     * @return string Сгенерированные строки таблицы
     */
    public function generateTableRows($rowCount = 100)
    {
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

    /**
     * Метод для генерации таблицы платежей
     * @param array $payments Массив платежей
     * @return string Сгенерированная таблица платежей
     */
    public function generatePaymentTable($payments)
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

            // Преобразуем дату в объект DateTime с указанием временной зоны
            $datetime = new \DateTime($datetime, new \DateTimeZone('Europe/Moscow'));

            $table .= "
        <tr class='$rowClass'>
            <th scope='row' title='" . $datetime->format('Y-m-d H:i:s') . "'>$id</th>
            <td title='$status'>$statusEmoji $cardNumber</td>
            <td>$email</td>
            <td title='$amount ₽'>$ticketCount</td>
        ";
        }

        return $table;
    }

    public function generateAdminCards()
    {
        $html = '<div class="row mt-3 statistic-block">
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Фильтр по дате</div>
                <div class="card-body pt-2 pb-2">
                    <form id="form-one">
                        <input type="date" id="startDate" class="form-control form-control-sm" name="startDate" value="'.$this->startDate .'">
                        <input type="date" id="endDate" class="form-control form-control-sm mt-1" name="endDate" value="'.$this->endDate .'">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Количество участников</div>
                <div class="card-body">
                    <h4 class="card-title">'.$this->uniqueParticipants.'</h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Всего куплено билетов</div>
                <div class="card-body">
                    <h4 class="card-title" title="не подтвержденные - '.$this->totalTicketCount.'">
                        '.$this->totalTicketCountPaid.'
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card border-primary mb-3" style="max-width: 20rem;">
                <div class="card-header">Сумма</div>
                <div class="card-body">
                    <h4 class="card-title" title="не подтвержденные - '.number_format($this->totalAmount, 0, '.', ' ') . '₽'.'">
                        '.number_format($this->totalAmountPaid, 0, '.', ' ') . '₽'.'
                    </h4>
                </div>
            </div>
        </div>
    </div>';
        return $html;
    }
}