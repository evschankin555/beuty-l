<?php
namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\Payment;

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
        // Получение входящих данных (POST или JSON)
        $postData = file_get_contents('php://input');
        $data = json_decode($postData, true);

        // Запись данных в лог-файл
        $logFile = '/var/www/beautylottery/runtime/logs/payment_notification.log';
        file_put_contents($logFile, "[" . date("Y-m-d H:i:s") . "] Incoming Data: " . $postData . PHP_EOL, FILE_APPEND);

        // Обновление данных платежа на основе уведомления
        Payment::updateFromNotification($data);

        // Возвращение ответа на уведомление.
        return "Notification processed successfully";
    }



    /**
     * Действие после успешной оплаты.
     */
    public function actionSuccess()
    {
        $this->layout = 'payment';

        $cookies = Yii::$app->request->cookies;
        if (isset($cookies['PaymentId'])) {
            $paymentId = $cookies['PaymentId']->value;
            $tinkoffPay = Yii::$app->TinkoffPayment;
            $paymentStatus = $tinkoffPay->checkPaymentStatus($paymentId);
            if ($paymentStatus && isset($paymentStatus['Status'])) {
                $payment = Payment::findOne(['payment_id' => $paymentId]);
                if ($payment) {
                    switch ($paymentStatus['Status']) {
                        case 'NEW':
                            $payment->status = 'начата оплата';
                            break;
                        case 'CANCELED':
                        case 'PREAUTHORIZING':
                        case 'FORMSHOWED':
                            $payment->status = 'отменено';
                            break;
                        default:
                            $payment->status = $paymentStatus['Status'];
                            break;
                    }
                    $payment->save();
                }
            }
        }

        return $this->render('success');
    }

    /**
     * Действие в случае ошибки оплаты.
     */
    public function actionFail()
    {
        $this->layout = 'payment';
        $cookies = Yii::$app->request->cookies;
        if (isset($cookies['PaymentId'])) {
            $paymentId = $cookies['PaymentId']->value;

            $tinkoffPay = Yii::$app->TinkoffPayment;
            $paymentStatus = $tinkoffPay->checkPaymentStatus($paymentId);

            if ($paymentStatus && isset($paymentStatus['Status'])) {
                $payment = Payment::findOne(['payment_id' => $paymentId]);
                if ($payment) {
                    switch ($paymentStatus['Status']) {
                        case 'NEW':
                            $payment->status = 'начата оплата';
                            break;
                        case 'CANCELED':
                        case 'PREAUTHORIZING':
                        case 'FORMSHOWED':
                            $payment->status = 'отменено';
                            break;
                        default:
                            $payment->status = $paymentStatus['Status'];
                            break;
                    }
                    $payment->save();
                }
            }
        }

        return $this->render('fail');
    }
}
