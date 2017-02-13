<?php
/**
 * Created by PhpStorm.
 * User: koco
 * Date: 2/2/2017
 * Time: 1:07 PM
 * @var $items array
 * @var $form yii\bootstrap\ActiveForm
 * @var $model \centigen\i18ncontent\models\WidgetMenu
 */

use centigen\i18ncontent\helpers\MenuHelper;

$menu = new MenuHelper();
echo $menu->getItems($items, $form, $model);
?>