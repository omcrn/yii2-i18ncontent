<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\ArticleCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $categories \centigen\i18ncontent\models\ArticleCategory[] */

$this->title = Yii::t('i18ncontent', 'Article Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index i18ncontent-table-wrapper">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'Article Category',
        ]), ['create'], ['class' => 'btn btn-success']);
        echo ' '. Html::a(
            Yii::t('i18ncontent', 'Delete checked {modelClass}s', ['modelClass' => 'Article Category']),
            [null],
            ['class' => 'btn btn-danger delete-multiple'])
        ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'class' => \centigen\base\grid\CheckboxColumn::className(),
                'prefix' => '<div class="om-checkbox"><label>',
                'suffix' => '<span class="om-checkbox-material"><span class="check"></span></span></label></div>',
                'headerPrefix' => '<div class="om-checkbox"><label>',
                'headerSuffix' => '</label></div>',
                'contentOptions' => [
                    'style' => 'width: 40px; vertical-align: middle;'
                ]
            ],
            'slug',
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->activeTranslation ? $model->activeTranslation->title : "";
                }
            ],
            [
                'label' => Yii::t('i18ncontent', 'Status'),
                'attribute' => 'status',
                'content' => function($model){
                    /** @var \common\models\User $model */
                    return \centigen\i18ncontent\helpers\Html::asFab($model);
                },
                'headerOptions' => [
                    'class' => 'text-center',
                ],
                'contentOptions' => [
                    'style' => 'width: 120px'
                ],
                'filter' => \centigen\i18ncontent\helpers\BaseHelper::getStatusOptionsArray()
            ],
            [
                'attribute' => 'parentCategory',
                'value' => function($model){
                    /** @var $model \centigen\i18ncontent\models\ArticleCategory */
                    return $model->parent ? $model->parent->getTitle() : null;
                },
                'label' => Yii::t('i18ncontent', 'Parent category'),
                'filter' => $categories
            ],
            'published_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttonOptions' => [
                    'class' => 'btn btn-sm btn-default'
                ],
                'contentOptions' => [
                    'class' => 'actions-column'
                ]
            ],
        ],
    ]); ?>

</div>
