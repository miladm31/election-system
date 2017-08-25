<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Votes */

$this->title = 'Create Votes';
$this->params['breadcrumbs'][] = ['label' => 'Votes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="votes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
