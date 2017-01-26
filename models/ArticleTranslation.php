<?php

namespace centigen\i18ncontent\models;

use centigen\i18ncontent\helpers\Html;
use Yii;

/**
 * This is the model class for table "article_translation".
 *
 * @property integer $id
 * @property integer $article_id
 * @property string $locale
 * @property string $title
 * @property string $body
 * @property string $short_description
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property string $keywords
 *
 * @property Article $article
 */
class ArticleTranslation extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'locale', 'title', 'body'], 'required'],
            [['article_id'], 'integer'],
            [['body','short_description'], 'string'],
            [['locale'], 'string', 'max' => 15],
            [['title', 'keywords', 'meta_title', 'meta_description', 'meta_keywords'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'article_id' => Yii::t('i18ncontent', 'Article ID'),
            'locale' => Yii::t('i18ncontent', 'Locale'),
            'title' => Yii::t('i18ncontent', 'Title'),
            'body' => Yii::t('i18ncontent', 'Body'),
            'short_description' => Yii::t('i18ncontent', 'Short Description'),
            'meta_title' => Yii::t('i18ncontent', 'Meta Title'),
            'meta_description' => Yii::t('i18ncontent', 'Meta Description'),
            'meta_keywords' => Yii::t('i18ncontent', 'Meta Keywords'),
            'keywords' => Yii::t('i18ncontent', 'Keywords'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    public function getBody()
    {
        return Html::decodeMediaItemUrls($this->body);
    }

    public function getShortDescription()
    {
        return Html::decodeMediaItemUrls($this->short_description);
    }
}
