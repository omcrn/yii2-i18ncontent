<?php
/**
 * User: zura
 * Date: 10/27/2016
 * Time: 10:41 AM
 */
use yii\helpers\Html;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel \centigen\i18ncontent\models\search\WidgetMenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('i18ncontent', 'Widget Menus');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="widget-menu-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'Widget Menu',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            'title',
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
            ['class' => 'yii\grid\ActionColumn', 'template'=>'{update} {delete}'],
        ],
    ]); ?>

</div>