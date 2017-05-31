<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetCarousel */
/* @var $carouselItemsProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
        'modelClass' => 'Widget Carousel',
    ]) . ' ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Widget Carousels'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->key, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="widget-carousel-update i18ncontent-table-wrapper">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'Widget Carousel Item',
        ]), ['widget-carousel-item/create', 'carousel_id' => $model->id], ['class' => 'btn btn-success']);

        echo ' '. Html::a(
            Yii::t('i18ncontent', 'Delete checked {modelClass}s', ['modelClass' => 'Widget Carousel Item']),
            [null],
            ['class' => 'btn btn-danger delete-multiple', 'data-url' => '/i18ncontent/widget-carousel-item/delete']);
        ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $carouselItemsProvider,
        'rowOptions' => function(){
            return [
                'data-toggle-action' => '/i18ncontent/widget-carousel-item/toggle-status'
            ];
        },
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
                'attribute' => 'order',
                'contentOptions' => [
                    'style' => 'width: 50px;'
                ]
            ],
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model \centigen\i18ncontent\models\WidgetCarouselItem */
                    return $model->path ? Html::img($model->getImageUrl(), ['class' => 'img-responsive']) : null;
                },
                'contentOptions' => [
                    'style' => 'width: 200px;'
                ]
            ],
            [
                'attribute' => 'url',
                'format' => ['url']
            ],
            [
                'format' => 'html',
                'attribute' => 'activeTranslation.caption',
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
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttonOptions' => [
                    'class' => 'btn btn-sm btn-default'
                ],
                'contentOptions' => [
                    'class' => 'actions-column'
                ]
            ]
        ],
    ]); ?>


</div>
