<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Drivers]].
 *
 * @see Drivers
 */
class DriversQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Drivers[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Drivers|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
