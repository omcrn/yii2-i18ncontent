<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\helpers\Html;
use Yii;

/**
 * This is the model class for table "widget_text_languages".
 *
 * @property integer $id
 * @property integer $widget_text_id
 * @property string $title
 * @property string $body
 * @property string $locale
 *
 * @property WidgetText $widgetText
 */
class WidgetTextTranslation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_text_translations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_text_id', 'locale'], 'required'],
            [['widget_text_id'], 'integer'],
            [['body'], 'string'],
            [['title'], 'string', 'max' => 512],
            [['locale'], 'string', 'max' => 15]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'widget_text_id' => Yii::t('i18ncontent', 'Widget Text ID'),
            'title' => Yii::t('i18ncontent', 'Title'),
            'body' => Yii::t('i18ncontent', 'Body'),
            'locale' => Yii::t('i18ncontent', 'Locale')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetText()
    {
        return $this->hasOne(WidgetText::className(), ['id' => 'widget_text_id']);
    }

    public function getBody()
    {
        return Html::decodeMediaItemUrls($this->body);
    }
}
