<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $articleCategories \centigen\i18ncontent\models\ArticleCategory[] */

$this->title = Yii::t('i18ncontent', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index i18ncontent-table-wrapper">

    <p>
        <?php
        echo Html::a(
            Yii::t('i18ncontent', 'Create {modelClass}', ['modelClass' => 'Article']),
            ['create'],
            ['class' => 'btn btn-success'])
        ?>
        <?php
        echo Html::a(
            Yii::t('i18ncontent', 'Delete checked {modelClass}s', ['modelClass' => 'Article']),
            [null],
            ['class' => 'btn btn-danger delete-multiple'])
        ?>
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => [
            'class' => 'table table-striped table-bordered table-hover'
        ],
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
            [
                'attribute' => 'thumbnail',
                'format' => 'html',
                'value' => function ($model) {
                    if (!$model->thumbnail_path){
                        return "";
                    }
                    return Html::img(Yii::getAlias('@storageUrl/source/' . $model->thumbnail_path), ['class' => 'img-responsive']);
                },
                'contentOptions' => [
                    'style' => 'width: 100px'
                ]
            ],
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->activeTranslation ? $model->activeTranslation->title : "";
                },
                'contentOptions' => [
                    'style' => 'width: auto'
                ]
            ],
            [
                'label' => Yii::t('i18ncontent', 'Categories'),
                'attribute' => 'category_ids',
                'filter' => $articleCategories,
                'format' => ['html'],
                'value' => function ($model) {
                    /** @var $model \centigen\i18ncontent\models\Article */
//                    \centigen\base\helpers\UtilHelper::vardump($model->toArray());
                    $content = '';
                    foreach ($model->articleCategoryArticles as $articleCategoryArticle) {
                        $content .= \yii\bootstrap\Html::tag('span', $articleCategoryArticle->articleCategory->getTitle(), ['class' => 'label label-default']);
                    }
                    return $content;
                },
                'contentOptions' => [
                    'style' => 'width: auto'
                ]
            ],
            [
                'label' => Yii::t('i18ncontent', 'Status'),
                'attribute' => 'status',
                'content' => function ($model) {
                    /** @var \common\models\User $model */
                    return \centigen\i18ncontent\helpers\Html::asFab($model);
                },
                'headerOptions' => [
                    'class' => 'text-center',
                ],
                'contentOptions' => [
                    'class' => '',
                    'style' => 'width: 120px'
                ],
                'filter' => \centigen\i18ncontent\helpers\BaseHelper::getStatusOptionsArray()
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
                'template' => '{update} {delete}',
                'buttonOptions' => [
                    'class' => 'btn btn-sm btn-default'
                ],
                'contentOptions' => [
                    'class' => 'actions-column'
                ]
            ]
        ]
    ]); ?>

</div>
