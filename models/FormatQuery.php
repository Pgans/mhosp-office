<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Format]].
 *
 * @see Format
 */
class FormatQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Format[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Format|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
