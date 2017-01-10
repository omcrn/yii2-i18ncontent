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
<div class="widget-carousel-update table-wrapper">

    <?php echo $this->render('_form', [
        'model' => $model,
    ]) ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'Widget Carousel Item',
        ]), ['widget-carousel-item/create', 'carousel_id' => $model->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $carouselItemsProvider,
        'columns' => [
            'order',
            [
                'attribute' => 'path',
                'format' => 'raw',
                'value' => function ($model) {
                    /* @var $model \centigen\i18ncontent\models\WidgetCarouselItem */
                    return $model->path ? Html::img($model->getImageUrl(), ['style' => 'width: 200px;']) : null;
                }
            ],
            'url:url',
            [
                'format' => 'html',
                'attribute' => 'activeTranslation.caption',
                'options' => ['style' => 'width: 20%']
            ],
            'status',

            [
                'class' => 'yii\grid\ActionColumn',
                'controller' => 'widget-carousel-item',
                'template' => '{update} {delete}'
            ],
        ],
    ]); ?>


</div>
