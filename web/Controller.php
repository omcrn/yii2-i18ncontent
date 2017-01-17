<?php

namespace centigen\i18ncontent\web;
use centigen\i18ncontent\AssetBundle;

/**
 * Class Controller
 *
 * @author zura
 * @package centigen\i18ncontent\web
 */
class Controller extends \yii\web\Controller
{
    public function init()
    {
        parent::init();
        AssetBundle::register($this->getView());

    }


    public function beforeAction($action)
    {
        if(isset(\Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset'])){
             \Yii::$app->assetManager->bundles['yii\bootstrap\BootstrapPluginAsset']->depends[] = 'yii\jui\JuiAsset';
        }
        return parent::beforeAction($action);
    }
} 