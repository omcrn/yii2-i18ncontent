<?php

namespace centigen\i18ncontent\components;

/**
 * User: giokoco
 * Date: 9/10/2015
 * Time: 10:44 AM
 */
class LayoutBehavior extends \yii\base\Behavior
{
    public function events()
    {
        return [
            \yii\web\Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($event)
    {
        $event->sender->layout = 'main';
    }
} 