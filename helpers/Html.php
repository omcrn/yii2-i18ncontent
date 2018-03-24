<?php
/**
 * User: Zura
 * Date: 11/27/2015
 * Time: 1:43 PM
 */

namespace centigen\i18ncontent\helpers;
use centigen\base\i18n\Formatter;
use centigen\i18ncontent\Module;
use Yii;


/**
 * Class Html
 *
 * @author  Zura Sekhniashvili <zurasekhniashvili@gmail.com>
 * @package centigen\i18ncontent\helpers
 */
class Html extends \yii\bootstrap\Html
{

    /**
     * Replace $module's `mediaUrlPrefix` occurrence with $module's `mediaUrlReplacement` in given text
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param string $text
     * @return string
     */
    public static function encodeMediaItemUrls($text)
    {
        if (!$text){
            return $text;
        }
        $component = Yii::$app->i18ncontent;
        return str_replace($component->mediaUrlPrefix, $component->mediaUrlReplacement, $text);
    }

    /**
     * Replace $module's `mediaUrlReplacement` occurrence with $module's `mediaUrlPrefix` in given text
     *
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @param $text
     * @return mixed
     */
    public static function decodeMediaItemUrls($text)
    {
        if (!$text){
            return $text;
        }
        $component = Yii::$app->i18ncontent;
        return str_replace($component->mediaUrlReplacement, $component->mediaUrlPrefix, $text);
    }

    /**
     * @param $model
     * @param bool $disabled
     * @return string
     */
    public static function asFab($model, $disabled = false){
        $formatter = new Formatter();
        return $formatter->asToggle($model->status, $disabled);
    }




}