<?php
namespace app\commands;


use Yii;
use yii\console\Controller;
use app\models\Users;

/**
 *
 */
class UserController extends Controller
{

  public function actionCreat($firstname , $lastname , $username , $password)
  {
    $user = new Users();
    $user->creatAdmin($firstname , $lastname , $username , $password);

  }

}
