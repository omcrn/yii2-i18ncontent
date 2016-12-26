<?php
/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\Page */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'Page',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'Pages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales
    ]) ?>

</div>
