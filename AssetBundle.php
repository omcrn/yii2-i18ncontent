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
    public $sourcePath = '@vendor/centigen/i18ncontent/assets';
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
} 