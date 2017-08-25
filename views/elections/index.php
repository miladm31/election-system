<?php

use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;
use app\controllers\CandidatesController;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ElectionsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Elections';
$this->params['breadcrumbs'][] = $this->title;

$form = ActiveForm::begin([
    'id' => 'login-form',
    'options' => ['class' => 'form-horizontal'],
]);
?>

<div class="elections-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Elections', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php
        Pjax::begin([]);
        echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',

            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\RadioButtonColumn',
            'radioOptions' => function ($model) {
                   return [
                       'value' => $model->id,
                       'checked' => ''
                   ];
               }
            ],
        ],
        ]);
        Pjax::end();
    ?>



    <div class="form-group">
        <div class="col-lg-offset-1 col-lg-11">
            <?= Html::submitButton('Select', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>
<?php ActiveForm::end();?>
</div>



<?php if(isset($_POST['radioButtonSelection'])){
        $id=$_POST['radioButtonSelection'];
        $all = $model->find()->all();
        $count = count($all);
        $i=0;
        while($i<$count)
        {
          $all[$i]['active']=false;
          $all[$i]->save();
          $i++;
        }
        $election = $model->find()->where(['id' => $id])->one();;
        $election->active = true;
        $election->save();


        return Yii::$app->response->redirect(Url::to(['/candidates/index', 'id' => $election->id]));
      }
?>
