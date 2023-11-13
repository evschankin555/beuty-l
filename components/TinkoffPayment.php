<?php
namespace app\components;

use yii\base\Component;
use yii;
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
        $data = [
            'TerminalKey' => $this->terminalKey,
            'Amount' => $amount * 100,
            'OrderId' =>  $payment->id,
            'Description' => $description,
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
                    $this->setPaymentIdToCookie($responseData['PaymentId']);
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
        unset($data['Token']);

        ksort($data);

        $tokenValues = array_map(function($value) {
            return is_array($value) ? implode(array_map(function($innerValue) {
                return is_array($innerValue) ? json_encode($innerValue) : (string) $innerValue;
            }, $value)) : (string) $value;
        }, $data);

        $tokenString = implode('', $tokenValues);
        $tokenString .= $this->secretKey;

        return hash('sha256', $tokenString);
    }


    public function checkPaymentStatus($paymentId)
    {
        $client = new Client(['base_uri' => 'https://securepay.tinkoff.ru/v2/GetState']);
        $data = [
            'TerminalKey' => $this->terminalKey,
            'PaymentId' => (int)$paymentId,
        ];

        // Создание токена
        $tokenString = $this->terminalKey . $paymentId . $this->secretKey;
        $token = hash('sha256', $tokenString);
        $data['Token'] = strtolower($token);

        try {
            $response = $client->post('GetState', [
                'json' => $data
            ]);

            if ($response->getStatusCode() === 200) {
                $responseData = json_decode($response->getBody()->getContents(), true);
                if (isset($responseData['Success']) && $responseData['Success'] == 1) {
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

    /**
     * Сохраняет PaymentId в куках
     *
     * @param string $paymentId
     * @return void
     */
    private function setPaymentIdToCookie($paymentId)
    {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new \yii\web\Cookie([
            'name' => 'PaymentId',
            'value' => $paymentId,
            'expire' => time() + 86400, // Куки будет живым 24 часа.
        ]));
    }


}
