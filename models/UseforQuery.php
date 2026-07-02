<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Usefor]].
 *
 * @see Usefor
 */
class UseforQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Usefor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Usefor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
