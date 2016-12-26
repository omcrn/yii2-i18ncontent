<?php
/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\WidgetText */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'Text Block',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Text Blocks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="text-block-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales
    ]) ?>

</div>
