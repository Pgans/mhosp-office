<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[LogThaimed]].
 *
 * @see LogThaimed
 */
class LogThaimedQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return LogThaimed[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return LogThaimed|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
