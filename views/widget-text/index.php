<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \centigen\i18ncontent\models\search\WidgetTextSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Text Blocks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-text-index i18ncontent-table-wrapper">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'Text Block',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'key',
            [
                'attribute' => 'title',
                'value' => function($model) {
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
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}'
            ],
        ],
    ]); ?>

</div>
