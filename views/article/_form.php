<?php

use yii\bootstrap\Tabs;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model centigen\i18ncontent\models\Article */
/* @var $categories centigen\i18ncontent\models\ArticleCategory[] */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $locales array */
?>

<div class="i18ncontent-form article-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php echo $form->field($model, 'slug')
        ->hint(Yii::t('i18ncontent', 'If you\'ll leave this field empty, slug will be generated automatically'))
        ->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'category_ids', [
        'inputOptions' => [
            'multiple' => 'multiple'
        ]
    ])->dropDownList($categories) ?>

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

    <?php echo $form->field($model, 'thumbnail')->widget(
        \trntv\filekit\widget\Upload::className(),
        [
            'url' => ['upload'],
            'maxFileSize' => 5000000, // 5 MiB
        ]);
    ?>

    <?php echo $form->field($model, 'attachments')->widget(
        \trntv\filekit\widget\Upload::className(),
        [
            'url' => ['upload'],
            'sortable' => true,
            'maxFileSize' => 10000000, // 10 MiB
            'maxNumberOfFiles' => 10
        ]);
    ?>

    <?php echo $form->field($model, 'position')->input('number') ?>

    <?php echo $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'view')->textInput(['maxlength' => true]) ?>

    <?php echo $form->field($model, 'status')->checkbox([
        'template' => '<div class="om-checkbox"><label style="padding-left: 0;">'.$model->getAttributeLabel('status').' {input}<span class="om-om-checkbox-material"><span class="check"></span></span></label></div>{hint}{error}'
    ]) ?>

    <?php echo $form->field($model, 'published_at')->widget(
        'trntv\yii\datetime\DateTimeWidget',
        [
            'momentDatetimeFormat' => \centigen\base\helpers\DateHelper::getMomentDatetimeFormat() ?: 'yyyy-MM-dd\'T\'HH:mm:ssZZZZZ',
        ]
    ) ?>

    <?php echo $form->field($model,'show_published_at')->checkbox() ?>

    <div class="form-group pull-left margin-right-5">
        <?php echo Html::submitButton(
            $model->isNewRecord ? Yii::t('i18ncontent', 'Create') : Yii::t('i18ncontent', 'Update'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php if (!$model->isNewRecord): ?>
        <div class="form-group">
            <?php echo Html::a(Yii::t('i18ncontent', 'Delete', []), ['delete', 'id' => $model->id],
                [
                    'class' => 'btn btn-danger',
                    'data-confirm' => "Are you sure you want to delete this item?",
                    'data-method' => "post",
                    'data-pjax' => "0"
                ]) ?>
        </div>
    <?php endif ?>
</div>
