<?php

namespace app\controllers;

use app\components\Common;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Controller;
use Yii;
use app\models\User;
use app\models\Payment;

class BaseController extends Controller
{

    public $enableCsrfValidation = false;
    public function beforeAction($action)
{
    $this->enableCsrfValidation = false;

    return parent :: beforeAction($action);
}
    /**
     * @return string
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function actionHome()
    {
        $this->layout = 'beauty';
        return $this->render('home');
    }


    public function actionAuth()
    {
        Yii::$app->response->headers->set('Content-Type', 'application/json; charset=utf-8');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $userModel = new User();
        $postData = Yii::$app->request->post('User');
        $email = isset($postData['email']) ? $postData['email'] : null;
        $password = isset($postData['password']) ? $postData['password'] : null;

        // Выход из метода, если какие-то из данных не переданы
        if ($email === null || $password === null) {
            Yii::$app->response->data = ['success' => false, 'error' => 'Обязательные поля пустые.'];
            return;
        }

        $userAuth = $userModel->login($email, $password);
        if ($userAuth) {
            return ['success' => true];
        } else {
            return [
                'success' => false,
                'error' => 'Не верный логин или пароль.'
            ];
        }
    }


    public function actionLogout()
    {
        Yii::$app->response->headers->set('Content-Type', 'application/json; charset=utf-8');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $user = new User();
        $userLogout = $user->logout();
        if ($userLogout) {
            return ['success' => true];
        } else {
            return ['success' => false, 'error' => 'Error logout'];
        }
    }

    public function actionAdminPanel()
    {
        $this->layout = 'admin';

        $payments = Payment::find()->all();
        $uniqueParticipants = Payment::countUniqueParticipants();
        $totalTicketCount = Payment::sumTicketCounts();
        $totalTicketCountPaid = Payment::sumTotalTicketCountsPaid();
        $totalAmount = Payment::sumTotalAmount();
        $totalAmountPaid = Payment::sumTotalAmountPaid();

        return $this->render('adminPanel', [
            'payments' => $payments,
            'uniqueParticipants' => $uniqueParticipants,
            'totalTicketCount' => $totalTicketCount,
            'totalTicketCountPaid' => $totalTicketCountPaid,
            'totalAmount' => $totalAmount,
            'totalAmountPaid' => $totalAmountPaid,
        ]);
    }
    public function actionCreatePayment()
    {
        Yii::$app->response->headers->set('Content-Type', 'application/json; charset=utf-8');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $postEmail = Yii::$app->request->post('email');
        $postTicketCount = Yii::$app->request->post('ticketCount');

        // Выход из метода, если какие-то из данных не переданы
        if ($postEmail === null || $postTicketCount === null) {
            Yii::$app->response->data = ['success' => false, 'error' => 'Обязательные поля пустые.'];
            return;
        }

        $payment = Payment::createPayment($postEmail, $postTicketCount);

        if ($payment) {
            Yii::$app->response->data = ['success' => true, 'paymentId' => $payment->id];
        } else {
            Yii::$app->response->data = ['success' => false, 'error' => 'Ошибка при создании заказа.'];
        }
    }

}