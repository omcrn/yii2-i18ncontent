<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index table-wrapper">

<!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(
            Yii::t('i18ncontent', 'Create {modelClass}', ['modelClass' => 'Article']),
            ['create'],
            ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            'id',
            'slug',
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->activeTranslation ? $model->activeTranslation->title : "";
                }
            ],
            [
                'attribute' => 'category_id',
                'value' => function ($model) {
                    return $model->category ? $model->category->activeTranslation->title : null;
                },
                'filter' => \centigen\i18ncontent\models\ArticleCategory::getCategories()
            ],
            [
                'attribute' => 'author',
                'value' => function ($model) {
                    return $model->author->username;
                }
            ],
            [
                'class' => \centigen\base\grid\EnumColumn::className(),
                'attribute' => 'status',
                'enum' => [
                    Yii::t('i18ncontent', 'Not Published'),
                    Yii::t('i18ncontent', 'Published')
                ]
            ],
            [
                'attribute' => 'position',
                'contentOptions' => [
                    'style' => 'width: 120px'
                ]
            ],
            'published_at:datetime',
            'created_at:datetime',

            // 'updated_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}'
            ]
        ]
    ]); ?>

</div>
