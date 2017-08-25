<?php

use yii\base\view;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */




$this->title = 'Candidates';
$this->params['breadcrumbs'][] = $this->title;


$form = ActiveForm::begin([
    'id' => 'vote-form',
    'action' => ['candidates/verify'],
    'method' => 'post',
]);
?>

<div class="row">
  <div class="col-md-4">

  </div>
  <div class="col-md-4">
    <?= $form->field($user, 'Phone_Number')->textInput()->hint('Please enter your phone number') ?>
  </div>
  <div class="col-md-4">

  </div>

</div>

<div class="candidates-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'first_name',
            'last_name',
            'rate',

            ['class' => 'yii\grid\RadioButtonColumn',
            'radioOptions' => function ($model) {
                   return [
                       'value' => $model->id,
                       'checked' => ''
                   ];
               }
            ],
        ],
    ]); ?>


<div class="form-group">
    <div class="col-lg-offset-1 col-lg-11">
        <?= Html::submitButton('vote', ['class' => 'btn btn-primary']) ?>
    </div>
</div>
<?php  ActiveForm::end(); ?>
</div>
