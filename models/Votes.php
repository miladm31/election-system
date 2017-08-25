<?php

namespace app\models;


use app\models\Elections;
use Yii;

/**
 * This is the model class for table "votes".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $election_id
 * @property string $vote_at
 * @property string $election_name
 *
 * @property Elections $election
 * @property Elections $electionName
 * @property Users $user
 */
class Votes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'votes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'election_id'], 'integer'],
            [['vote_at'], 'safe'],
            [['election_name'], 'string'],
            [['election_id'], 'exist', 'skipOnError' => true, 'targetClass' => Elections::className(), 'targetAttribute' => ['election_id' => 'id']],
            [['election_name'], 'exist', 'skipOnError' => true, 'targetClass' => Elections::className(), 'targetAttribute' => ['election_name' => 'name']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'Phone_Number']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'election_id' => 'Election ID',
            'vote_at' => 'Vote At',
            'election_name' => 'Election Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElection()
    {
        return $this->hasOne(Elections::className(), ['id' => 'election_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getElectionName()
    {
        return $this->hasOne(Elections::className(), ['name' => 'election_name']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Users::className(), ['Phone_Number' => 'user_id']);
    }

    public function notValid($election_id , $user_phone)
    {

      if ($user_phone[0] == 0){
        $user_phone = ltrim($user_phone, '0');
      }
      return (static::find()->where(['election_id' =>$election_id , 'user_id' => $user_phone ])->one());
    }

    public function setVote($user_id , $election_id)
    {
      $this->user_id = $user_id;
      $this->election_id = $election_id;
      $election = new Elections();
      $election = $election->find()->where(['id' => $election_id])->one();
      $election_name = $election->name;
      $this->election_name = $election->name;
      $this->vote_at = new \yii\db\Expression('NOW()');
      $this->save();
      if(!$this->save())
      {
        print_r($this->getErrors());
        die();
      }

      return $this;
    }
}
