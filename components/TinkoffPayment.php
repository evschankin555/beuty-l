<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\Json;
use GuzzleHttp\Client;
use app\models\Payment;

class TinkoffPayment extends Component
{
    public $terminalKey;
    public $secretKey;
    public function init()
    {
        parent::init();
    }

    public function createPayment($payment, $amount, $description, $email, $items)
    {
        $client = new Client(['base_uri' => 'https://securepay.tinkoff.ru/v2/Init']);
        $amount = 10;
        $data = [
            'TerminalKey' => $this->terminalKey,
            'Amount' => $amount * 100,
            'OrderId' =>  $payment->id,
            'Description' => $description,
            "Recurrent" =>  "Y",
            "PayType" =>  "O",
            "Language" =>  "ru",
            'DATA' => [
                'Email' => $email,
            ],
            'Receipt' => [
                'Items' => $items,
                'Email' => $email,
                'Taxation' => 'osn',
            ],
            'NotificationURL' => 'https://outfitchecklist.ru/payment/notification', // обработка уведомлений о статусе платежей
            'SuccessURL' => 'https://outfitchecklist.ru/payment/success', // после успешной оплаты
            'FailURL' => 'https://outfitchecklist.ru/payment/fail' // в случае неуспешной оплаты
        ];

        $token = $this->generateToken($data);
        $data['Token'] = $token;
        try {
            $response = $client->post('Init', [
                'json' => $data,
                'headers' => [
                    'TerminalKey' => $this->terminalKey,
                ],
            ]);
            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                if (isset($responseData['Success']) && $responseData['Success'] == 1) {
                    $payment->payment_id = $responseData['PaymentId'];
                    if ($responseData['Status'] === 'NEW') {
                        $payment->status = 'начата оплата';
                    } else {
                        $payment->status = $responseData['Status'];
                    }                    if ($payment->save()) {
                        return $responseData['PaymentURL'];
                    } else {
                        var_dump($payment->getErrors());
                        exit();
                    }

                } else {
                    echo '<pre>';print_r($responseData); echo '</pre>';
                    exit();
                }
            }
        } catch (GuzzleHttp\Exception\GuzzleException $e) {
            echo $e->getMessage();
        }

        return false;
    }

    private function generateToken($data)
    {
        $dataString = Json::encode($data);
        $token = hash('sha256', $this->secretKey . $dataString);

        return $token;
    }

    public function checkPaymentStatus($paymentId)
    {
        $client = new Client(['base_uri' => 'https://securepay.tinkoff.ru/v2/GetState']);
        $data = [
            'TerminalKey' => $this->terminalKey,
            'PaymentId' => $paymentId,
        ];

        $token = $this->generateToken($data);
        $data['Token'] = $token;

        try {
            $response = $client->post('GetState', [
                'json' => $data,
                'headers' => [
                    'TerminalKey' => $this->terminalKey,
                ],
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                if (isset($responseData['Success']) && $responseData['Success'] == 'true') {
                    return $responseData;
                } else {
                    return false;
                }
            }
        } catch (\GuzzleHttp\Exception\GuzzleException $e) {
            echo $e->getMessage();
        }

        return false;
    }
}
