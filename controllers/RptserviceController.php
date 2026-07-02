<?php

namespace app\controllers;

class RptserviceController extends \yii\web\Controller
{
    public function actionIndex()
    {
		$sql = "
			SELECT k.fiscal,
			 COUNT(CASE WHEN (k.type_id = 2) THEN 1 END ) as '2',
			COUNT(CASE WHEN (k.type_id = 4) THEN 2 END ) as '4' ,
			COUNT(CASE WHEN (k.type_id = 5) THEN 3 END ) as '5' ,
			COUNT(CASE WHEN (k.type_id = 6) THEN 4 END ) as  '6' ,
			COUNT(CASE WHEN (k.type_id = 7) THEN 5 END ) as '7' ,
			COUNT(CASE WHEN (k.type_id = 8) THEN 6 END ) as '8' ,
			COUNT(CASE WHEN (k.type_id = 9) THEN 7 END ) as '9' ,
			COUNT(CASE WHEN (k.type_id = 10) THEN 8 END ) as '10' ,
			COUNT(CASE WHEN (k.type_id = 11) THEN 9 END ) as '11' ,
			COUNT(CASE WHEN (k.type_id = 12) THEN 10 END ) as '12' ,
			COUNT(CASE WHEN ISNULL(k.type_id) THEN 11 ELSE NULL END) AS 'xx'
			FROM (
			SELECT 
			 j.detail, j.dateline, j.send_by, j.send_at, j.repair_by, j.repair_at, j.repair_service, j.repair_cost,
			 j.device_id,
			 j.type_id, js.type,
			 j.dep_id,
			IF(MONTH(j.dateline)>9, YEAR(j.dateline)+544, YEAR(j.dateline)+543) AS fiscal
			FROM jobcom j
			LEFT JOIN jobtype_service js ON js.id = j.type_id 
			WHERE j.dateline BETWEEN '2018-10-01' AND NOW()
			) as k
			GROUP BY k.fiscal
			ORDER BY k.fiscal DESC

			";

     $rawData1 = \yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $rawData1,
            'pagination' => FALSE,
            'pagination' => ['pagesize' => 10],
        ]);
		
        //return $this->render('index');
		  return $this->render('index', [
                    'dataProvider' => $dataProvider,
					//'nowProvider' => $nowProvider,
                    'sql'=>$sql,
                   

        ]);
    
    }

}
