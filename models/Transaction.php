<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transaction".
 *
 * @property string $id
 * @property string $user_id
 * @property string $target_id
 * @property string $method 
 * @property string $type
 * @property float $amount
 * @property string $time
 *
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'transaction';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'target_id', 'method', 'type', 'amount', 'time'], 'required'],
            [['type','time'], 'string'],
            [['amount'], 'number'],
            [['id', 'user_id', 'target_id'], 'string', 'max' => 255],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['create'] = ['id', 'user_id', 'target_id', 'method', 'type', 'amount', 'time'];
        return $scenarios;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'target_id' => Yii::t('app', 'Target ID'),
            'method' => Yii::t('app', 'Method'),
            'type' => Yii::t('app', 'Type'),
            'amount' => Yii::t('app', 'Amount'),
            'time' => Yii::t('app', 'Time'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return TransactionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TransactionQuery(get_called_class());
    }
}
