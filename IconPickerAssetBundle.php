<?php

namespace centigen\i18ncontent;


/**
 * Class AssetBundle
 *
 * @author zura
 * @package centigen\i18ncontent
 */
class IconPickerAssetBundle extends \yii\web\AssetBundle
{
    public $baseUrl = '@web';

    public $css = [
        'css/fontawesome-iconpicker.min.css'
    ];

    public $js = [
      'js/fontawesome-iconpicker.min.js'
    ];

    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset'
    ];

    public function init()
    {
        $this->sourcePath = '@bower/itsjavi/fontawesome-iconpicker/dist/';
        parent::init();
    }
} 