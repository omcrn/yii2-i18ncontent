<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel \centigen\i18ncontent\models\search\WidgetTextSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Text Blocks');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="text-block-index table-wrapper">

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
            ['class' => 'yii\grid\SerialColumn'],
            'key',

            [
                'attribute' => 'title',
                'value' => function($model) {
                    return $model->activeTranslation ? $model->activeTranslation->title : "";
                }
            ],
            [
                'class' => \centigen\base\grid\EnumColumn::className(),
                'attribute' => 'status',
                'format' => ['statusLabel'],
                'enum' => [
                    Yii::t('i18ncontent', 'Inactive'),
                    Yii::t('i18ncontent', 'Active')
                ],
            ],
            'created_at:datetime',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{delete}'
            ],
        ],
    ]); ?>

</div>
