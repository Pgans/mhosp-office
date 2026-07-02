<?php

namespace app\controllers;
use yii;


class DashboardController extends \yii\web\Controller
{
	//public function actionIndex()
	// {
	//     return $this->render('index');
	
	
	public function actionDashboard(){

############################ SERVER  192.168.200.14 ###############################################
		
		$sql = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID)  as amount 
	FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
	UNION
	SELECT 'refers out' as tables, COUNT(r.VISIT_ID)  as amount 
	FROM refers r WHERE r.rf_dt >= CURDATE() AND r.is_cancel = 0 AND r.RF_TYPE = 2 AND left(r.hosp_id,3) not in ('036','037','998')
	UNION
	/*
	SELECT 'prescriptions' as tables, COUNT(ps.VISIT_ID)  as amount 
	FROM prescriptions ps WHERE ps.order_dt BETWEEN CURDATE() AND NOW() AND ps.is_cancel = 0
	*/
	SELECT 'refers in' as tables, COUNT(r1.VISIT_ID)  as amount 
	FROM refers r1 WHERE r1.rf_dt >= CURDATE() AND r1.is_cancel = 0 AND r1.RF_TYPE = 1
	
	UNION
	SELECT 'ipd' as tables, COUNT(i.VISIT_ID)  as amount 
	FROM ipd_reg i WHERE i.ADM_DT >= CURDATE() AND i.is_cancel = 0
	UNION
	SELECT 'Lab' as tables, COUNT(l.VISIT_ID)  as amount 
	FROM lab_requests l WHERE l.LREQ_DT >= CURDATE() AND l.is_cancel = 0
	UNION
	SELECT 'X-ray' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM xray_requests x WHERE x.XREQ_DATETIME >= CURDATE() AND x.is_cancel = 0
	UNION
	SELECT 'ipd_cont' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM ipd_cont x WHERE x.order_dt BETWEEN CURDATE() AND NOW() AND x.is_cancel = 0
	/*
	UNION
	SELECT 'opd_dx' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM opd_diagnosis x WHERE x.dx_dt BETWEEN CURDATE() AND NOW()
	*/
	UNION
	SELECT 'cost_visits' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM cost_visits x WHERE x.dt_timestamp BETWEEN CURDATE() AND NOW() 
	UNION
	SELECT 'visit_invoice' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM visit_invoice x WHERE x.record_dt BETWEEN CURDATE() AND NOW() 
	";
		$rawData = \yii::$app->db14->createCommand($sql)->queryAll();
		try {
			$rawData = \Yii::$app->db14->createCommand($sql)->queryAll();
		} catch (\yii\db2\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$data14Provider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawData,
		'pagination' => [
		'pageSize' => 10,
		],
		]);
############################ SERVER  192.168.200.70 ###############################################
		
		$sql1 = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID)  as amount 
	FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
	UNION
	SELECT 'refers out' as tables, COUNT(r.VISIT_ID)  as amount 
	FROM refers r WHERE r.rf_dt >= CURDATE() AND r.is_cancel = 0 AND r.RF_TYPE = 2 AND left(r.hosp_id,3) not in ('036','037','998')
	UNION
	SELECT 'refers in' as tables, COUNT(r1.VISIT_ID)  as amount 
	FROM refers r1 WHERE r1.rf_dt >= CURDATE() AND r1.is_cancel = 0 AND r1.RF_TYPE = 1
	UNION
	SELECT 'ipd' as tables, COUNT(i.VISIT_ID)  as amount 
	FROM ipd_reg i WHERE i.ADM_DT >= CURDATE() AND i.is_cancel = 0
	UNION
	SELECT 'Lab' as tables, COUNT(l.VISIT_ID)  as amount 
	FROM lab_requests l WHERE l.LREQ_DT >= CURDATE() AND l.is_cancel = 0
	UNION
	SELECT 'X-ray' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM xray_requests x WHERE x.XREQ_DATETIME >= CURDATE() AND x.is_cancel = 0
	UNION
	SELECT 'ipd_cont' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM ipd_cont x WHERE x.order_dt BETWEEN CURDATE() AND NOW() AND x.is_cancel = 0
	UNION
	SELECT 'cost_visits' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM cost_visits x WHERE x.dt_timestamp BETWEEN CURDATE() AND NOW()
	UNION
	SELECT 'visit_invoice' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM visit_invoice x WHERE x.record_dt BETWEEN CURDATE() AND NOW() 
	";
		$rawData70 = \yii::$app->db14->createCommand($sql1)->queryAll();
		try {
			$rawData = \Yii::$app->db14->createCommand($sql1)->queryAll();
		} catch (\yii\db70\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$data70Provider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawData70,
		'pagination' => [
		'pageSize' => 10,
		],
		]);
############################ SERVER  192.168.200.7 ###############################################
		
		$sql2 = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID)  as amount 
FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
UNION
SELECT 'refers out' as tables, COUNT(r.VISIT_ID)  as amount 
FROM refers r WHERE r.rf_dt >= CURDATE() AND r.is_cancel = 0 AND r.RF_TYPE = 2 AND left(r.hosp_id,3) not in ('036','037','998')
UNION
SELECT 'refers in' as tables, COUNT(r1.VISIT_ID)  as amount 
FROM refers r1 WHERE r1.rf_dt >= CURDATE() AND r1.is_cancel = 0 AND r1.RF_TYPE = 1
UNION
SELECT 'ipd' as tables, COUNT(i.VISIT_ID)  as amount 
FROM ipd_reg i WHERE i.ADM_DT >= CURDATE() AND i.is_cancel = 0
UNION
SELECT 'Lab' as tables, COUNT(l.VISIT_ID)  as amount 
FROM lab_requests l WHERE l.LREQ_DT >= CURDATE() AND l.is_cancel = 0
UNION
SELECT 'X-ray' as tables, COUNT(x.VISIT_ID)  as amount 
FROM xray_requests x WHERE x.XREQ_DATETIME >= CURDATE() AND x.is_cancel = 0
UNION
SELECT 'ipd_cont' as tables, COUNT(x.VISIT_ID)  as amount 
FROM ipd_cont x WHERE x.order_dt BETWEEN CURDATE() AND NOW() AND x.is_cancel = 0

UNION
	SELECT 'cost_visits' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM cost_visits x WHERE x.dt_timestamp BETWEEN CURDATE() AND NOW() 
	UNION
	SELECT 'visit_invoice' as tables, COUNT(x.VISIT_ID)  as amount 
	FROM visit_invoice x WHERE x.record_dt BETWEEN CURDATE() AND NOW() 
	";
		$rawData70 = \yii::$app->db7->createCommand($sql2)->queryAll();
		try {
			$rawData7 = \Yii::$app->db7->createCommand($sql2)->queryAll();
		} catch (\yii\db7\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$data7Provider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawData7,
		'pagination' => [
		'pageSize' => 10,
		],
		]);
############################ Monitor DHDC ###############################################
		
		$sqldhdc = "

			SELECT 
				table_name,
				table_rows,
				data_length + index_length AS total_size,
				ROUND((data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
					ROUND((data_length + index_length) / 1024 / 1024/ 1024, 3) AS total_size_gb
			FROM
				information_schema.tables
			WHERE
				table_schema = 'mhospit1_dhdc3'
			UNION ALL
			SELECT 
				'Total' AS table_name,
				NULL AS table_rows,
				SUM(data_length + index_length) AS total_size,
				ROUND(SUM(data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
					ROUND(SUM(data_length + index_length) / 1024 / 1024/ 1024, 3) AS total_size_gb
			FROM
				information_schema.tables
			WHERE
				table_schema = 'mhospit1_dhdc3'
			ORDER BY
				total_size DESC  LIMIT 10;


		";
		$rawDataHost = \yii::$app->db_host->createCommand($sqldhdc)->queryAll();
		try {
			$rawDataHost = \Yii::$app->db_host->createCommand($sqldhdc)->queryAll();
		} catch (\yii\db\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$dhdcProvider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawDataHost,
		'pagination' => [
		'pageSize' => 12,
		],
		]);
		############################ Monitor Mbase 192.168.200.14 ###############################################
		
		$sql = "SELECT 
    table_name,
    table_rows,
    data_length + index_length AS total_size,
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
		ROUND((data_length + index_length) / 1024 / 1024/ 1024, 3) AS total_size_gb
	FROM
		information_schema.tables
	WHERE
		table_schema = 'mbase_data1'
	ORDER BY
		total_size DESC  LIMIT 10;
				
		";
		$rawDataHostx = \yii::$app->db14->createCommand($sql)->queryAll();
		try {
			$rawDataHostx = \Yii::$app->db14->createCommand($sql)->queryAll();
		} catch (\yii\db\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$mbase14Provider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawDataHostx,
		'pagination' => [
		'pageSize' => 12,
		],
		]);
		############################ Monitor Mbase 192.168.200.7 ###############################################
		/*
		$sql = "SELECT 
    table_name,
    table_rows,
    data_length + index_length AS total_size,
    ROUND((data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
		ROUND((data_length + index_length) / 1024 / 1024/ 1024, 3) AS total_size_gb
	FROM
		information_schema.tables
	WHERE
		table_schema = 'mbase_data1'
	ORDER BY
		total_size DESC  LIMIT 10;
				
		";
		$rawDataHost7 = \yii::$app->db7->createCommand($sql)->queryAll();
		try {
			$rawDataHost7 = \Yii::$app->db7->createCommand($sql)->queryAll();
		} catch (\yii\db\Exception $e) {
			throw new \yii\web\ConflictHttpException('sql error');
		}

		$mbase7Provider = new \yii\data\ArrayDataProvider([
		'allModels' => $rawDataHost7,
		'pagination' => [
		'pageSize' => 12,
		],
		]);
		*/
		return $this->render('dashboard_m30', [
		// 'searchModel' => $searchModel,
		'dataProvider' => $dataProvider,
		'data14Provider' => $data14Provider,
		'data70Provider' => $data70Provider,
		'data7Provider' => $data7Provider,
		'dhdcProvider' => $dhdcProvider,
		'mbase14Provider' => $mbase14Provider,
		//'mbase7Provider' => $mbase7Provider,
		'epidemProvider' => $epidemProvider,
		'refersProvider' => $refersProvider,
		'vsignProvider' => $vsignProvider,
		'phrProvider' => $phrProvider,
		'rfretroProvider' => $rfretroProvider,
		
		]);   
	}
	
}
