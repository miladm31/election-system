<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "elections".
 *
 * @property integer $id
 * @property string $name

 *
 * @property Votes[] $votes
 */
class Elections extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'elections';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['active'], 'boolean'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotes()
    {
        return $this->hasMany(Votes::className(), ['election_id' => 'id']);
    }

    public static function getActive()
    {
      $active = static::find()->where(['active' => true])->one();
      if($active)
      {
        return $active->id;
      }else {
        return null;
      }
    }

    public function notValid($name)
    {
      return (static::find()->where(['name' => $name])->one());
    }

}
