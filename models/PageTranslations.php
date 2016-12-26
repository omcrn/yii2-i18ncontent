<?php

namespace centigen\i18ncontent\models;

use Yii;

/**
 * This is the model class for table "page_translations".
 *
 * @property integer $id
 * @property integer $page_id
 * @property string $locale
 * @property string $title
 * @property string $body
 * @property string $meta_title
 * @property string $meta_keywords
 * @property string $meta_description
 *
 * @property Page $page
 */
class PageTranslations extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%page_translations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id'], 'required'],
            [['page_id'], 'integer'],
            [['body'], 'string'],
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
}
