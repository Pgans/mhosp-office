<?php
namespace app\models;

use yii\base\Model;
use Yii;

class ProcessModel extends Model
{
    public function getProcessList()
    {
        try {
            $connection = Yii::$app->db2;
            $command = $connection->createCommand('SHOW PROCESSLIST');
            return $command->queryAll();
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'ไม่สามารถดึงรายการ Process ได้: ' . $e->getMessage());
            return [];
        }
    }

    public function killProcess($processId)
    {
        try {
            $connection = Yii::$app->db2;
            $command = $connection->createCommand('KILL ' . (int)$processId);
            $command->execute();
            return true;
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'ไม่สามารถ Kill Process ได้: ' . $e->getMessage());
            return false;
        }
    }
}