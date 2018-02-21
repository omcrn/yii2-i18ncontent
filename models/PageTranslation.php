<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\helpers\Html;
use Yii;

/**
 * This is the model class for table "page_translation".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $locale
 * @property string $title
 * @property string $short_description
 * @property string $body
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 *
 * @property Page $page
 */
class PageTranslation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'locale', 'title', 'body'], 'required'],
            [['page_id'], 'integer'],
            [['body', 'short_description'], 'string'],
            [['locale'], 'string', 'max' => 15],
            [['title', 'meta_title', 'meta_keywords', 'meta_description'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'page_id' => Yii::t('i18ncontent', 'Page ID'),
            'locale' => Yii::t('i18ncontent', 'Locale'),
            'title' => Yii::t('i18ncontent', 'Title'),
            'short_description' => Yii::t('i18ncontent', 'Short Description'),
            'body' => Yii::t('i18ncontent', 'Body'),
            'meta_title' => Yii::t('i18ncontent', 'Meta Title'),
            'meta_keywords' => Yii::t('i18ncontent', 'Meta Keywords'),
            'meta_description' => Yii::t('i18ncontent', 'Meta Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    public function getShortDescription()
    {
        return Html::decodeMediaItemUrls($this->short_description);
    }

    public function getBody()
    {
        return Html::decodeMediaItemUrls($this->body);
    }
}
