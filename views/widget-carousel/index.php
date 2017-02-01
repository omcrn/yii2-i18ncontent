<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\WidgetCarouselSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Widget Carousels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-carousel-index i18ncontent-table-wrapper">

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
                'modelClass' => 'Widget Carousel',
            ]), ['create'], ['class' => 'btn btn-success']);
        echo ' '. Html::a(
            Yii::t('i18ncontent', 'Delete checked {modelClass}s', ['modelClass' => 'Widget Carousel']),
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
                'prefix' => '<div class="checkbox"><label>',
                'suffix' => '<span class="checkbox-material"><span class="check"></span></span></label></div>',
                'headerPrefix' => '<div class="checkbox"><label>',
                'headerSuffix' => '</label></div>',
                'options' => [
                    'style' => 'width: 1px;',
                    'class' => 'text-center',
                ],
                'contentOptions' => [
                    'style' => 'vertical-align: middle;'
                ]
            ],
            'key',
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
                'label' => Yii::t('i18ncontent', 'Items'),
                'format' => ['html'],
                'value' => function($model){
                    /** @var \centigen\i18ncontent\models\WidgetCarousel $model */
                    return \yii\bootstrap\Html::tag('span', count($model->items), ['class' => 'badge']);
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ],
        ],
    ]); ?>

</div>
