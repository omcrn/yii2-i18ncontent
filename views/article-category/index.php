<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel centigen\i18ncontent\models\search\ArticleCategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('i18ncontent', 'Article Categories');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-category-index table-wrapper">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'Article Category',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'slug',
            [
                'attribute' => 'title',
                'value' => function ($model) {
                    return $model->activeTranslation ? $model->activeTranslation->title : "";
                }
            ],
            'status',
            [
                'attribute' => 'parent.activeTranslation.title',
                'label' => Yii::t('backend', 'Parent category')
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template'=>'{update} {delete}'
            ],
        ],
    ]); ?>

</div>
