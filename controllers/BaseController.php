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
    public function actionOld()
    {
        $this->layout = 'beauty';
        return $this->render('old');
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
    public function actionDownloadAll()
    {
        Yii::$app->response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        Yii::$app->response->format = \yii\web\Response::FORMAT_RAW;

        $startDate = Yii::$app->request->post('startDate');
        $endDate = Yii::$app->request->post('endDate');
        $buttonId = Yii::$app->request->post('buttonId');
        $startDate = new \DateTime($startDate, new \DateTimeZone('Europe/Moscow'));
        $endDate = new \DateTime($endDate, new \DateTimeZone('Europe/Moscow'));
        $startDate->setTime(0, 0, 0);
        $endDate->setTime(23, 59, 59);

        $payments = Payment::find()
            ->where(['between', 'datetime', $startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s')])
            ->all();

        $adminPageModule = new AdminPage($startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'), null, null, null, null, null);
        $textData = $adminPageModule->generateCardNumbersText($payments, $buttonId);

        Yii::$app->response->content = $textData;
    }
    public function actionBuy1()
    {
        $amount = 90;
        $this->layout = 'buy';
        return $this->render('buy', ['amount' => $amount]);
    }

    public function actionBuy2()
    {
        $amount = 450;
        $this->layout = 'buy';
        return $this->render('buy', ['amount' => $amount]);
    }

    public function actionBuy3()
    {
        $amount = 900;
        $this->layout = 'buy';
        return $this->render('buy', ['amount' => $amount]);
    }

    public function actionCreatePay()
    {
        $tinkoffPay = Yii::$app->TinkoffPayment;
        Yii::$app->response->headers->set('Content-Type', 'application/json; charset=utf-8');
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $postEmail = Yii::$app->request->post('email');
        $amount = $postTicketCount = Yii::$app->request->post('amount');

        if ($postEmail === null || $postTicketCount === null) {
            Yii::$app->response->data = ['success' => false, 'error' => 'Обязательные поля пустые.'];
            return;
        }

        $checklists = [
            90 => 'чек-лист № 1 содержит в себе гардеробную капсулу из набора одежды, обуви и аксессуаров!',
            450 => 'чек-лист № 2 содержит в себе 2 гардеробные капсулы из набора одежды, обуви и аксессуаров!',
            900 => 'чек-лист № 3 содержит в себе 3 гардеробные капсулы из набора одежды, обуви и аксессуаров!',
            //10 => 'чек-лист Тест',
        ];

        $checklistsName = [
            90 => 'чек-лист № 1',
            450 => 'чек-лист № 2',
            900 => 'чек-лист № 3',
            //10 => 'чек-лист Тест',
        ];
        $items = [
            [
                'Name' => $checklistsName[$amount],
                'Price' => $amount * 100,
                'Quantity' => 1,
                'Amount' => $amount * 100,
                'PaymentMethod' => 'full_payment',
                'PaymentObject' => 'payment',
                'Tax' => 'vat0',
            ],
        ];
        if (array_key_exists($amount, $checklists)) {
            $checklistDescription = $checklists[$amount];
        } else {
            $checklistDescription = 'Оплата на сайте outfitchecklist.ru';
        }

        $payment = Payment::createPayment($postEmail, $postTicketCount);

        if ($payment) {
            Yii::$app->response->data = ['success' => true, 'paymentId' => $payment->id];

            $paymentResponse = $tinkoffPay->createPayment($payment, $amount, $checklistDescription, $postEmail, $items);

            if ($paymentResponse) {
                return $this->redirect($paymentResponse);
            } else {
                Yii::$app->session->setFlash('error', 'Не удалось инициировать платеж.');
                return $this->redirect(['/']);
            }
        } else {
            Yii::$app->response->data = ['success' => false, 'error' => 'Ошибка при создании заказа.'];
        }
    }

    public function actionPay()
    {
        $this->layout = 'buy';
        return $this->render('pay');
    }
}