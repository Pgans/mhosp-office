<?php

namespace app\controllers;

use Yii;
use yii\data\ArrayDataProvider;
//use kartik\mpdf\Pdf;
use yii\filters\VerbFilter;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

class ReportController extends \yii\web\Controller {

    public function behaviors(){
    
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=> ['index','admin','create','update','view','delete'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['?']
                    ],
                    [
                        'actions'=>['index','create'],
                        'allow'=> true,
                        'roles' => [
                           User::ROLE_USER,
                         ]
                    ],
                    [
                        'actions'=>['index','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['admin','index','create','update','view'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_ADMIN
                        ]
                    ],
                    [
                        'actions'=>['delete'],
                        'allow'=> true,
                        'roles'=>[User::ROLE_ADMIN]
                    ]
                ]
            ]
        ];
    }


    public function actionIndex() {
        $y = date("Y", time());
        $m = date("m", time());

        if ($m == '01') {
            $trans_m = 'มกราคม';
        } elseif ($m == '02') {
            $trans_m = 'กุมภาพันธ์';
        } elseif ($m == '03') {
            $trans_m = 'มีนาคม';
        } elseif ($m == '04') {
            $trans_m = 'เมษายน';
        } elseif ($m == '05') {
            $trans_m = 'พฤษภาคม';
        } elseif ($m == '06') {
            $trans_m = 'มิถุนายน';
        } elseif ($m == '07') {
            $trans_m = 'กรกฎาคม';
        } elseif ($m == '08') {
            $trans_m = 'สิงหาคม';
        } elseif ($m == '09') {
            $trans_m = 'กันยายน';
        } elseif ($m == '10') {
            $trans_m = 'ตุลาคม';
        } elseif ($m == '11') {
            $trans_m = 'พฤศจิกายน';
        } else {
            $trans_m = 'ธันวาคม';
        }
        /*
         * คำสั่ง sql ดึงข้อมูลการจองขอเดือนปัจจุบัน
         */
        $sql = "
        SELECT v.license,
        r.date_start AS start,
        r.date_end AS end,
        p.firstname AS fn,
        p.lastname AS ln
        FROM rental r
        LEFT JOIN vehicle v ON v.vehicle_id = r.vehicle_id
        LEFT JOIN person p ON p.user_id = r.user_id
        WHERE MONTH(r.date_start)= '" . $m . "' AND YEAR(r.date_start) = '" . $y . " ' AND r.status = '1'
         ORDER BY r.date_start DESC   ";
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        /*
         * ส่งข้อมูลให้ตาราง
         */
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
        ]);

        return $this->render('index', [
                    'month' => $trans_m,
                    'dataProvider' => $dataProvider,
                    $dataProvider->pagination = [
                        'pageSize' => 10,
                    
                    ]
        ]);
    }
 
    public function actionNext() {
        $y = date("Y", time());
        $m_now = date("m", time());
        if ($m_now == '01') {
            $m = '02';
        } elseif ($m_now == '02') {
            $m = '03';
        } elseif ($m_now == '03') {
            $m = '04';
        } elseif ($m_now == '04') {
            $m = '05';
        } elseif ($m_now == '05') {
            $m = '06';
        } elseif ($m_now == '06') {
            $m = '07';
        } elseif ($m_now == '07') {
            $m = '08';
        } elseif ($m_now == '08') {
            $m = '09';
        } elseif ($m_now == '09') {
            $m = '10';
        } elseif ($m_now == '10') {
            $m = '11';
        } elseif ($m_now == '11') {
            $m = '12';
        } else {
            $m = '01';
            $y = $y+'1';
        }

        if ($m == '01') {
            $trans_m = 'มกราคม';
        } elseif ($m == '02') {
            $trans_m = 'กุมภาพันธ์';
        } elseif ($m == '03') {
            $trans_m = 'มีนาคม';
        } elseif ($m == '04') {
            $trans_m = 'เมษายน';
        } elseif ($m == '05') {
            $trans_m = 'พฤษภาคม';
        } elseif ($m == '06') {
            $trans_m = 'มิถุนายน';
        } elseif ($m == '07') {
            $trans_m = 'กรกฎาคม';
        } elseif ($m == '08') {
            $trans_m = 'สิงหาคม';
        } elseif ($m == '09') {
            $trans_m = 'กันยายน';
        } elseif ($m == '10') {
            $trans_m = 'ตุลาคม';
        } elseif ($m == '11') {
            $trans_m = 'พฤศจิกายน';
        } else {
            $trans_m = 'ธันวาคม';
        }
        /*
         * คำสั่ง sql ดึงข้อมูลการจองขอเดือนปัจจุบัน
         */
        $sql = "
            SELECT v.license,
            r.date_start AS start,
            r.date_end AS end,
            p.firstname AS fn,
            p.lastname AS ln
            FROM rental r
            LEFT JOIN vehicle v ON v.vehicle_id = r.vehicle_id
            LEFT JOIN person p ON p.user_id = r.user_id
            WHERE MONTH(r.date_start)= '" . $m . "' AND YEAR(r.date_start) = '" . $y . "' AND r.status = '1'
            ";
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        /*
         * ส่งข้อมูลให้ตาราง
         */
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
        ]);

        return $this->render('next', [
                    'month' => $trans_m,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReport() {

        $d = date("d", time());
        $y = date("Y", time());
        $m = date("m", time());

        if ($m == '01') {
            $trans_m = 'มกราคม';
        } elseif ($m == '02') {
            $trans_m = 'กุมภาพันธ์';
        } elseif ($m == '03') {
            $trans_m = 'มีนาคม';
        } elseif ($m == '04') {
            $trans_m = 'เมษายน';
        } elseif ($m == '05') {
            $trans_m = 'พฤษภาคม';
        } elseif ($m == '06') {
            $trans_m = 'มิถุนายน';
        } elseif ($m == '07') {
            $trans_m = 'กรกฎาคม';
        } elseif ($m == '08') {
            $trans_m = 'สิงหาคม';
        } elseif ($m == '09') {
            $trans_m = 'กันยายน';
        } elseif ($m == '10') {
            $trans_m = 'ตุลาคม';
        } elseif ($m == '11') {
            $trans_m = 'พฤศจิกายน';
        } else {
            $trans_m = 'ธันวาคม';
        }
        $sql = "SELECT COUNT(v.vehicle_id) AS counter, v.license
                FROM rental r
                INNER JOIN vehicle v ON v.vehicle_id = r.vehicle_id
                WHERE MONTH(r.date_start)= '" . $m . "' AND YEAR(r.date_start) = '" . $y . "' AND r.status = '1'
                GROUP BY r.vehicle_id
                ";
        // AND YEAR(r.date_start) = '" . $y . "'
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $graph = [];
        foreach ($data as $d) {
            $graph[] = [
                'type' => 'column',
                'name' => $d['license'],
                'data' => [intval($d['counter'])]
            ];
        }

        /*
         * ส่งข้อมูลให้ตาราง
         */
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['license', 'counter']
            ]
        ]);

        return $this->render('report', [
                    'd' => $d,
                    'm' => $m,
                    'y' => $y+'543',
                    'month' => $trans_m,
                    'graph' => $graph,
                    'dataProvider' => $dataProvider,
        ]);
    }
 
    public function actionReportprev() {

        $d = date("d", time());
        $y = date("Y", time());
        $m_now = date("m", time());
        if ($m_now == '01') {
            $m = '12';
            $y = $y-'1';
        } elseif ($m_now == '02') {
            $m = '01';
        } elseif ($m_now == '03') {
            $m = '02';
        } elseif ($m_now == '04') {
            $m = '03';
        } elseif ($m_now == '05') {
            $m = '04';
        } elseif ($m_now == '06') {
            $m = '05';
        } elseif ($m_now == '07') {
            $m = '06';
        } elseif ($m_now == '08') {
            $m = '07';
        } elseif ($m_now == '09') {
            $m = '08';
        } elseif ($m_now == '10') {
            $m = '09';
        } elseif ($m_now == '11') {
            $m = '10'; 
        } else {
            $m = '11';
        }

        if ($m == '01') {
            $trans_m = 'มกราคม';
        } elseif ($m == '02') {
            $trans_m = 'กุมภาพันธ์';
        } elseif ($m == '03') {
            $trans_m = 'มีนาคม';
        } elseif ($m == '04') {
            $trans_m = 'เมษายน';
        } elseif ($m == '05') {
            $trans_m = 'พฤษภาคม';
        } elseif ($m == '06') {
            $trans_m = 'มิถุนายน';
        } elseif ($m == '07') {
            $trans_m = 'กรกฎาคม';
        } elseif ($m == '08') {
            $trans_m = 'สิงหาคม';
        } elseif ($m == '09') {
            $trans_m = 'กันยายน';
        } elseif ($m == '10') {
            $trans_m = 'ตุลาคม';
        } elseif ($m == '11') {
            $trans_m = 'พฤศจิกายน';
        } else {
            $trans_m = 'ธันวาคม';
        }
        $sql = "SELECT COUNT(v.vehicle_id) AS counter, v.license
                FROM rental r
                LEFT JOIN vehicle v ON v.vehicle_id = r.vehicle_id
                WHERE MONTH(r.date_start)= '" . $m . "' AND YEAR(r.date_start) = '" . $y . "' AND r.status = '1'
                GROUP BY r.vehicle_id
                ";
        // AND YEAR(r.date_start) = '" . $y . "'
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $graph = [];
        foreach ($data as $d) {
            $graph[] = [
                'type' => 'column',
                'name' => $d['license'],
                'data' => [intval($d['counter'])]
            ];
        }

        /*
         * ส่งข้อมูลให้ตาราง
         */
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['license', 'counter']
            ]
        ]);

        return $this->render('reportprev', [
                    'd' => $d,
                    'm' => $m,
                    'y' => $y,
                    'month' => $trans_m,
                    'graph' => $graph,
                    'dataProvider' => $dataProvider,
        ]);
    }
 
    public function actionReportnext() {

        $d = date("d", time());
        $y = date("Y", time());
        $m_now = date("m", time());
        if ($m_now == '01') {
            $m = '02';
        } elseif ($m_now == '02') {
            $m = '03';
        } elseif ($m_now == '03') {
            $m = '04';
        } elseif ($m_now == '04') {
            $m = '05';
        } elseif ($m_now == '05') {
            $m = '06';
        } elseif ($m_now == '06') {
            $m = '07';
        } elseif ($m_now == '07') {
            $m = '08';
        } elseif ($m_now == '08') {
            $m = '09';
        } elseif ($m_now == '09') {
            $m = '10';
        } elseif ($m_now == '10') {
            $m = '11';
        } elseif ($m_now == '11') {
            $m = '12';
        } else {
            $m = '01';
            $y = $y+'1';
        }

        if ($m == '01') {
            $trans_m = 'มกราคม';
        } elseif ($m == '02') {
            $trans_m = 'กุมภาพันธ์';
        } elseif ($m == '03') {
            $trans_m = 'มีนาคม';
        } elseif ($m == '04') {
            $trans_m = 'เมษายน';
        } elseif ($m == '05') {
            $trans_m = 'พฤษภาคม';
        } elseif ($m == '06') {
            $trans_m = 'มิถุนายน';
        } elseif ($m == '07') {
            $trans_m = 'กรกฎาคม';
        } elseif ($m == '08') {
            $trans_m = 'สิงหาคม';
        } elseif ($m == '09') {
            $trans_m = 'กันยายน';
        } elseif ($m == '10') {
            $trans_m = 'ตุลาคม';
        } elseif ($m == '11') {
            $trans_m = 'พฤศจิกายน';
        } else {
            $trans_m = 'ธันวาคม';
        }
        $sql = "SELECT COUNT(v.vehicle_id) AS counter, v.license
                FROM rental r
                LEFT JOIN vehicle v ON v.vehicle_id = r.vehicle_id
                WHERE MONTH(r.date_start)= '" . $m . "' AND YEAR(r.date_start) = '" . $y . "' AND r.status = '1'
                GROUP BY r.vehicle_id
                ";
        // AND YEAR(r.date_start) = '" . $y . "'
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $graph = [];
        foreach ($data as $d) {
            $graph[] = [
                'type' => 'column',
                'name' => $d['license'],
                'data' => [intval($d['counter'])]
            ];
        }

        /*
         * ส่งข้อมูลให้ตาราง
         */
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['license', 'counter']
            ]
        ]);

        return $this->render('reportnext', [
                    'd' => $d,
                    'm' => $m,
                    'y' => $y,
                    'month' => $trans_m,
                    'graph' => $graph,
                    'dataProvider' => $dataProvider,
        ]);
    }
 
    public function actionReportall() {

        $y = date("Y", time());

        $sql = "SELECT v.license,
                COUNT(IF(MONTH(r.date_start) = 1,r.id,NULL)) AS r1,
                COUNT(IF(MONTH(r.date_start) = 2,r.id,NULL)) AS r2,
                COUNT(IF(MONTH(r.date_start) = 3,r.id,NULL)) AS r3,
                COUNT(IF(MONTH(r.date_start) = 4,r.id,NULL)) AS r4,
                COUNT(IF(MONTH(r.date_start) = 5,r.id,NULL)) AS r5,
                COUNT(IF(MONTH(r.date_start) = 6,r.id,NULL)) AS r6,
                COUNT(IF(MONTH(r.date_start) = 7,r.id,NULL)) AS r7,
                COUNT(IF(MONTH(r.date_start) = 8,r.id,NULL)) AS r8,
                COUNT(IF(MONTH(r.date_start) = 9,r.id,NULL)) AS r9,
                COUNT(IF(MONTH(r.date_start) = 10,r.id,NULL)) AS r10,
                COUNT(IF(MONTH(r.date_start) = 11,r.id,NULL)) AS r11,
                COUNT(IF(MONTH(r.date_start) = 12,r.id,NULL)) AS r12
             
                FROM rental r
                INNER JOIN vehicle v ON v.vehicle_id = r.vehicle_id
                WHERE YEAR(r.date_start)= '" . $y . "' AND r.status = '1'
                GROUP BY r.vehicle_id  

                ";
     
        $data = Yii::$app->db->createCommand($sql)->queryAll();

        $graph = [];
        foreach ($data as $d) {
            $graph[] = [
                'type' => 'line',
                'name' => $d['license'],
                //'name' => 'ยอดการจองภายในเดือน',
                'data' => [
                    intval($d['r1']),
                    intval($d['r2']),
                    intval($d['r3']),
                    intval($d['r4']),
                    intval($d['r5']),
                    intval($d['r6']),
                    intval($d['r7']),
                    intval($d['r8']),
                    intval($d['r9']),
                    intval($d['r10']),
                    intval($d['r11']),
                    intval($d['r12']),
                ]
            ];
        }

        /*
         * ส่งข้อมูลให้ตาราง
         */
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['license','r1', 'r2', 'r3', 'r4', 'r5', 'r6', 'r7', 'r8', 'r9', 'r10', 'r11', 'r12',]
            ]
        ]);

        return $this->render('reportall', [
                    'y' => $y+'543',
                    'graph' => $graph,
                    'dataProvider' => $dataProvider,
                    
        ]);
    }

    public function actionMap() {
        return $this->redirect('http://192.168.200.98/mhosp-office/web/index.php?r=report/map.php');
        //return $this->render('map');
        //$contacts = Contact::find()->all();
        //return $this->render('map',['contacts'=>$contacts]);
    }
 
    /*
    public function actionExport() {
        $sql = "SELECT *
                FROM rental r
                LEFT JOIN vehicle v ON v.id = r.vehicle_id
                WHERE MONTH(r.date_start)= '" . $m . "'
                GROUP BY r.vehicle_id
                ";
        // AND YEAR(r.date_start) = '" . $y . "'
        $data = Yii::$app->db->createCommand($sql)->queryAll();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'sort' => [
                'attributes' => ['license', 'counter']
            ]
        ]);
    }
     *
     */

}
