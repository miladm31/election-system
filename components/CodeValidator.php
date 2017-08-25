<?php
namespace app\components;

use yii\validators\Validator;

class CodeValidator extends Validator
{
    public function validateAttribute($model, $attribute,$value)
    {
        if (!($model->$attribute==$value)) {
            $this->addError($model, $attribute, 'The code must be either equal to inout.');
        }
    }
}
