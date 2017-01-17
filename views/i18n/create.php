<?php
/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\I18nSourceMessage */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Create {modelClass}', [
    'modelClass' => 'i18n',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'i18n'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-create">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales
    ]) ?>

</div>
