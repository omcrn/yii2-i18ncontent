<?php
/**
 * Created by PhpStorm.
 * User: koco
 * Date: 2/2/2017
 * Time: 1:07 PM
 * @var $base_options array
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \centigen\i18ncontent\models\WidgetMenu
 */


\centigen\i18ncontent\helpers\MenuHelper::decodeOptions($base_options);
$optionProperties = \centigen\i18ncontent\helpers\AnnotationHelper::getPublicProperties(\yii\widgets\Menu::className());
unset($optionProperties[0]);

?>
<div class="modal fade" id="options-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"
                    id="myModalLabel"><?php echo Yii::t('i18ncontent', 'Menu widget options') ?></h4>
            </div>
            <div class="modal-body">
                <?php echo \centigen\i18ncontent\helpers\MenuHelper::generateMenuBaseOptionInputs($model, $form, $optionProperties, $base_options); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('i18ncontent', 'Ok')?></button>
            </div>
        </div>
    </div>
</div>
