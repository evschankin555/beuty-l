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
            ['status', 'in', 'range' => ['начата оплата', 'оплачено', 'отменено']],
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

        $payment->datetime = date('Y-m-d H:i:s');

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


}
