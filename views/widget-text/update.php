<?php

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetText */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'Text Block with key',
]) . ' ' . $model->key;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Text Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->key, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="text-block-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales
    ]) ?>

</div>
