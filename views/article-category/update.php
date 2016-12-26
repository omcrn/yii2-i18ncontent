<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\ArticleCategory */
/* @var $categories array */
/* @var $locales array */
/* @var $translations array */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'Article Category',
]) . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Article Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="article-category-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'locales' => $locales,
        'translations' => $translations
    ]) ?>

</div>
