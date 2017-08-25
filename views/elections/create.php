<?php

use yii\widgets\Pjax;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Elections */

$this->title = 'Create Elections';
$this->params['breadcrumbs'][] = ['label' => 'Elections', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="elections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ])?>

</div>
