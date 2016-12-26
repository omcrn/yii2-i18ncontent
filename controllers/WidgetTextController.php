<?php

namespace centigen\i18ncontent\controllers;

use centigen\i18ncontent\models\search\WidgetTextSearch;
use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\WidgetText;
use centigen\i18ncontent\models\WidgetTextLanguages;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WidgetTextController implements the CRUD actions for WidgetText model.
 */
class WidgetTextController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            \centigen\base\behaviors\LayoutBehavior::className()
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'deleteRoute' => 'upload-delete'
            ],
            'upload-delete' => [
                'class' => 'trntv\filekit\actions\DeleteAction'
            ],
            'upload-imperavi' => [
                'class' => 'trntv\filekit\actions\UploadAction',
                'fileparam' => 'file',
                'responseUrlParam'=> 'filelink',
                'multiple' => false,
                'disableCsrf' => true
            ]
        ];
    }

    /**
     * Lists all WidgetText models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WidgetTextSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new WidgetText model.
     *
     * @author zura
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $widgetText = $request->post('WidgetText');
            $languages = $request->post('WidgetTextLanguages');

            $param = [
                'key' => $widgetText['key'],
                'status' => $widgetText['status'],
                'translations' => []
            ];


            $titles = $languages['title'];
            $descriptions = $languages['body'];

            foreach ($locales as $loc => $locale) {
                $title = $titles[$loc];
                $description = $descriptions[$loc];
//                \ChromePhp::log($title, $description);
                $param['translations'][] = [
                    'title' => $title,
                    'body' => $description,
                    'locale' => $loc,
                ];
            }
//            \ChromePhp::log($param);
//            return;
            if (WidgetText::saveWidget($param)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderCreateForm();
            }

        } else {
            return $this->renderCreateForm();
        }
    }

    /**
     * Updates an existing WidgetText model.
     *
     * @author zura
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $widgetText = $this->findModel($id);
        $translations = $widgetText->translations;

        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $text = $request->post('WidgetText');
            $languages = $request->post('WidgetTextLanguages');

            $param = [
                'key' => $text['key'],
                'status' => $text['status'],
                'translations' => [],
            ];
            $titles = $languages['title'];
            $descriptions = $languages['body'];
//            \ChromePhp::log($request->post());

//            return;

            foreach ($locales as $loc => $locale) {
                $title = $titles[$loc];
                $description = $descriptions[$loc];
//                \ChromePhp::log($title, $description);
                $param['translations'][$loc] = [
                    'title' => $title,
                    'body' => $description
                ];
            }
//            \ChromePhp::log($param);
//            return;
            if (WidgetText::updateWidget($widgetText, $translations, $param)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderUpdateForm($widgetText, $translations);
            }

        } else {
            return $this->renderUpdateForm($widgetText, $translations);
        }
    }

    /**
     * Deletes an existing WidgetText model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WidgetText model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WidgetText the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WidgetText::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Render create form
     *
     * @author zura
     * @return string
     */
    protected function renderCreateForm()
    {
        return $this->render('create', [
            'model' => new WidgetText(),
            'locales' => BaseHelper::getAvailableLocales()
        ]);
    }

    /**
     * Render update form with information to update
     *
     * @author zura
     * @param WidgetText $widgetText
     * @param WidgetTextLanguages[] $translations
     * @return string
     */
    protected function renderUpdateForm(WidgetText $widgetText, $translations)
    {
        return $this->render('update', [
            'model' => $widgetText,
            'translations' => $translations,
            'locales' => BaseHelper::getAvailableLocales()
        ]);
    }

}
