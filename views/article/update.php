<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\Article */
/* @var $categories */
/* @var $locales */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'Article',
]) . ' ' . $model->getTitle();
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Articles'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->slug, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="article-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'locales' => $locales
    ]) ?>

</div>
