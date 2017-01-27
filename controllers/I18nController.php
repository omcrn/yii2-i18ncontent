<?php
/**
 * Created by PhpStorm.
 * User: guga
 * Date: 1/16/17
 * Time: 11:40 AM
 */

namespace centigen\i18ncontent\controllers;


use centigen\base\helpers\LocaleHelper;
use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\I18nMessage;
use centigen\i18ncontent\models\I18nSourceMessage;
use centigen\i18ncontent\models\search\I18nSearch;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

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

        $categories = ArrayHelper::map(
            I18nSourceMessage::find()->select('category')->distinct()->all(),
            'category',
            'category'
        );

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'languages' => LocaleHelper::getAvailableLocales(),
            'categories' => $categories
        ]);
    }

    /**
     * Creates a new I18n model.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new I18nSourceMessage();

        $locales = BaseHelper::getAvailableLocales();

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index']);
        }
        $categories = ArrayHelper::map(
            I18nSourceMessage::find()->select('category')->distinct()->all(),
            'category',
            'category'
        );
        return $this->render('update', [
            'model' => $model,
            'locales' => $locales,
            'categories' => $categories
        ]);
    }


    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post(), null) && $model->save()) {
            return $this->redirect(['index']);
        }
        $categories = ArrayHelper::map(
            I18nSourceMessage::find()->select('category')->distinct()->all(),
            'category',
            'category'
        );
        $locales = BaseHelper::getAvailableLocales();

        return $this->render('update', [
            'model' => $model,
            'locales' => $locales,
            'categories' => $categories
        ]);
    }

    /**
     * Finds the I18nSourceMessage model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return I18nSourceMessage the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = I18nSourceMessage::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}