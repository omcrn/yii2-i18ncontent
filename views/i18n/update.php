<?php
/**
 * User: zura
 * Date: 1/27/17
 * Time: 11:26 AM
 */

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\I18nSourceMessage */
/* @var $locales array */

$this->title = Yii::t('i18ncontent', 'Update {modelClass}: ', [
    'modelClass' => 'I18n message',
]) . ' ' . $model->message;
$this->params['breadcrumbs'][] = ['label' => Yii::t('i18ncontent', 'I18n messages'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('i18ncontent', 'Update');
?>
<div class="i18n-message-update">

    <?php echo $this->render('_form', [
        'model' => $model,
        'locales' => $locales
    ]) ?>

</div>
