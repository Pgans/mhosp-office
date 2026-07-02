<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Jobservice]].
 *
 * @see Jobservice
 */
class JobserviceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Jobservice[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Jobservice|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
