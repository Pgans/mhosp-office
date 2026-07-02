<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;

class DashboardxController extends Controller
{
    public function actionDashboard()
    {
        // MASTER / SLAVE STATUS
        $masterStatus = Yii::$app->db2->createCommand('SHOW MASTER STATUS')->queryOne();
        $slaveStatus70 = Yii::$app->db70->createCommand('SHOW SLAVE STATUS')->queryOne();
        $slaveStatus22 = Yii::$app->db22->createCommand('SHOW SLAVE STATUS')->queryOne();
        $slaveStatus74 = Yii::$app->db74->createCommand('SHOW SLAVE STATUS')->queryOne();
        $slaveStatus4  = Yii::$app->db4->createCommand('SHOW SLAVE STATUS')->queryOne();

        // ----------------- SERVER 4 -----------------
        $sql4 = "SELECT 'opd_visits' as tables, COUNT(VISIT_ID) as amount 
        FROM opd_visits WHERE REG_DATETIME >= CURDATE() AND is_cancel = 0
        UNION
        SELECT 'refers out' as tables, COUNT(VISIT_ID) FROM refers WHERE rf_dt >= CURDATE() AND is_cancel = 0 AND RF_TYPE = 2 AND LEFT(hosp_id,3) NOT IN ('036','037','998')
        UNION
        SELECT 'refers in', COUNT(VISIT_ID) FROM refers WHERE rf_dt >= CURDATE() AND is_cancel = 0 AND RF_TYPE = 1
        UNION
        SELECT 'ipd', COUNT(VISIT_ID) FROM ipd_reg WHERE ADM_DT >= CURDATE() AND is_cancel = 0
        UNION
        SELECT 'Lab', COUNT(VISIT_ID) FROM lab_requests WHERE LREQ_DT >= CURDATE() AND is_cancel = 0
        UNION
        SELECT 'X-ray', COUNT(VISIT_ID) FROM xray_requests WHERE XREQ_DATETIME >= CURDATE() AND is_cancel = 0
        UNION
        SELECT 'ipd_cont', COUNT(VISIT_ID) FROM ipd_cont WHERE order_dt BETWEEN CURDATE() AND NOW() AND is_cancel = 0
        UNION
        SELECT 'cost_visits', COUNT(VISIT_ID) FROM cost_visits WHERE dt_timestamp BETWEEN CURDATE() AND NOW()
        UNION
        SELECT 'visit_invoice', COUNT(VISIT_ID) FROM visit_invoice WHERE record_dt BETWEEN CURDATE() AND NOW()";

        $data4Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db4->createCommand($sql4)->queryAll(),
            'pagination' => ['pageSize' => 10],
        ]);

        $data74Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db74->createCommand($sql4)->queryAll(),
            'pagination' => ['pageSize' => 10],
        ]);

        $data70Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db70->createCommand($sql4)->queryAll(),
            'pagination' => ['pageSize' => 10],
        ]);

        $data7Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db2->createCommand($sql4)->queryAll(),
            'pagination' => ['pageSize' => 10],
        ]);

        // ----------------- DHDC -----------------
        $sqldhdc = "SELECT 
            table_name, table_rows, data_length + index_length AS total_size,
            ROUND((data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
            ROUND((data_length + index_length) / 1024 / 1024 / 1024, 3) AS total_size_gb
        FROM information_schema.tables
        WHERE table_schema = 'mhospit1_dhdc3'
        UNION ALL
        SELECT 'Total', NULL, SUM(data_length + index_length),
            ROUND(SUM(data_length + index_length) / 1024 / 1024, 2),
            ROUND(SUM(data_length + index_length) / 1024 / 1024 / 1024, 3)
        FROM information_schema.tables
        WHERE table_schema = 'mhospit1_dhdc3'
        ORDER BY total_size DESC LIMIT 10";

        $dhdcProvider = new ArrayDataProvider([
            'allModels' => Yii::$app->db22->createCommand($sqldhdc)->queryAll(),
            'pagination' => ['pageSize' => 12],
        ]);

		  // ----------------- Placeholder -----------------
        $dataProvider = new ArrayDataProvider(['allModels' => []]);
        $data14Provider = new ArrayDataProvider(['allModels' => []]);
        $mbase74Provider = new ArrayDataProvider(['allModels' => []]);
        $mbase7Provider = new ArrayDataProvider(['allModels' => []]);
        $epidemProvider = new ArrayDataProvider(['allModels' => []]);
        $refersProvider = new ArrayDataProvider(['allModels' => []]);
        $vsignProvider = new ArrayDataProvider(['allModels' => []]);
        $phrProvider = new ArrayDataProvider(['allModels' => []]);
        $rfretroProvider = new ArrayDataProvider(['allModels' => []]);
        // ----------------- MBASE -----------------
        $sqlMbase = "SELECT 
            table_name, table_rows, data_length + index_length AS total_size,
            ROUND((data_length + index_length) / 1024 / 1024, 2) AS total_size_mb,
            ROUND((data_length + index_length) / 1024 / 1024 / 1024, 3) AS total_size_gb
        FROM information_schema.tables
        WHERE table_schema = 'mbase_data1'
        ORDER BY total_size DESC LIMIT 10";

        $mbase74Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db74->createCommand($sqlMbase)->queryAll(),
            'pagination' => ['pageSize' => 12],
        ]);

        $mbase4Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db4->createCommand($sqlMbase)->queryAll(),
            'pagination' => ['pageSize' => 12],
        ]);
		$mbase7Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db7->createCommand($sqlMbase)->queryAll(),
            'pagination' => ['pageSize' => 12],
        ]);
        $mbase70Provider = new ArrayDataProvider([
            'allModels' => Yii::$app->db70->createCommand($sqlMbase)->queryAll(),
            'pagination' => ['pageSize' => 12],
        ]);

      

        return $this->render('dashboard', [
            'dataProvider' => $dataProvider,
            'data4Provider' => $data4Provider,
            'data14Provider' => $data14Provider,
            'data70Provider' => $data70Provider,
            'data74Provider' => $data74Provider,
            'data7Provider' => $data7Provider,
            'dhdcProvider' => $dhdcProvider,
            'mbase14Provider' => $mbase14Provider,
            'mbase4Provider' => $mbase4Provider,
            'mbase70Provider' => $mbase70Provider,
            'mbase74Provider' => $mbase74Provider,
            'mbase7Provider' => $mbase7Provider,
            'epidemProvider' => $epidemProvider,
            'refersProvider' => $refersProvider,
            'vsignProvider' => $vsignProvider,
            'phrProvider' => $phrProvider,
            'rfretroProvider' => $rfretroProvider,
            'masterStatus' => $masterStatus,
            'slaveStatus' => $slaveStatus70,
            'slaveStatus74' => $slaveStatus74,
            'slaveStatus22' => $slaveStatus22,
            'slaveStatus4' => $slaveStatus4,
        ]);
    }
}