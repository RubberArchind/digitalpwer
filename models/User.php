<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $user_id
 * @property string $username
 * @property string $auth_key
 * @property string|null $user_referral
 * @property string|null $referral
 * @property string $full_name
 * @property string $email
 * @property string $password
 * @property string $phone
 * @property string $bank_code
 * @property string $bank_number
 * @property float $balance_deposit
 * @property float $balance_bonus
 * @property float $balance_cashback
 * @property string $signup_time 
 *
 * @property Transaction[] $transactions
 */
class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'username', 'auth_key', 'full_name', 'email', 'password', 'phone', 'bank_code', 'bank_number', 'balance_deposit', 'balance_bonus', 'balance_cashback'], 'required'],
            [['balance_deposit', 'balance_bonus', 'balance_cashback'], 'number'],
            ['email','email'],
            ['username', 'compare', 'compareAttribute' => 'email'],
            [['signup_time'], 'safe'],
            [['user_id', 'username', 'full_name', 'email', 'password'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['user_referral', 'referral'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 12],
            [['bank_code'], 'string', 'max' => 5],
            [['bank_number'], 'string', 'max' => 25],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'username' => Yii::t('app', 'Username'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'user_referral' => Yii::t('app', 'User Referral'),
            'referral' => Yii::t('app', 'Referral'),
            'full_name' => Yii::t('app', 'Full Name'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'phone' => Yii::t('app', 'Phone'),
            'bank_code' => Yii::t('app', 'Bank Code'),
            'bank_number' => Yii::t('app', 'Bank Number'),
            'balance_deposit' => Yii::t('app', 'Balance Deposit'),
            'balance_bonus' => Yii::t('app', 'Balance Bonus'),
            'balance_cashback' => Yii::t('app', 'Balance Cashback'),
            'signup_time' => Yii::t('app', 'Signup Time'), 
        ];
    }

    // /**
    //  * Gets query for [[Transactions]].
    //  *
    //  * @return \yii\db\ActiveQuery|TransactionQuery
    //  */
    // public function getTransactions()
    // {
    //     return $this->hasMany(Transaction::class, ['user_id' => 'user_id']);
    // }

    /**
     * {@inheritdoc}
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface|null the identity object that matches the given ID.
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param string $token the token to be looked for
     * @return IdentityInterface|null the identity object that matches the given token.
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['access_token' => $token]);
    }

    /**
     * @return int|string current user ID
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string|null current user auth key
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool|null if auth key is valid for current user
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this->auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }
    
    public static function findByEmail($email){
        $findUser = User::findOne(['email'=>$email]);
        return new static($findUser);
    }

    public function validatePassword($password){
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);  
    }
}
