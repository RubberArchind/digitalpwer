<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "topuop".
 *
 * @property int $id
 * @property string $gambar
 * @property string $name
 * @property int $harga
 * @property string $last_update
 */
class Topuop extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topuop';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['gambar', 'name', 'harga'], 'required'],
            [['harga'], 'integer'],
            [['last_update'], 'safe'],
            [['gambar', 'name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'gambar' => Yii::t('app', 'Gambar'),
            'name' => Yii::t('app', 'Name'),
            'harga' => Yii::t('app', 'Harga'),
            'last_update' => Yii::t('app', 'Last Update'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TopuopQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TopuopQuery(get_called_class());
    }
}
