<?php


use yii\helpers\Html;
use yii\web\NotFoundHttpException;
use yii\helpers\Url;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Candidates */

$this->title = 'Create Candidates';
$this->params['breadcrumbs'][] = ['label' => 'Candidates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="candidates-create">

  <div class="candidates-form">

      <?php $form = ActiveForm::begin([
    'action' => ['candidates/validate'],
    'method' => 'post',
    ]); ?>
<br><br><br>
      <div class="row">
        <div class="col-md-4">

        </div>
        <div class="col-md-4">
          <?= $form->field($verification, 'verify_code')->textInput(['maxlength' => true]) ?>
          <?=  Html::hiddenInput('user_id', $user_id); ?>
          <?=  Html::hiddenInput('candid_id', $candid_id); ?>

          <div class="form-group">
            <?= Html::submitButton('Verify', ['class' => 'btn btn-primary']) ?>
          </div>
        </div>
        <div class="col-md-4">

        </div>

      </div>



      <?php ActiveForm::end(); ?>

  </div>

</div>
