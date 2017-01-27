<?php

use backend\modules\i18n\models\search\I18nMessageSearch;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var $this yii\web\View
 * @var $searchModel I18nMessageSearch
 * @var $languages array
 * @var $categories array
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('i18ncontent', 'I18n Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="i18n-message-index">

    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php echo Html::a(Yii::t('i18ncontent', 'Create {modelClass}', [
            'modelClass' => 'I18n Message',
        ]), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'options' => [
            'class' => 'grid-view table-responsive'
        ],
        'columns' => [
            [
                'attribute' => 'category',
                'filter' => $categories
            ],
            'message',
            [
                'attribute' => 'language',
                'value' => function ($model) {
                    /** @var $model \centigen\i18ncontent\models\I18nMessage */
//                    \centigen\base\helpers\UtilHelper::vardump($model->language, \centigen\base\helpers\BaseHelper::getAvailableLocales());

                    return \centigen\base\helpers\LocaleHelper::getByKey($model['language']);
                },
                'filter' => $languages
            ],
            'translation:ntext',
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>