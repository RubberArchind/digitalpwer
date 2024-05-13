<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[TrxMap]].
 *
 * @see TrxMap
 */
class TrxMapQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return TrxMap[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return TrxMap|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
