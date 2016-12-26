<?php

namespace centigen\i18ncontent;


/**
 * Class AssetBundle
 *
 * @author zura
 * @package centigen\i18ncontent
 */
class AssetBundle extends \yii\web\AssetBundle
{
    public $baseUrl = '@web';

    public $publishOptions = [
        'forceCopy' => true
    ];

    public $css = [
        'css/main.css'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset'
    ];

    public function init()
    {
        $this->setSourcePath(__DIR__ . '/assets');
        parent::init();
    }
} 