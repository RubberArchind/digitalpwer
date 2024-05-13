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
class TopupForm extends Model
{
    public $code;    
    public $pnumber;
    public $amount;
    public $snap;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [            
            [['code', 'pnumber', 'amount'], 'required'],            
            ['code', 'string'],
            ['pnumber', 'string'],
            ['amount', 'string'],
            ['snap', 'string']
        ];
    }
   

    public function deposit()
    {
        return false;
    }
}
