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
class FormSignup extends Model
{
    public $fullname;
    public $email;
    public $phone;
    public $password;
    public $confirm_password;
    public $bank_code;
    public $bank_number;
    public $referral;
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['fullname', 'email', 'phone', 'password', 'confirm_password', 'bank_code', 'bank_number'], 'required'],
            ['fullname', 'match', 'pattern' => '/^[A-Za-z\s]+$/u', 'message' => "{attribute} should contain only letters"],
            ['email', 'email'],
            ['phone', 'match', 'pattern' => '/^[0-9]+\+?$/', 'message' => "{attribute} should contain only numbers"],
            ['password', 'compare', 'compareAttribute' => 'confirm_password'],
            ['bank_code', 'match', 'pattern' => '/^[A-Za-z\s]+$/u', 'message' => "{attribute} should contain only letters"],
            ['bank_number', 'match', 'pattern' => '/^[0-9]+\+?$/', 'message' => "{attribute} should contain only numbers"],
            ['referral', 'match', 'pattern' => '/\p{Lu}/u', 'message' => '{attribute} should be in Uppercase']
        ];
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {

        if ($this->_user === false) {
            $this->_user = User::findOne(['username'=>$this->email]);            
        }

        return !$this->_user;
    }

    public function isNewUser()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
                
            if (!$user) {
                $this->_user = false;
                $this->addError('Email sudah didaftarkan.');
            }else{
                $this->_user = true;
            }

            return $this->_user;
        }
    }
}
