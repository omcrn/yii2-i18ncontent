<?php

namespace centigen\i18ncontent\helpers;
use Yii;

/**
 * Class BaseHelper
 *
 * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package centigen\i18ncontent\helpers
 */
class BaseHelper 
{
    /**
     * Returns availableLocales from `Yii::$app->params` if it is set. If not it will return Yii::$app->language
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @return array
     * @deprecated Will be removed on v2.0.0. Use \centigen\base\helpers\LocaleHelper::getAvailableLocales instead
     */
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