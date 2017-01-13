<?php

namespace centigen\i18ncontent\models;

use Yii;

/**
 * This is the model class for table "widget_carousel_item_languages".
 *
 * @property integer $id
 * @property integer $widget_carousel_item_id
 * @property string $caption
 * @property string $locale
 * @property integer $active
 * @property integer $created_at
 * @property integer $updated_at
 * @property WidgetCarouselItem $widgetCarouselItem
 */
class WidgetCarouselItemTranslation extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_carousel_item_translations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_carousel_item_id', 'locale'], 'required'],
            [['widget_carousel_item_id'], 'integer'],
            [['locale'], 'string', 'max' => 15],
            [['caption'], 'string', 'max' => 1024]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'widget_carousel_item_id' => Yii::t('i18ncontent', 'Widget Carousel Item ID'),
            'caption' => Yii::t('i18ncontent', 'Caption'),
            'locale' => Yii::t('i18ncontent', 'Locale')
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWidgetCarouselItem()
    {
        return $this->hasOne(WidgetCarouselItem::className(), ['id' => 'widget_carousel_item_id']);
    }
}
