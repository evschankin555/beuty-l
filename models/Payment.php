<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;

class Payment extends ActiveRecord
{
    public static function tableName()
    {
        return 'payments'; // Имя таблицы в базе данных
    }

    public function rules()
    {
        return [
            [['card_number', 'email', 'ticket_count', 'product_name', 'datetime', 'amount', 'status'], 'required'],
            ['card_number', 'string', 'length' => 16],
            ['email', 'email'],
            ['ticket_count', 'integer'],
            ['datetime', 'datetime', 'format' => 'yyyy-MM-dd HH:mm:ss'],
            ['amount', 'number'],
            ['payment_id', 'integer'],
        ];
    }

    public static function createPayment($email, $ticketCount)
    {
        $payment = new Payment();

        $payment->email = $email;
        $payment->status = 'начата оплата';
        $payment->ticket_count = $ticketCount;
        $payment->card_number = '0000000000000000';

        if ($ticketCount == 1) {
            $payment->product_name = 'Один билет';
        } elseif ($ticketCount == 5) {
            $payment->product_name = 'Пять билетов';
        } elseif ($ticketCount == 10) {
            $payment->product_name = 'Десять билетов';
        } else {
            $payment->product_name = 'Здесь введите значение';
        }

        // Преобразуем текущую дату и время в объект DateTime с указанием временной зоны
        $datetime = new \DateTime('now', new \DateTimeZone('Europe/Moscow'));
        $payment->datetime = $datetime->format('Y-m-d H:i:s');

        if ($ticketCount == 1) {
            $payment->amount = 90.00;
        } elseif ($ticketCount == 5) {
            $payment->amount = 450.00;
        } elseif ($ticketCount == 10) {
            $payment->amount = 900.00;
        } else {
            $payment->amount = 0.00;
        }

        if ($payment->save()) {
            return $payment;
        } else {
            $errors = $payment->getErrors();
            foreach ($errors as $attribute => $errorMessages) {
                foreach ($errorMessages as $errorMessage) {
                    echo "$attribute: $errorMessage<br>";
                }
            }
        }
        return null;
    }

    /**
     * Метод для подсчета количества уникальных участников по их email
     * @param string|null $startDate Дата начала периода (формат: 'Y-m-d')
     * @param string|null $endDate Дата конца периода (формат: 'Y-m-d')
     * @return int
     */
    public static function countUniqueParticipants($startDate = null, $endDate = null)
    {
        $query = Payment::find()->select(['email'])->distinct()
            ->where(['<>', 'status', 'hidden'])
            ->andWhere(['=', 'status', 'CONFIRMED']);

        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
            $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
            $startDate->setTime(0, 0, 0);
            $endDate->setTime(23, 59, 59);
            $query->andWhere(['between', 'datetime', $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        }

        return $query->count();
    }

    /**
     * Метод для подсчета суммы количества билетов
     * @param string|null $startDate Дата начала периода (формат: 'Y-m-d')
     * @param string|null $endDate Дата конца периода (формат: 'Y-m-d')
     * @return int
     */
    public static function sumTicketCounts($startDate = null, $endDate = null)
    {
        $query = Payment::find()->where(['<>', 'status', 'hidden'])
            ->andWhere(['=', 'status', 'CONFIRMED']);

        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
            $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
            $startDate->setTime(0, 0, 0);
            $endDate->setTime(23, 59, 59);
            $query->andWhere(['between', 'datetime', $startDate->format('Y-m-d H:i:s'),
                $endDate->format('Y-m-d H:i:s')]);
        }
        return (int) $query->sum('ticket_count');
    }


    /**
     * Метод для подсчета общей суммы денег
     * @param string|null $startDate Дата начала периода (формат: 'Y-m-d')
     * @param string|null $endDate Дата конца периода (формат: 'Y-m-d')
     * @return float
     */
    public static function sumTotalAmount($startDate = null, $endDate = null)
    {
        $query = Payment::find()->where(['<>', 'status', 'hidden'])
            ->andWhere(['=', 'status', 'CONFIRMED']);

        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
            $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
            $startDate->setTime(0, 0, 0);
            $endDate->setTime(23, 59, 59);
            $query->andWhere(['between', 'datetime', $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        }

        return (int) $query->sum('amount');
    }


    /**
     * Метод для подсчета общей суммы денег для оплаченных заказов
     * @param string|null $startDate Дата начала периода (формат: 'Y-m-d')
     * @param string|null $endDate Дата конца периода (формат: 'Y-m-d')
     * @return float
     */
    public static function sumTotalAmountPaid($startDate = null, $endDate = null)
    {
        $query = Payment::find()->where(['<>', 'status', 'hidden'])
            ->andWhere(['=', 'status', 'CONFIRMED']);

        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
            $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
            $startDate->setTime(0, 0, 0);
            $endDate->setTime(23, 59, 59);
            $query->andWhere(['between', 'datetime', $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        }

        return (int) $query->sum('amount');
    }


    /**
     * Метод для подсчета суммы количества билетов для оплаченных заказов
     * @param string|null $startDate Дата начала периода (формат: 'Y-m-d')
     * @param string|null $endDate Дата конца периода (формат: 'Y-m-d')
     * @return int
     */
    public static function sumTotalTicketCountsPaid($startDate = null, $endDate = null)
    {
        $query = Payment::find()->where(['<>', 'status', 'hidden'])
            ->andWhere(['=', 'status', 'CONFIRMED']);

        if ($startDate && $endDate) {
            $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
            $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
            $startDate->setTime(0, 0, 0);
            $endDate->setTime(23, 59, 59);
            $query->andWhere(['between', 'datetime', $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')]);
        }

        return (int) $query->sum('ticket_count');
    }

    /**
     * Сохраняет статус платежа на основе ответа от Тинькофф.
     *
     * @param array $responseData Ответ от Тинькофф
     *
     * @return bool Результат сохранения
     */
    public function savePaymentStatus(array $responseData) {
        // Определение статуса на основе ответа
        switch ($responseData['Status']) {
            case 'NEW':
                $this->status = 'начата оплата';
                break;
            case 'CANCELED':
                $this->status = 'отменено';
                break;
            case 'COMPLETED':
                $this->status = 'оплачено';
                break;
            default:
                $this->status = 'оплачено';
                break;
        }

        return $this->save();
    }

    /**
     * Обновление данных платежа на основе уведомления.
     *
     * @param array $data Данные уведомления.
     */
    public static function updateFromNotification($data)
    {
        if (isset($data['PaymentId'])) {
            $payment = self::findOne(['payment_id' => $data['PaymentId']]);

            if ($payment) {
                $payment->status = $data['Status'];

                if (isset($data['Pan'])) {
                    $payment->card_number = $data['Pan'];
                }

                $payment->save();
            }
        }
    }

    /**
     * Метод для обновления статуса всех записей на 'hidden'.
     */
    public static function hideAllPayments()
    {
        return self::updateAll(['status' => 'hidden']);
    }
}
