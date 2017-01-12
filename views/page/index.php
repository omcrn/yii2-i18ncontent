<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\PageSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Pages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-index table-wrapper">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'Page',
]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->activeTranslation ? $model->activeTranslation->title : "";
                }
            ],
            'slug',
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

<!--    --><?php //ChromePhp::log(\centigen\cms\helpers\QueryHelper::getRawSql($dataProvider->query)); ?>

</div>
