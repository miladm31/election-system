<?php

namespace app\controllers;

use Yii;
use app\models\Votes;
use app\models\VotesSearch;
use app\models\Users;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VotesController implements the CRUD actions for Votes model.
 */
class VotesController extends Controller
{
    /**
     * @inheritdoc
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

    public function isAuthorize()
    {
      if(!Yii::$app->user->isGuest){
        $id=Yii::$app->user->getId();
        $user = Users::findIdentity($id);
        $isAdmin = $user->Admin;
        return $isAdmin;
      }
    }

    /**
     * Lists all Votes models.
     * @return mixed
     */
    public function actionIndex()
    {

        if($this->isAuthorize()){

          $searchModel = new VotesSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

          return $this->render('index', [
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
          ]);

        }else {
          throw new \yii\web\HttpException(401, 'You do not have premission');
        }
    }


    /**
     * Displays a single Votes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
      if($this->isAuthorize()){
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
      }else {
        throw new \yii\web\HttpException(401, 'You do not have premission');
      }
    }

    /**
     * Creates a new Votes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
      if($this->isAuthorize()){
        $model = new Votes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
      }else {
        throw new \yii\web\HttpException(401, 'You do not have premission');
      }
    }

    /**
     * Updates an existing Votes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
      if($this->isAuthorize()){
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
      }else {
        throw new \yii\web\HttpException(401, 'You do not have premission');
      }
    }

    /**
     * Deletes an existing Votes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
      if($this->isAuthorize()){
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
      }else {
        throw new \yii\web\HttpException(401, 'You do not have premission');
      }
    }

    /**
     * Finds the Votes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Votes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Votes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
