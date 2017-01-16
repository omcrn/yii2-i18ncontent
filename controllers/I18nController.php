<?php
/**
 * Created by PhpStorm.
 * User: guga
 * Date: 1/16/17
 * Time: 11:40 AM
 */

namespace centigen\i18ncontent\controllers;


use centigen\i18ncontent\web\Controller;
use yii\filters\VerbFilter;

class I18nController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ],
            \centigen\base\behaviors\LayoutBehavior::className()
        ];
    }

}