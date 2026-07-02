<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Jobcom]].
 *
 * @see Jobcom
 */
class JobcomQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Jobcom[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Jobcom|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
