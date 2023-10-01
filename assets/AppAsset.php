<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [];
    public $jsOptions = ['position' => \yii\web\View::POS_END];

    public function init()
    {
        parent::init();

        $mainCssFile = 'css/main.min.css';
        $mainCssFilePath = $this->basePath . '/' . $mainCssFile;
        if (file_exists($mainCssFilePath)) {
            $mainVersion = filemtime($mainCssFilePath);
            $this->css[] = $mainCssFile . '?v=' . $mainVersion;
        } else {
            $this->css[] = $mainCssFile;
        }

        $cssFile = 'css/site.css';
        $cssFilePath = $this->basePath . '/' . $cssFile;
        if (file_exists($cssFilePath)) {
            $version = filemtime($cssFilePath);
            $this->css[] = $cssFile . '?v=' . $version;
        } else {
            $this->css[] = $cssFile;
        }

        $this->js[] = 'js/jquery.min.js';

        $jsFile = 'js/app.js';
        $jsFilePath = $this->basePath . '/' . $jsFile;
        if (file_exists($jsFilePath)) {
            $jsVersion = filemtime($jsFilePath);
            $this->js[] = $jsFile . '?v=' . $jsVersion;
        } else {
            $this->js[] = $jsFile;
        }
    }
}
