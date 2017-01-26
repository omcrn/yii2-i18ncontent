<?php
/**
 * User: zura
 * Date: 1/26/17
 * Time: 11:20 AM
 */

namespace centigen\i18ncontent\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%article_category_article}}".
 *
 * @property integer $id
 * @property integer $article_id
 * @property integer $category_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Article $article
 * @property ArticleCategory $articleCategory
 */
class ArticleCategoryArticle extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%article_category_article}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'category_id'], 'required'],
            [['article_id', 'category_id', 'created_at', 'updated_at'], 'integer'],
            [['article_id'], 'exist', 'skipOnError' => true, 'targetClass' => Article::className(), 'targetAttribute' => ['article_id' => 'id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => ArticleCategory::className(), 'targetAttribute' => ['category_id' => 'id']],
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
            'category_id' => Yii::t('i18ncontent', 'Article Category ID'),
            'created_at' => Yii::t('i18ncontent', 'Created At'),
            'updated_at' => Yii::t('i18ncontent', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticle()
    {
        return $this->hasOne(Article::className(), ['id' => 'article_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getArticleCategory()
    {
        return $this->hasOne(ArticleCategory::className(), ['id' => 'category_id']);
    }
}