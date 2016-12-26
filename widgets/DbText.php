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
        return $this->getModel()->title;
    }

    public function getBody()
    {
        return $this->getModel()->body;
    }

    public function getModel()
    {
        if (!$this->model) {
            $this->model = WidgetText::find()->joinWith('activeTranslation')
                ->where(['key' => $this->key, 'status' => WidgetText::STATUS_ACTIVE])->one();
        }
        if(!$this->model){
            return "";
        }
        return $this->model->activeTranslation;
    }
}
