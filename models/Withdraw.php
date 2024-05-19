<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "withdraw".
 *
 * @property string $id
 * @property int $user_id
 * @property float $amount
 * @property float $amount_plus_fee
 * @property string $source
 * @property string $time
 */
class Withdraw extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'withdraw';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'amount', 'amount_plus_fee', 'source'], 'required'],
            [['user_id'], 'string'],
            [['amount','amount_plus_fee'], 'number'],
            [['time'], 'safe'],
            [['id'], 'string', 'max' => 255],
            [['source'], 'string', 'max' => 200],
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
            'amount' => Yii::t('app', 'Amount'),
            'source' => Yii::t('app', 'Source'),
            'time' => Yii::t('app', 'Time'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return WithdrawQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new WithdrawQuery(get_called_class());
    }
}
