<?php

namespace app\controllers;

use Yii;
use app\models\Meetingagenda;
use app\models\Agendaitem;
use app\models\MeetingagendaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Meeting;
use app\models\Uploadfile;
use yii\db\Query;
/* เพิ่มคำสั่ง 3 บรรทัดต่อจากนี้ลงไป */
//use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
//use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
//use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่

/**
 * MeetingagendaController implements the CRUD actions for Meetingagenda model.
 */
class MeetingagendaController extends Controller
{
     public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	/*
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
                        'actions'=>['index','create','view','userview'],
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
	*/
    /**
     * Lists all Meetingagenda models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MeetingagendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $meetingAgendas = MeetingAgenda::find()->all();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'meetingAgendas' => $meetingAgendas,
        ]);
    }
    public function actionAdmin()
    {
        $searchModel = new MeetingagendaSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $meetingAgendas = MeetingAgenda::find()->all();
        return $this->render('admin', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'meetingAgendas' => $meetingAgendas,
        ]);
    }
    ########################INSERT ตาราง AGENDA_ITEM###########################
    public function actionRunQuery()
    {
        $connection = \Yii::$app->db;
        
        // คิวรีเพื่อดึง meeting_agenda_id ค่าสุดท้าย
        $lastMeetingAgendaId = (new Query())
            ->select('meeting_agenda_id')
            ->from('agenda_item')
            ->orderBy(['meeting_agenda_id' => SORT_DESC])
            ->limit(1)
            ->scalar();

        // เพิ่ม 1 เพื่อใช้เป็นค่าใหม่
        $newMeetingAgendaId = $lastMeetingAgendaId + 1;

        // คิวรีที่คุณต้องการรัน
        $query = "INSERT INTO `agenda_item` ( `agen_id`,`meeting_agenda_id`, `ref`, `topic`, `discription`, `covenant`, `docs`, `create_date`, `view`)
        VALUES
            (1, $newMeetingAgendaId, NULL, 'วาระที่1', 'ประธานแจ้งให้ทราบ', NULL, '', '2023-8-11 15:40:11', NULL),
            (2, $newMeetingAgendaId, NULL, 'วาระที่2', 'รับรองรายงานการประชุมครั้งที่ผ่านมา', NULL, '', '2023-8-11 15:40:11', NULL),
            (3, $newMeetingAgendaId, NULL, 'วาระที่3', 'ประเด็นสำคัญที่ติดตาม', '', '', '2023-8-11 15:40:11', NULL),
            (4, $newMeetingAgendaId, NULL, 'วาระที่4', 'เรื่องจากกลุ่มงาน', NULL, '', '2023-8-11 15:40:11', NULL),
            (5, $newMeetingAgendaId, NULL, 'วาระที่5', 'เรื่องอื่นๆ', NULL, '', '2023-8-11 15:40:11', NULL);         
             "; 

        // รันคิวรี
        $connection->createCommand($query)->execute();
        \Yii::$app->session->setFlash('success', 'เพิ่มวาระการประชุมเรียบร้อยแล้ว');
        // อัพเดตหรือทำอื่นๆตามความต้องการ
        // ...

        return $this->redirect(['admin']); // เปลี่ยนเป็นหน้าที่คุณต้องการไปหลังจากรันคิวรี
    }
    ###################################################
    ########################INSERT ตาราง AGENDA_SUBX###########################
    public function actionRunAgendasubx()
    {
        $connection = \Yii::$app->db;
        
        // คิวรีเพื่อดึง meeting_agenda_id ค่าสุดท้าย
        $lastAgandasubxId = (new Query())
            ->select('meeting_id')
            ->from('agenda_subx')
            ->orderBy(['meeting_id' => SORT_DESC])
            ->limit(1)
            ->scalar();

        // เพิ่ม 1 เพื่อใช้เป็นค่าใหม่
        $newAgendasubxId = $lastAgandasubxId + 1;

        // คิวรีที่คุณต้องการรัน
        $query = "INSERT INTO `agenda_subx` ( `meeting_id`, `agenda_id`, `sub_topic`, `sub_description`, `department`, `filename`, `path`, `create_date`)
        VALUES
        ( $newAgendasubxId , 3, '3.1  สถานการณ์โรคและภัยสุขภาพที่สำคัญ + สิ่งแวดล้อม : กลุ่มงานบริการด้านปฐมภูมิและองค์รวม', 'ให้เสนอข้อมูลสถานการณ์โรคและภัยสุขภาพที่สำคัญ เช่น ไข้เลือดออก เห็ดพิษ Leptospirosis หรือสิ่งแวดล้อมที่เป็นปัญหาในโรงพยาบาล', '', '', '', '2023-8-10 12:47:12'),
        ( $newAgendasubxId , 3, '3.2 การบริหารทรัพยากรบุคคล : กลุ่มงานบริหารงานทั่วไป', 'ให้เสนอข้อมูลแผนบุคลากร(3 ปี) - ฝึกอบรม - การจ้าง - การลาออก – ความดีความชอบ –ความก้าวหน้า และปัญหาสุขภาพ/การเงินของเจ้าหน้าที่', '', '', '', '2023-8-10 13:41:32'),
        ( $newAgendasubxId , 3, '3.3 สถานะการเงินการคลัง : งานการเงิน', 'ให้เสนอข้อมูลสถานะการเงินการคลัง', '', '', '', '2023-8-10 12:05:10'),
        ( $newAgendasubxId , 3, '3.4 กองทุนต่างๆ : กลุ่มงานประกันสุขภาพยุทธศาสตร์และสารสนเทศทางการแพทย์', 'ให้เสนอข้อมูลรายได้จากกองทุนที่สำคัญ ได้แก่ ผู้ป่วยใน, non uc, ประกันสังคม, พรบ., ทันตกรรม แผนไทย กายภาพบำบัด และ PP อื่นที่ไม่รวมกองทุนที่กล่าวข้างต้น โดยให้แสดงข้อมูลเปรียบเทียบแผนกับผลงานที่ได', '', '', '', '2023-8-10 10:35:22'),
        ( $newAgendasubxId , 3, '3.5 ค่าใช้จ่ายที่สำคัญ : กลุ่มงานบริหารงานทั่วไป', '▪ ให้เสนอข้อมูล ดังนี้ ค่าไฟฟ้า ค่าน้ำมัน ค่าซ่อมแซมรถยนต์ค่าวัตถุดิบโรงครัว ค่าวัสดุงานบ้านสำนักงาน ค่าหมึกพิมพ์ ยา LAB ค่าออกซิเจน โดยแสดงข้อมูลเป็นรายเดือนให้เห็นแนวโน้ม เพิ่มขึ้นหรือลดลง', '', '', '', '2023-8-10 13:46:36'),
        ( $newAgendasubxId , 3, '3.6 ความก้าวหน้าแผนการลงทุน 43 ล้าน +งบค่าเสื่อม : กลุ่มงานประกันสุขภาพยุทธศาสตร์และสารสนเทศทางการแพทย์', '▪ ให้เสนอข้อมูลแผนปัจจุบันและแผน 3 ปีข้างหน้า', '', '', '', '2023-8-9 14:25:50'),
        ( $newAgendasubxId , 3, '3.7 ความก้าวหน้าการพัฒนาระบบเทคโนโลยีสารสนเทศ : งาน IT', '▪ ให้เสนอความก้าวหน้าในการพัฒนา tripleA, iclaim, backoffice, mBaseและอื่นๆ ที่สำคัญ', '', '', '', '0000-0-0 00:00:00'),
        ( $newAgendasubxId , 3, '3.8 แผนปฏิบัติการ และการปรับแผนปฏิบัติการ : กลุ่มงานประกันสุขภาพยุทธศาสตร์และสารสนเทศทางการแพทย์', '▪ ให้เสนอความก้าวหน้าการดำเนินงานตามแผนปฏิบัติการ รวมถึงการปรับแผนปฏิบัติการ', '', '', '', '0000-0-0 00:00:00'),
        ( $newAgendasubxId , 3, '3.9 ความเสี่ยงที่สำคัญของโรงพยาบาล : ทีมความเสี่ยง', '▪ ให้เสนอข้อมูลข้อร้องเรียนต่างๆ เน้นความเสี่ยงระดับ E และข้อร้องเรียนด้านพฤติกรรมบริการ โดยระบุประเด็นพฤติกรรมบริการใด และเกิดขึ้นในหน่วยงานใด', '', '', '', '2023-8-10 10:35:28'),
        ( $newAgendasubxId , 3, '3.10 งานพัฒนาคุณภาพสถานพยาบาล : สำนักคุณภาพ', '▪ ให้เสนอข้อมูลความก้าวหน้า หรือประเด็นสำคัญที่ต้องเร่งกำกับติดตามเพื่อให้การพัฒนาคุณภาพ เป็นไปอย่างต่อเนื่อง', '', '', '', '2023-8-9 14:29:20'),
        ( $newAgendasubxId , 3, '3.11 การปรับระบบบริการ: กลุ่มงานการพยาบาล', '▪ ให้เสนอข้อมูลการปรับระบบบริการในแต่ละจุด เช่น การบริการนอกเวลา การปรับระบบงานร่วมกันระหว่างสหวิชาชีพ เป็นต้น', '', '', '', '2023-8-10 10:35:31'),
        ( $newAgendasubxId , 4, '1. กลุ่มงานบริหารทั่วไป', '▪ ให้เสนอข้อมูลการปรับระบบบริการในแต่ละจุด เช่น การบริการนอกเวลา การปรับระบบงานร่วมกันระหว่างสหวิชาชีพ เป็นต้นเด้อครับ5555', '', '', '', '2023-8-10 13:43:58'),
        ( $newAgendasubxId , 4, '2. กลุ่มงานการพยาบาล', '', '', 'รพ.ม่วงสามสิบ คำสั่งแต่งตั้งคณะกรรมการ DI.pdf', '', '2023-8-10 13:45:26'),
        ( $newAgendasubxId , 4, '3.กลุ่มงานบริการด้านปฐมภูมิและองค์รวม', NULL, '', '', '', '0000-0-0 00:00:00'),
        ( $newAgendasubxId , 4, '4. กลุ่มงานเทคนิคการแพทย์', '', '', '', '', '2023-8-9 15:30:43'),
        ( $newAgendasubxId , 4, '5. กลุ่มงานเภสัชกรรมฯ', NULL, '', '', '', '2023-8-9 15:00:35'),
        ( $newAgendasubxId , 4, '6. กลุ่มงานทันตกรรม', '', '', '', '', '2023-8-9 16:17:28'),
        ( $newAgendasubxId , 4, '7. กลุ่มงานบริการทางการแพทย์', '', '', '', '', '2023-8-9 15:32:12'),
        ( $newAgendasubxId , 4, '8. กลุ่มงานยุทธศาสตร์', '', '', '', '', '2023-8-9 15:32:12'), 
        ( $newAgendasubxId , 1, '', '', '', '', '', '2023-8-9 15:32:12'), 
        ( $newAgendasubxId , 2, '', '', '', '', '', '2023-8-9 15:32:12'), 
        ( $newAgendasubxId , 5, '', '', '', '', '', '2023-8-9 15:32:12'); 
             "; 

        // รันคิวรี
        $connection->createCommand($query)->execute();
        \Yii::$app->session->setFlash('success', 'เพิ่มหัวข้อย่อยการประชุมและการแนบไฟล์เรียบร้อยแล้ว');
        // อัพเดตหรือทำอื่นๆตามความต้องการ
        // ...

        return $this->redirect(['admin']); // เปลี่ยนเป็นหน้าที่คุณต้องการไปหลังจากรันคิวรี
    }
    ###################################################
    public function actionRunSql()
        {
            $sqlFile = 'uploads/agenda_subx.sql';

            try {
                $sql = file_get_contents($sqlFile);
                
                // ทำการเชื่อมต่อกับฐานข้อมูลและรันคำสั่ง SQL
                $db = Yii::$app->db;
                $transaction = $db->beginTransaction();
                $db->createCommand($sql)->execute();
                $transaction->commit();

                echo "SQL file executed successfully.";
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
    // public function actionViewAgenda($id)
    // {
    //     $model = $this->findModel($id);
    //     $keypointsModel = new Uploadfile();

    //     if ($keypointsModel->load(Yii::$app->request->post())) {
    //         $keypointsModel->file = UploadedFile::getInstance($keypointsModel, 'file');
    //         if ($keypointsModel->upload()) {
    //             // Save the keypoints model with the meeting_id
    //             $keypointsModel->meeting_id = $model->id;
    //             $keypointsModel->save();
    //             // Redirect or show success message
    //         }
    //     }

    //     return $this->render('view', [
    //         'model' => $model,
    //         'keypointsModel' => $keypointsModel,
    //     ]);
    // }
    /**
     * Displays a single Meetingagenda model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
       
    //     $agendaItems = AgendaItem::find()
    //         ->where(['meeting_agenda_id' => $id])
    //         ->groupBy('agenda_id')
    //         ->all();

    //     return $this->render('view', [
    //         'agendaItems' => $agendaItems,
    //     ]);
    // }
        $meetingAgenda = MeetingAgenda::findOne($id);
            
        if (!$meetingAgenda) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'meetingAgenda' => $meetingAgenda,
            
        ]);
    }
	public function actionView_admin($id)
    {
       
    //     $agendaItems = AgendaItem::find()
    //         ->where(['meeting_agenda_id' => $id])
    //         ->groupBy('agenda_id')
    //         ->all();

    //     return $this->render('view', [
    //         'agendaItems' => $agendaItems,
    //     ]);
    // }
        $meetingAgenda = MeetingAgenda::findOne($id);
            
        if (!$meetingAgenda) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view_admin', [
            'meetingAgenda' => $meetingAgenda,
            
        ]);
    }
    /**
     * Creates a new Meetingagenda model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Meetingagenda();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Meetingagenda model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Meetingagenda model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Meetingagenda model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Meetingagenda the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Meetingagenda::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
