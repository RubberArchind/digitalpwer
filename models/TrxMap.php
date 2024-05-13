<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trx_map".
 *
 * @property string $id
 * @property string $user_id
 */
class TrxMap extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trx_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'required'],
            [['id', 'user_id'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * {@inheritdoc}
     * @return TrxMapQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TrxMapQuery(get_called_class());
    }
}
