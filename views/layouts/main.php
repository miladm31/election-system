<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\models\Users;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);

if(!Yii::$app->user->isGuest){
  $id=Yii::$app->user->getId();
  $user = Users::findIdentity($id);
  $isAdmin = $user->Admin;
}
?>



<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin(
        [
        'brandLabel' => '',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
      ]
    );
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-left'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'Vote', 'url' => ['/candidates/vote']],


            Yii::$app->user->isGuest ?
                (
                    ['label' => 'Login', 'url' => ['/site/login']]
                )
                :
                (
                  $isAdmin ?
                  (
                      '<li>'. Html::beginForm(['/elections'], 'get')
                      . Html::submitButton(
                          'Elections ',
                          ['class' => 'btn btn-link logout']
                      )
                      . Html::endForm().'</li>'.


                      '<li>'. Html::beginForm(['/candidates'], 'get')
                      . Html::submitButton(
                          'Edite candidates ',
                          ['class' => 'btn btn-link logout']
                      )
                      . Html::endForm().'</li>'.


                      '<li>'. Html::beginForm(['/votes'], 'get')
                      . Html::submitButton(
                          'Vote List ',
                          ['class' => 'btn btn-link logout']
                      )
                      . Html::endForm().'</li>'


                      .'<li>'. Html::beginForm(['/site/logout'], 'post')
                      . Html::submitButton(
                          'Logout (' . Yii::$app->user->identity->username . ')',
                          ['class' => 'btn btn-link logout']
                      )
                      . Html::endForm().'</li>'
                  )
                  :
                  (
                      '<li>'. Html::beginForm(['/candidates/vote'], 'get')
                      . Html::submitButton(
                          'Vote (' . Yii::$app->user->identity->username . ')',
                          ['class' => 'btn btn-link logout']
                      )
                      . Html::endForm().'</li>'
                      .'<li>'. Html::beginForm(['/site/logout'], 'post')
                      . Html::submitButton(
                          'Logout (' . Yii::$app->user->identity->username . ')',
                          ['class' => 'btn btn-link logout']
                      )
                      . Html::endForm().'</li>'
                  )

                )
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
