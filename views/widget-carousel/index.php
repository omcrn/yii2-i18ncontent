<?php

use centigen\base\grid\EnumColumn;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\WidgetCarouselSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Widget Carousels');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-carousel-index table-wrapper">

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
                'modelClass' => 'Widget Carousel',
            ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'key',
            [
                'class' => \centigen\base\grid\EnumColumn::className(),
                'attribute' => 'status',
                'format' => ['statusLabel'],
                'enum' => [
                    Yii::t('i18ncontent', 'Inactive'),
                    Yii::t('i18ncontent', 'Active')
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ],
        ],
    ]); ?>

</div>
