<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;

class PaymentController extends Controller
{
    public $enableCsrfValidation = false;

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Действие для обработки уведомлений о статусе платежа.
     */
    public function actionNotification()
    {
        // Логика обработки уведомлений о статусе платежа
        // Например, запись уведомления в базу данных, изменение статуса заказа и т.д.
        // ...

        // Возвращение ответа на уведомление. В зависимости от API платежной системы,
        // может потребоваться возвращать определенный HTTP-ответ или JSON-ответ.
        return "Notification processed successfully";
    }

    /**
     * Действие после успешной оплаты.
     */
    public function actionSuccess()
    {
        $this->layout = 'payment';
        return $this->render('success');
    }

    /**
     * Действие в случае ошибки оплаты.
     */
    public function actionFail()
    {
        $this->layout = 'payment';
        return $this->render('fail');
    }
}
