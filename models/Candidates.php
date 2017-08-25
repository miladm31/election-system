<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "candidates".
 *
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property integer $election_id
 * @property integer $rate
 */
class Candidates extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'candidates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['election_id', 'rate'], 'integer'],
            [['first_name', 'last_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'election_id' => 'Election ID',
            'rate' => 'Rate',
        ];
    }
    

    public function getCandidates($id)
    {
      return static::find()->where(['election_id' => $id])->one();
    }

    public function rateUp($id)
    {
      $candid=static::find()->where(['id' => $id])->one();
      $candid->rate += 1;
      $candid->save();
    }
}
