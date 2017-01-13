<?php

namespace centigen\i18ncontent\models;

use Yii;

/**
 * This is the model class for table "article_category_translation".
 *
 * @property integer $id
 * @property integer $article_category_id
 * @property string $locale
 * @property string $title
 * @property string $body
 *
 * @property ArticleCategory $articleCategory
 */
class ArticleCategoryTranslation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category_translation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_category_id', 'locale', 'title'], 'required'],
            [['article_category_id'], 'integer'],
            [['body'], 'string'],
            [['locale'], 'string', 'max' => 15],
            [['title'], 'string', 'max' => 512]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('i18ncontent', 'ID'),
            'article_category_id' => Yii::t('i18ncontent', 'Article Category ID'),
            'locale' => Yii::t('i18ncontent', 'Locale'),
            'title' => Yii::t('i18ncontent', 'Title'),
            'body' => Yii::t('i18ncontent', 'Body'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'article_category_id']);
    }
}
