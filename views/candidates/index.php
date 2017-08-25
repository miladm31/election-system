<?php
// use Yii;
use app\models\ActiveElection;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */




$this->title = 'Candidates';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="candidates-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Candidates', ['create', 'id'=> $election_id], ['class' => 'btn btn-success'])?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'first_name',
            'last_name',
            'election_id',
            'rate',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
