<?php

namespace app\models;
require  __DIR__.'/../vendor/autoload.php';

use Yii;
use app\controllers\CandidatesController;
use Kavenegar\Exceptions\ApiException;

/**
 * This is the model class for table "verification".
 *
 * @property integer $id
 * @property integer $verify_code
 * @property integer $user_id
 * @property integer $candid_id
 * @property integer $election_id
 */
class Verification extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'verification';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['verify_code', 'user_id', 'candid_id','election_id'], 'integer' ,'message'=> 'verify code must be a number'],
            [['verify_code'] ,'required'],
        ];
    }


    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'verify_code' => 'Verify Code',
            'user_id' => 'User ID',
            'candid_id' => 'Candid ID',
            'election_id' => 'Election ID',
        ];
    }

    public function ValidateCode($verify_code , $input_code)
    {
      if(!($verify_code == $input_code))
      {

        $errors = 'Wrong verify Code';
        $this->addError('verify_code',$errors);
        return $errors;
      }else
      {
        return;
      }
    }


    public function newVerify($user_id ,$candid_id , $election_id)
    {
      if(!$this->find()->where(['user_id' => $user_id , 'candid_id' =>$candid_id])->one())
      {
        $verify_code = mt_rand(10000, 99999);
        $this->verify_code = $verify_code;
        $this->user_id =$user_id;
        $this->candid_id = $candid_id;
        $this->election_id = $election_id;
        $result= $this->save();
        if(!$result)
        {
          print_r($this->getErrors());
          die();
        }
        return $this;
      }else{
        return false;
      }

    }

    public function sendCode($verify_code,$user_id)
    {
      try{
        $sender = "100065995";
        $receptor = (string)$user_id;
        $message = "your verify code is :".$verify_code;
        $api = new \Kavenegar\KavenegarApi("326D5A634D79327179453445566C4874773435356F413D3D");
        $api->Send($sender,$receptor,$message);
      }
      catch(ApiException $ex)
      {
        $error = $ex->errorMessage();
        $error = explode(":" , $error);
      	return $error[1];
      }
      catch(HttpException $ex)
      {
      	echo $e->errorMessage();
      }
    }


    public function votExist($user_id ,$candid_id , $election_id)
    {
      $result = static::find()->where(['user_id'=>$user_id,
                                       'candid_id' => $candid_id,
                                       'election_id'=>$election_id,
                                       ])->one();
      return $result;
    }
}
