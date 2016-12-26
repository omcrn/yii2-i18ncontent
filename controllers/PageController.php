<?php

namespace centigen\i18ncontent\controllers;

use centigen\i18ncontent\AssetBundle;
use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\Page;
use centigen\i18ncontent\models\PageTranslations;
use centigen\i18ncontent\models\search\PageSearch;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PageController implements the CRUD actions for Page model.
 */
class PageController extends Controller
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
     * Lists all Page models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

//        \ChromePhp::log($dataProvider->sort->attributes);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Page model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $model = $request->post('Page');
            $translations = $request->post('PageTranslations');

//            \ChromePhp::log($model, $translations);
            if (Page::processAndSave($model, $translations, $locales)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderCreateForm();
            }

        } else {
            return $this->renderCreateForm();
        }

    }

    /**
     * Updates an existing Page model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $translations = $model->translations;

        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $modelData = $request->post('Page');
            $languages = $request->post('PageTranslations');

//            \ChromePhp::log($model);
            if (Page::processAndUpdate($model, $translations, $modelData, $languages, $locales)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderUpdateForm($model, $translations);
            }

        } else {
            return $this->renderUpdateForm($model, $translations);
        }
    }

    /**
     * Deletes an existing Page model.
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
     * Finds the Page model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Page the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Page::findOne($id)) !== null) {
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
        AssetBundle::register($this->getView());
        return $this->render('create', [
            'model' => new Page(),
            'locales' => BaseHelper::getAvailableLocales()
        ]);
    }

    /**
     * Render update form with information to update
     *
     * @author zura
     * @param Page $model
     * @param PageTranslations[] $translations
     * @return string
     */
    protected function renderUpdateForm(Page $model, $translations)
    {
        return $this->render('update', [
            'model' => $model,
            'translations' => $translations,
            'locales' => BaseHelper::getAvailableLocales()
        ]);
    }

}
