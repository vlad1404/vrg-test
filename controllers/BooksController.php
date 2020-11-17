<?php

namespace app\controllers;

use app\controllers\base\BaseController;
use app\models\Authors;
use app\models\BooksAuthors;
use app\models\BookUploadForm;
use Yii;
use app\models\Books;
use app\models\search\BooksSearch;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * BooksController implements the CRUD actions for Books model.
 */
class BooksController extends BaseController
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Books models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BooksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize = 15;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Books model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionSaveData($id = null)
    {
        $post = Yii::$app->request->post();
        if(empty($post))
            throw new HttpException(404);
        $model = Books::findOne($id) ?? new Books();
        if ($model->load($post)) {

            $imgLogo = new BookUploadForm();
            $imgLogo->src = UploadedFile::getInstance($model, 'photo');
            $upload = $imgLogo->upload();

            if ($upload !== false)
                $model->photo = $upload;

            if($model->save()) {
                $old = $model->booksAuthors;
                $ids = array_column($model->booksAuthors, 'author_id');
                foreach ($post['authors'] as $author) {
                    $bookAuthors = BooksAuthors::find()->where(['and', ['book_id' => $model->id], ['author_id' => $author]])->one();
                    if($bookAuthors === null) {
                        $model->link('authors', Authors::findOne($author));
                    } else {
                        unset($old[array_search($bookAuthors->author_id, $ids)]);
                    }
                }
                foreach ($old as $item) {
                    $bookAuthorDelete = BooksAuthors::findOne(['id' => $item['id']]);
                    if ($bookAuthorDelete === null) {
                        continue;
                    }
                    $bookAuthorDelete->delete();
                }
            }

            return true;
        }
        return false;
    }

    /**
     * Creates a new Books model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if(!\Yii::$app->request->isAjax)
            throw new HttpException(404);

        $model = new Books();

        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Books model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        if(!\Yii::$app->request->isAjax)
            throw new HttpException(404);

        $model = $this->findModel($id);

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Books model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Books model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Books the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Books::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
