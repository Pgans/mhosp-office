<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[DepartmentJob]].
 *
 * @see DepartmentJob
 */
class DepartmentJobQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return DepartmentJob[]|array
     */
    public function all($db = null)
    {
        return parent::all($db7);
    }

    /**
     * {@inheritdoc}
     * @return DepartmentJob|array|null
     */
    public function one($db = null)
    {
        return parent::one($db7);
    }
}
