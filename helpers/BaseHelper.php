<?php

namespace centigen\i18ncontent\helpers;
use Yii;

/**
 * Class BaseHelper
 *
 * @author zura
 * @package centigen\i18ncontent\helpers
 */
class BaseHelper 
{
    public static function getAvailableLocales()
    {
        $locales = [];
        if (isset(Yii::$app->params['availableLocales'])) {
            $locales = Yii::$app->params['availableLocales'];
        }

        if (count($locales) === 0) {
            $locales = [Yii::$app->language];
        }

        return $locales;
    }
} 