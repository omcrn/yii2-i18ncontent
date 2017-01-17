<?php
/**
 * Created by PhpStorm.
 * User: guga
 * Date: 1/16/17
 * Time: 11:40 AM
 */

namespace centigen\i18ncontent\controllers;


use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\I18nMessage;
use centigen\i18ncontent\models\I18nSourceMessage;
use centigen\i18ncontent\models\search\I18nSearch;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

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


    /**
     * Lists all I18nMessage models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new I18nSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Url::remember(Yii::$app->request->getUrl(), 'i18n-messages-filter');

        $languages = ArrayHelper::map(
            I18nMessage::find()->select('language')->distinct()->all(),
            'language',
            'language'
        );
        $categories = ArrayHelper::map(
            I18nSourceMessage::find()->select('category')->distinct()->all(),
            'category',
            'category'
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => $languages,
            'categories' => $categories
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new I18nSourceMessage();

        $locales = BaseHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
            'locales' => $locales
        ]);
    }

}