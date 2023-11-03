<?php
namespace app\components;

use yii\base\Component;
use yii\helpers\Json;
use GuzzleHttp\Client;

class TinkoffPayment extends Component
{
    public $terminalKey;
    public $secretKey;
    public function init()
    {
        parent::init();
    }

    public function createPayment($orderId, $amount, $description, $email, $items)
    {
        $client = new Client(['base_uri' => 'https://securepay.tinkoff.ru/v2/Init']);
        $amount = 10;
        $data = [
            'TerminalKey' => $this->terminalKey,
            'Amount' => $amount * 100,
            'OrderId' =>  $orderId,
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
                if (isset($responseData['Success']) && $responseData['Success'] == 'true') {
                    return $responseData['PaymentURL'];
                }else{
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
}
