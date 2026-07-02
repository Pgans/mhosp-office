<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Rental]].
 *
 * @see Rental
 */
class RentalQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Rental[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Rental|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
