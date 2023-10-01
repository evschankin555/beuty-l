<?php



namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAssetAdmin extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];

    public function init()
    {
        $customCssVersion = filemtime(\Yii::getAlias("@webroot/css/custom.min.css"));
        $toastJsVersion = filemtime(\Yii::getAlias("@webroot/js/toast-messages.js"));
        $this->css = [
            'css/bootstrap.min.css',
            'css/custom.min.css?v=' . $customCssVersion,
        ];
        $this->js = [
            'js/toast-messages.js?v=' . $toastJsVersion,
        ];

        parent::init();
    }
}