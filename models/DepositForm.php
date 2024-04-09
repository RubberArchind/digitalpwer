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
class DepositForm extends Model
{
    public $amount;    

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['amount'], 'required'],
            ['amount', 'match', 'pattern' => '/^[0-9]+\+?$/', 'message' => "{attribute} should contain only numbers"]
        ];
    }
   

    public function deposit()
    {
        return false;
    }
}
