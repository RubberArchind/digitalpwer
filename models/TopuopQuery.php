<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Topuop]].
 *
 * @see Topuop
 */
class TopuopQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Topuop[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Topuop|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
