<?php

namespace app\controllers;

use Yii;
use app\models\Verification;
use app\models\Users;
use app\models\Votes;
use app\models\Elections;
use yii\helpers\Url;
use app\models\ActiveElection;
use app\models\Candidates;
use app\models\CandidatesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CandidatesController implements the CRUD actions for Candidates model.
 */
class CandidatesController extends Controller
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
     * Lists all Candidates models.
     * @return mixed
     */
    public function actionIndex($id=null)
    {
      if($this->isAuthorize()){
        if($id == null){
            $election = new Elections;
            $election =$election->find()->where(['active' => true])->one();
            if($election){
              $id=$election->id;
            }
          }
          if(!$id){
            throw new \yii\web\HttpException(503, 'There is no election selected');
          }
          $searchModel = new CandidatesSearch();
          $dataProvider = $searchModel->search(array('r' => 'candidates' , 'id' => $id));
          return $this->render('index', [
              'election_id' => $id,
              'searchModel' => $searchModel,
              'dataProvider' => $dataProvider,
          ]);
      }else {
          throw new \yii\web\HttpException(401, 'You do not have premission');
      }
    }

    /**
     * Displays a single Candidates model.
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
     * Creates a new Candidates model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
      if($this->isAuthorize()){

        $model = new Candidates();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $model->election_id=$id;
            $model->rate=0;
            $model->save();

            return $this->redirect(['/candidates']);
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
     * Updates an existing Candidates model.
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
     * Deletes an existing Candidates model.
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
     * Finds the Candidates model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Candidates the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Candidates::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionVote()
    {

      $election = new Elections();
      $election_id = $election->getActive();
      if($election_id ==null)
      {
        throw new \yii\web\HttpException(503, 'There is no election selected');
      }

      $user=new Users();

        $searchModel = new CandidatesSearch();
        $dataProvider = $searchModel->search(array('r' => 'candidates' , 'id' => $election_id));

        return $this->render('vote', [
          'user' => $user,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }


    public function actionVerify()
    {

          $request = Yii::$app->request;

          $user_id = $request->post('Users');
          $user_id= $user_id['Phone_Number'];
          if($user_id == null){
            throw new \yii\web\HttpException(401, 'You do not have premission');
          }

          $candid_id = $request->post('radioButtonSelection');

          $election_id = Elections::getActive();

          $vote = new Votes();
          if($vote-> notValid($election_id , $user_id))
          {
            throw new \yii\web\HttpException(401, 'You already vote');
          }

          $verification = new Verification();
          if(!$verification->votExist($user_id ,$candid_id , $election_id))
          {
            $verification= $verification->newVerify($user_id ,$candid_id , $election_id);
            $verify_code = $verification->verify_code;
            $error = $verification->sendCode($verify_code,$user_id);

          }else{
            $verification=$verification->votExist($user_id ,$candid_id , $election_id);
            $verify_code = $verification->verify_code;
            $error = $verification->sendCode($verify_code,$user_id);

          }

          if ($error)
          {
            return $this->render('notvalid',[
              'error' => $error,
            ]);
          }
          $model = new Verification();
          return $this->render('verify',[
            'user_id' => $user_id,
            'candid_id' => $candid_id,
            'verification' => $model,
            'verify_code' => $verify_code,
          ]);


    }

    public function actionValidate()
    {

        $request = Yii::$app->request;

        $input_code = $request->post('Verification');
        $input_code = $input_code['verify_code'];

        $user_id = $request->post('user_id');
        if($user_id == null){
          throw new \yii\web\HttpException(401, 'You do not have premission');
        }

        $candid_id = $request->post('candid_id');

        $election_id = Elections::getActive();

        $verification= new Verification();
        $verification = $verification->votExist($user_id ,$candid_id , $election_id);

        $verify_code = $verification->verify_code;
        $model = new Verification();
        $errors=$model->ValidateCode($verify_code , $input_code);

        if(!$errors)
        {
          $vote = new Votes();
          $candid = new Candidates();
          $candid->rateUp($candid_id);
          $user = new Users();
          if($user->notSet($user_id))
          {
            $user->setPhone($user_id);
          }

          $vote->setVote($user_id , $election_id);
          if($vote->setVote($user_id , $election_id))
          {
            return Yii::$app->response->redirect(Url::to(['/site/thanks']));
          }
        }else{

          return $this->render('verify',[
            'user_id' => $user_id,
            'candid_id' => $candid_id,
            'verification' => $model,
            'errors' => $model->getErrors(),

          ]);
        }


    }


}
