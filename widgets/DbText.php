<?php

namespace centigen\i18ncontent\widgets;

use centigen\i18ncontent\models\WidgetText;
use yii\base\Widget;
use Yii;

/**
 * Class DbText
 * Return a text block content stored in db
 *
 * @package common\widgets\text
 */
class DbText extends Widget
{
    /**
     * @var string text block key
     */
    public $key;

    /**
     * @author Zura Sekhniashvili <zurasekhniashvili@gmail.com>
     * @var WidgetText
     */
    private $model = null;

    public $attribute = 'body';

    /**
     * @return string
     */
    public function run()
    {
        return $this->getModel() ? $this->getModel()->{$this->attribute} : "";
    }

    public function getTitle()
    {
        return $this->getModel() ? $this->getModel()->title : "";
    }

    public function getBody()
    {
        return $this->getModel() ? $this->getModel()->body : "";
    }

    public function getModel()
    {
        if (!$this->model) {
            $this->model = WidgetText::find()->where(['key' => $this->key, 'status' => WidgetText::STATUS_ACTIVE])->one();
            if (!$this->model) {
                Yii::error("No text widget found for key: \"" . $this->key . "\"");
                return null;
//                throw new \InvalidArgumentException("No text widget found for key: \"".$this->key."\"");
            }
            if (!$this->model->activeTranslation) {
                Yii::error("Text widget \"{$this->key}\" does not have translation for \"" . Yii::$app->language . "\" language");
                return null;
//                throw new \InvalidArgumentException("Text widget \"{$this->key}\" does not have translation for \"".Yii::$app->language."\" language");
            }
        }
        return $this->model->activeTranslation;
    }
}
