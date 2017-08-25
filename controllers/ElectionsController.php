<?php

namespace app\controllers;

use Yii;
use app\models\Users;
use app\models\Elections;
use app\models\ElectionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ElectionsController implements the CRUD actions for Elections model.
 */
class ElectionsController extends Controller
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
     * Lists all Elections models.
     * @return mixed
     */
    public function actionIndex()
    {
      if(!Yii::$app->user->isGuest){

        $id=Yii::$app->user->getId();
        $user = Users::findIdentity($id);
        $isAdmin = $user->Admin;
        if($isAdmin)
        {
          $model = new Elections();
          $searchModel = new ElectionsSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

          return $this->render('index', [
              'model' => $model,
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
          ]);
        }
      }
      throw new \yii\web\HttpException(401, 'You do not have premission');
    }

    /**
     * Displays a single Elections model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Elections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Elections();

        if ($model->load(Yii::$app->request->post())) {

            if($model->notValid($model->name)){
              return $this->redirect(['/site/exist', 'id' => $model->name]);
            }else{
              $model->save();
              return $this->redirect(['/elections', 'id' => $model->id]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Elections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Elections model.
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
     * Finds the Elections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Elections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Elections::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
