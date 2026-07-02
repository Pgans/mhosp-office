<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LogA15er]].
 *
 * @see LogA15er
 */
class LogA15erQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LogA15er[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LogA15er|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
