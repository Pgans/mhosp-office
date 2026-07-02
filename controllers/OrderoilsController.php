<?php

namespace app\controllers;

use Yii;
use mPDF;
use kartik\mpdf\Pdf;
//use Mpdf\Mpdf;
use app\models\Orderoils;
use app\models\OrderoilsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use yii\helpers\BaseFileHelper;
use app\models\Province;
use app\models\Amphur;
use app\models\District;
use app\models\Mooban;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\filters\AccessControl;        // เรียกใช้ คลาส AccessControl
use app\models\User;             // เรียกใช้ Model คลาส User ที่ปรับปรังปรุงไว้
use app\components\AccessRule;   // เรียกใช้ คลาส Component AccessRule ที่เราสร้างใหม่



/**
 * OrderoilsController implements the CRUD actions for Orderoils model.
 */
class OrderoilsController extends Controller
{
    /**
     * @inheritdoc
     */
    
    public function behaviors(){
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access'=>[
                'class'=>AccessControl::className(),
                'only'=> ['index','create','update','view','delete'],
                'ruleConfig'=>[
                    'class'=>AccessRule::className()
                ],
                'rules'=>[
                    [
                        'actions'=>['index','create','view'],
                        'allow'=> true,
                        'roles'=>[
                            
                            User::ROLE_EMPLOYEE,
                            User::ROLE_ADMIN

                        ]
                    ],
                    [
                        'actions'=>['update'],
                        'allow'=> true,
                        'roles'=>[
                            User::ROLE_EMPLOYEE,
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

    /**
     * Lists all Orderoils models.
     * @return mixed
     */
    public function actionIndex()
    {
        
        $searchModel = new OrderoilsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort->defaultOrder = ['oilorder_id' => SORT_DESC];

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            $dataProvider->pagination = [
                'pageSize' => 5,
            
            ]
        ]);
    }
    
    public function actionPdf()
    {
       
        //$mpdf = new mPDF;
        //$mpdf = new Pdf('utf-8', 'A4',0,'Garuda');
    //$mpdf = new mPdf('th', 'A4', '0', 'Garuda'); // ขนาด A4 font Garuda
    $mpdf->WriteHTML('TEST PDFเด้อ');
    //$mpdf->Output('data.pdf', 'D');
    $mpdf->WriteHTML($this->renderPartial('_reportView')); // หน้า View สำหรับ export

    $mpdf->Output();
    exit();
    }

    /**
     * Displays a single Orderoils model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Orderoils model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
	 public function actionCreate()
{
    $model = new Orderoils();

    $amphurList = \yii\helpers\ArrayHelper::map(
        \app\models\Amphur::find()->all(),
        'AMPHUR_ID', 'AMPHUR_NAME'
    );

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->render('create', [
        'model' => $model,
        'amphurList' => $amphurList,
    ]);
}
	 /*
	 
    public function actionCreate()
    {
        $model = new Orderoils();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->oilorder_id]);
        }
       // return $this->renderAjax('create', [
        return $this->render('create', [
            'model' => $model,
            'amphur'=> [],
            'district' =>[],
            'mooban' =>[]
        ]);
    }
*/
    /**
     * Updates an existing Orderoils model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->oilsToArray();
        $amphur         = ArrayHelper::map($this->getAmphur($model->province),'id','name');
        $district       = ArrayHelper::map($this->getDistrict($model->amphur),'id','name');
        $mooban       = ArrayHelper::map($this->getMooban($model->district),'id','name');
 

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->oilorder_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'amphur'=> $amphur,
            'district' => $district,
            'mooban'=> $mooban
        ]);
    }

    /**
     * Deletes an existing Orderoils model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orderoils model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return Orderoils the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orderoils::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionGetAmphur() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $parents = $_POST['depdrop_parents'];
            if ($parents != null) {
                $province_id = $parents[0];
                $out = $this->getAmphur($province_id);
                echo Json::encode(['output'=>$out, 'selected'=>'']);
                return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetDistrict() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $amphur_id = empty($ids[1]) ? null : $ids[1];
            if ($province_id != null) {
               $data = $this->getDistrict($amphur_id);      
               echo Json::encode(['output'=>$data, 'selected'=>'']);
               return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    public function actionGetMooban() {
        $out = [];
        if (isset($_POST['depdrop_parents'])) {
            $ids = $_POST['depdrop_parents'];
            $province_id = empty($ids[0]) ? null : $ids[0];
            $amphur_id = empty($ids[1]) ? null : $ids[1];
            $district_id = empty($ids[2]) ? null : $ids[2];
            if ($province_id != null) {
               $data = $this->getMooban($district_id);      
               echo Json::encode(['output'=>$data, 'selected'=>'']);
               return;
            }
        }
        echo Json::encode(['output'=>'', 'selected'=>'']);
    }

    protected function getAmphur($id){
        $datas = Amphur::find()->where(['PROVINCE_ID'=>$id])->all();
        return $this->MapData($datas,'AMPHUR_ID','AMPHUR_NAME');
    }

    protected function getDistrict($id){
        $datas = District::find()->where(['AMPHUR_ID'=>$id])->all();
        return $this->MapData($datas,'DISTRICT_ID','DISTRICT_NAME');
    }

    protected function getMooban($id){
        $datas = Mooban::find()->where(['DISTRICT_ID'=>$id])->all(); 
        return $this->MapData($datas,'mooban_id','mooban_name');
    }

    protected function MapData($datas,$fieldId,$fieldName){
        $obj = [];
        foreach ($datas as $key => $value) {
            array_push($obj, ['id'=>$value->{$fieldId},'name'=>$value->{$fieldName}]);
        }
        return $obj;
    }
    public function actionPrint($id)
    {
        //'model' => Orderoils::findOne($id),
        $data = $this->findModel($id);
        // 'model' => $this->findModel($id),
 
         // get your HTML raw content without any layouts or scripts
         $content = $this->renderPartial('_reportView', ['model' => $data]);
     

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_UTF8,
            // A4 paper format
            'format' => [40, 20],//Pdf::FORMAT_A4,
            'marginLeft' => false,
            'marginRight' => false,
            'marginTop' => 1,
            'marginBottom' => false,
            'marginHeader' => false,
            'marginFooter' => false,

            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@app/web/css/kv-mpdf-bootstrap.css',
            // any css to be embedded if required
            'cssInline' => 'body{font-size:9px}',
            // set mPDF properties on the fly
            'options' => ['title' => 'Print Sticker', ],
            // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>false,//['Krajee Report Header'],
                'SetFooter'=>false,//['{PAGENO}'],
            ]
        ]);

        // return the pdf output as per the destination setting
        return $pdf->render();
    }
    // public function actionReport() {
    //      $data = $this->findModel($id);
    //     // 'model' => $this->findModel($id),
 
    //      // get your HTML raw content without any layouts or scripts
    //      $content = $this->renderPartial('_reportView', ['model' => $data]);
        
    //     // setup kartik\mpdf\Pdf component
    //     $pdf = new Pdf([
    //         // set to use core fonts only
    //         //'mode' => Pdf::MODE_CORE, 
    //         'mode' => Pdf::MODE_UTF8,
    //         // A4 paper format
    //         'format' => Pdf::FORMAT_A4, 
    //         // portrait orientation
    //         'orientation' => Pdf::ORIENT_PORTRAIT, 
    //         // stream to browser inline
    //         'destination' => Pdf::DEST_BROWSER, 
    //         // your html content input
    //         'content' => $content,  
    //         // format content from your own css file if needed or use the
    //         // enhanced bootstrap css built by Krajee for mPDF formatting 
    //         'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
    //         // any css to be embedded if required
    //         'cssInline' => '.kv-heading-1{font-size:18px}', 
    //          // set mPDF properties on the fly
    //         'options' => ['title' => 'KrajeePDF'],
    //          // call mPDF methods on the fly
    //         'methods' => [ 
    //             'SetHeader'=>['Krajee Report Header'], 
    //            // 'SetFooter'=>['{PAGENO}'],
    //         ]
    //     ]);
        
    //     // return the pdf output as per the destination setting
    //     return $pdf->render(); 
    //     }

        public function actionReport1($id) {

            $data = $this->findModel($id);
           // 'model' => $this->findModel($id),
    
            // get your HTML raw content without any layouts or scripts
            $content = $this->renderPartial('_reportView', ['model' => $data]);
    
            $destination = Pdf::DEST_BROWSER;
            //$destination = Pdf::DEST_DOWNLOAD;
    
           //$filename = $data->_reportView. ".pdf";
			
            $pdf = new Pdf([
                // set to use core fonts only
                'mode' => Pdf::MODE_UTF8,
                // A4 paper format
                'format' => Pdf::FORMAT_A4,
                // portrait orientation
                'orientation' => Pdf::ORIENT_PORTRAIT,
                // stream to browser inline
                'destination' => $destination,
                'filename' => $filename,
                // your html content input
                'content' => $content,
                // format content from your own css file if needed or use the
                // enhanced bootstrap css built by Krajee for mPDF formatting 
                'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
                // any css to be embedded if required
                'cssInline' => 'p, td,div { font-family: freeserif; }; body, p { font-family: irannastaliq; font-size: 15pt; }; .kv-heading-1{font-size:18px}table{width: 100%;line-height: inherit;text-align: left; border-collapse: collapse;}table, td, th {border: 1px solid black;}',
                'marginFooter' => 5,
                // call mPDF methods on the fly
                'methods' => [
                    'SetTitle' => ['PDF'],
                    //'SetHeader' => ['SAMPLE'],
                  //  'SetFooter' => ['Page {PAGENO}'],
                ]
            ]);
    
            // return the pdf output as per the destination setting
            return $pdf->render();
        }
   
   
}