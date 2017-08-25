<?php

namespace app\models;
use yii\web\IdentityInterface;
use \yii\db\ActiveRecord;


use Yii;

/**
 * This is the model class for table "users".
 *
 * @property integer $id
 * @property string $firstname
 * @property string $lastname
 * @property string $username
 * @property string $password
 * @property string $authKey
 * @property boolean $Admin
 * @property integer $Phone_Number
 *
 * @property Votes[] $votes
 */
class Users extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Admin'], 'boolean'],
            [['Phone_Number'], 'integer'],
            [['firstname', 'lastname', 'username', 'password'], 'string', 'max' => 255],
            [['Phone_Number'] ,'required'],
            [['authKey'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'firstname' => 'Firstname',
            'lastname' => 'Lastname',
            'username' => 'Username',
            'password' => 'Password',
            'authKey' => 'Auth Key',
            'Admin' => 'Admin',
            'Phone_Number' => 'Phone Number',
        ];
    }

    public function creatAdmin($firstname , $lastname , $username , $password)
    {
      $this->firstname =$firstname;
      $this->lastname =$lastname;
      $this->username =$username;
      $this->password =Yii::$app->getSecurity()->generatePasswordHash($password);
      $this->Admin = true;

      $this->Phone_Number = 0;

      if($this->save())
      {
        echo 'Done';
      }else {
        var_dump($user->getErrors());
        die();
      }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Votes::className(), ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        // TODO: Implement validateAuthKey() method.
    }

    public function findByUsername($username)
    {
      $user = Users::find()->where(['username' => $username])->one();
      return $user;
    }

    public function validatePassword($password)
    {
      $hash = $this->password;

      if (Yii::$app->getSecurity()->validatePassword($password, $hash)) {
        return true;
      } else {
        return false;
      }
    }

    public function setPhone($phone)
    {
      $this->Phone_Number = $phone;
      $this->save();
    }

    public function notSet($phone)
    {
      return !(static::find()->where(['Phone_Number' => $phone])->one());
    }



}
