<?php

namespace app\controllers;

use app\components\Common;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Controller;
use Yii;
use app\models\User;
use app\models\Payment;
use app\components\AdminPage;

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

    public function actionGetPayments()
    {
        Yii::$app->response->headers->set('Content-Type', 'application/json; charset=utf-8');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $startDate =  $startDate0 = Yii::$app->request->get('startDate');
        $endDate = $endDate0 = Yii::$app->request->get('endDate');

        // Выход из метода, если какие-то из данных не переданы
        if ($startDate === null || $endDate === null) {
            Yii::$app->response->data = ['success' => false, 'error' => 'Обязательные поля пустые.'];
            return;
        }
        $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
        $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
        $startDate->setTime(0, 0, 0);
        $endDate->setTime(23, 59, 59);

        $startDate = $startDate->format('Y-m-d H:i:s');
        $endDate = $endDate->format('Y-m-d H:i:s');
        // Запрос данных с учетом дат
        $payments = Payment::find()
            ->where(['between', 'datetime', $startDate, $endDate])
            ->all();

        // Вызов методов для расчета остальных данных
        $uniqueParticipants = Payment::countUniqueParticipants($startDate, $endDate);
        $totalTicketCount = Payment::sumTicketCounts($startDate, $endDate);
        $totalTicketCountPaid = Payment::sumTotalTicketCountsPaid($startDate, $endDate);
        $totalAmount = Payment::sumTotalAmount($startDate, $endDate);
        $totalAmountPaid = Payment::sumTotalAmountPaid($startDate, $endDate);


        $adminPageModule = new AdminPage($startDate0, $endDate0, $uniqueParticipants, $totalTicketCount, $totalTicketCountPaid, $totalAmount, $totalAmountPaid);
        $cards = $adminPageModule->generateAdminCards();
        $html = $adminPageModule->generatePaymentTable($payments);
        return Yii::$app->response->data = ['success' => true, 'html' => $html, 'cards' => $cards];
        if (empty($payments)) {
            $cards = '';
            $html = '';
            return Yii::$app->response->data = ['success' => true, 'html' => $html, 'cards' => $cards];
        }
        return false;
    }


}