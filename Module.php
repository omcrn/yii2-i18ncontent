<?php
namespace centigen\i18ncontent;
use yii\helpers\Url;

/**
 * Class I18nContentModule
 *
 * @author zura
 * @package centigen\i18ncontent
 */
class Module extends \yii\base\Module
{

    /**
     * Default layout which will be used in all actions
     *
     * @var string
     */
    public $defaultLayout = null;

    public function missingTranslation()
    {
        // @todo
    }
}