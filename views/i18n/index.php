<?php

use centigen\i18ncontent\models\search\I18nSearch;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var $this yii\web\View
 * @var $searchModel I18nSearch
 * @var $languages array
 * @var $categories array
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('i18ncontent', 'I18n Messages');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="i18n-message-index i18ncontent-table-wrapper">

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
                'attribute' => 'translation',
                'label' => Yii::t('i18ncontent', 'Translations'),
                'format' => ['html'],
                'value' => function ($model) {
                    /** @var $model \centigen\i18ncontent\models\I18nSourceMessage */
                    $content = '';
                    foreach (\centigen\base\helpers\LocaleHelper::getAvailableLocales() as $key => $locale) {
                        $found = false;
                        foreach ($model->i18nMessages as $translation) {
                            if (\centigen\base\helpers\LocaleHelper::isEqual($translation->language, $key)) {
                                $found = true;
                                $content .= \yii\bootstrap\Html::tag('span', $locale, ['class' => 'label label-success']);
                                break;
                            }
                        }
                        if (!$found) {
                            $content .= \yii\bootstrap\Html::tag('span', $locale, ['class' => 'label label-default']);
                        }
                    }
                    return $content;
                },
                'contentOptions' => [
                    'class' => 'i18n-language-translations'
                ]
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{update} {delete}'],
        ],
    ]); ?>

</div>