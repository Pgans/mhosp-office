<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Bookingstatus]].
 *
 * @see Bookingstatus
 */
class BookingStatusQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Bookingstatus[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Bookingstatus|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
