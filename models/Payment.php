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
}
