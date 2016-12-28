<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\Page */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'Page',
]) . ' ' . $model->slug;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->slug, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="page-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales
    ]) ?>

</div>
