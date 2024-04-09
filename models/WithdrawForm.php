<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class WithdrawForm extends Model
{
    public $amount;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // amount is required and should be a string consisting of only digits
            [['amount'], 'required'],
        ];
    }



    public function deposit()
    {
        return false;
    }
}
