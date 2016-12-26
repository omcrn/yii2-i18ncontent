<?php

namespace centigen\i18ncontent\controllers;

use centigen\i18ncontent\helpers\BaseHelper;
use centigen\i18ncontent\models\Article;
use centigen\i18ncontent\models\search\ArticleSearch;
use centigen\i18ncontent\models\ArticleCategory;
use centigen\i18ncontent\web\Controller;
use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
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
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $locales = BaseHelper::getAvailableLocales();
        if ($request->post()) {

            $model = $request->post('Article');
            $translations = $request->post('ArticleTranslations');

//            \ChromePhp::log($model, $translations);
            if (Article::processAndSave($model, $translations, $locales)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderCreateForm();
            }

        } else {
            return $this->renderCreateForm();
        }
    }

    /**
     * Updates an existing Article model.
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

            $modelData = $request->post('Article');
            $languages = $request->post('ArticleTranslations');

            if (Article::processAndUpdate($model, $translations, $modelData, $languages, $locales)) {
                return $this->redirect(['index']);
            } else {
                return $this->renderUpdateForm($model, $translations);
            }

        } else {
            return $this->renderUpdateForm($model, $translations);
        }

    }

    /**
     * Deletes an existing Article model.
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
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Article::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected  function renderCreateForm()
    {
        return $this->render('create', [
            'model' => new Article(),
            'locales' => BaseHelper::getAvailableLocales(),
            'categories' => ArticleCategory::getCategories(),
        ]);
    }


    protected function renderUpdateForm($model, $translations)
    {
        return $this->render('update', [
            'model' => $model,
            'translations' => $translations,
            'locales' => BaseHelper::getAvailableLocales(),
            'categories' => ArticleCategory::getCategories(),
        ]);
    }
}
