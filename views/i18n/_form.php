<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\I18nSourceMessage */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $locales array */
?>

<div class="i18ncontent-form article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'category') ?>

    <?php echo $form->field($model, 'message')->textarea(['rows' => 2]) ?>

    <?php

    if (isset($locales)) {
        $items = [];
        $ind = 0;
        foreach ($locales as $key => $locale) {
            $title = $locale;
            $translationModel = $model->findTranslationByLocale($key);

            $content = $this->render('_tab_content', [
                'form' => $form,
                'model' => $translationModel,
                'language' => $key,
            ]);

            $items[] = [
                'label' => $title,
                'content' => $content,
                'headerOptions' => [
                    'title' => $translationModel->hasErrors() ? Yii::t('i18ncontent', 'You have validation errors') : "",
                    'class' => $translationModel->hasErrors() ? 'has-error' : '',
                    'data-toggle' => 'tooltip'
                ],
                'options' => [
                    'class' => 'fade' . ($ind++ === 0 ? ' in' : '')
                ]
            ];
        }
        echo '<div class="tab-wrapper">';
        echo Tabs::widget([
            'items' => $items
        ]);
        echo '</div>';
    } else {
        echo $this->render('_tab_content', [
            'form' => $form,
            'model' => $model
        ]);
    }

    ?>


    <div class="form-group pull-left margin-right-5">
        <?php echo Html::submitButton(
            $model->isNewRecord ? Yii::t('i18ncontent', 'Create') : Yii::t('i18ncontent', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if(!$model->isNewRecord): ?>
        <div class="form-group">
            <?php echo Html::a(Yii::t('i18ncontent', 'Delete', []), ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data-confirm' => "Are you sure you want to delete this item?",
                    'data-method'=>"post",
                    'data-pjax' => "0"
                ]) ?>
        </div>
    <?php endif?>
</div>
