<?php

namespace app\controllers;

use app\components\Common;
use app\models\Category;
use yii\base\InvalidConfigException;
use yii\httpclient\Exception;
use yii\web\Controller;
use app\models\ContactForm;
use app\models\SubmitFeed;
use Yii;
use app\models\User;
use app\models\SourceList;
use app\models\UploadForm;
use yii\web\UploadedFile;
use app\models\Images;
use app\modules\SeoModule;

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
        return $this->render('adminPanel');
    }

}