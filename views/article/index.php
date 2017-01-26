<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
    </p>

    <?php
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [

            [
                'attribute' => 'id',
                'contentOptions' => [
                    'style' => 'width: 100px'
                ]
            ],
            [
                'attribute' => 'slug',
                'contentOptions' => [
                    'style' => 'width: auto'
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
                'attribute' => 'author',
                'value' => function ($model) {
                    return $model->author->username;
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
                    'class' => '',
                    'style' => 'width: 1px'
                ],
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
                'contentOptions' => [
                    'style' => 'width: 80px'
                ]
            ]
        ]
    ]); ?>

</div>
