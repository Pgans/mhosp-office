<?php

namespace app\controllers;

use Yii;
use app\models\Repimport;
use app\models\RepimportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;



/**
 * RepimportController implements the CRUD actions for Repimport model.
 */
class RepimportController extends Controller
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all Repimport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RepimportSearch();
        $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider1,
        ]);
    }

    /**
     * Displays a single Repimport model.
     * @param integer $id
     * @return mixed
  
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

   
     */
    public function actionCreate()
    {
        $model = new Repimport();
		
         if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->auto_id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     ********************************************Import Files*****************************************
     */
	 public function actionImportExcel()
{
    $inputFile = 'upload/eclaim_template.xls';
    try{
        $inputFileType = \PHPExcel_IOFactory::identify($inputFile);
        $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
        $objPHPExcel = $objReader->load($inputFile);
    } catch (Exception $e) {
        die('Error');
    }

    $sheet = $objPHPExcel->getSheet(0);
    $highestRow = $sheet->getHighestRow();
    $highestColumn = $sheet->getHighestColumn();

    for($row=1; $row <= $highestRow; $row++)
    {
        $rowData = $sheet->rangeToArray('A'.$row.':'.$highestColumn.$row,NULL,TRUE,FALSE);

        if($row==1)
        {
            continue;
        }
		$import = new Repimport();
        //$siswa = new Siswa();
       // $import->auto_id = $rowData[0][0]; 
        $import->rep  = $rowData[0][1]; 
        $import->id  = $rowData[0][2]; 
        $import->train_id  = $rowData[0][3]; 
        $import->hn  = $rowData[0][4]; 
        $import->an  = $rowData[0][5]; 
        $import->pid  = $rowData[0][6]; 
        $import->fullname  = $rowData[0][7]; 
        $import->main  = $rowData[0][8]; 
        $import->regdate = $rowData[0][9];
        $import->discharge = $rowData[0][10];
        $import->ins = $rowData[0][11];
		$import->pp = $rowData[0][12];
		$import->errorcode = $rowData[0][13];
		$import->sub = $rowData[0][14];
        $import->save();
        print_r($import->getErrors());
    }
    die('okay');
}
/********************************************************* จบImport ********************************************************************/
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
	
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->auto_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Repimport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Repimport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
	
    public function actionView($id)
	//public function actionView()
    {
        $model = $this->findModel($id);

        try{
            $file = Yii::getAlias('@webroot').'/'.$model->uploadPath.'/'.$model->file;
            $inputFile = \PHPExcel_IOFactory::identify($file);
            $objReader = \PHPExcel_IOFactory::createReader($inputFile);
            $objPHPExcel = $objReader->load($file);
        }catch (Exception $e){
            Yii::$app->session->addFlash('error', 'เกิดข้อผิดพลาด'. $e->getMessage());
        }

        $sheet = $objPHPExcel->getSheet(0);
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        $objWorksheet = $objPHPExcel->getActiveSheet();

        foreach($objWorksheet->getRowIterator() as $rowIndex => $row){
            $arr[] = $objWorksheet->rangeToArray('A'.$rowIndex.':'.$highestColumn.$rowIndex);
        }

        /*
         * Post Register from active form
         */
	if(Yii::$app->request->post('register')){
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $register = Yii::$app->request->post('register');
                //var_dump($register);
                //die();
                foreach ($objWorksheet->getRowIterator() as $rowIndex => $row) {

                    $tmpdata = $objWorksheet->rangeToArray('A' . $rowIndex . ':' . $highestColumn . $rowIndex);
                    $data[] = $tmpdata[0];

                }

                foreach ($register as $key => $val) {
                    //Check if specific
                    if (!empty($val)) {//ถ้าเลือกส่ง
                        //var_dump($data[$key]);
                        //มีการ Import หรือยัง
                        $import = Repimport::find()->where(['rep' => $data[$key][1]])
                            ->andFilterWhere(['like', 'pid', $data[$key][6]])
                            ->one();
                        if (!$import) {//ถ้ายังไม่มีการ Import
                            
                           // $gender = $data[$key][2] == 'หญิง' ? 1 : 0;


                            $import = new Repimport();
                            $model->rep = (string)$sheetData[$baseRow]['A'];
                            $model->id = (string)$sheetData[$baseRow]['B'];
                            //$import->gender = $gender;
                            $import->train_id = $data[$key][3];
                            $import->hn = $data[$key][4];
                            $import->an = $data[$key][5];
                            $import->pid = $data[$key][6];
                            $import->fullname = $data[$key][7];
                            $import->main = $data[$key][8];
                            $import->regdate = $data[$key][9];
                            $import->discharge = $data[$key][10];
                            $import->ins = $data[$key][11];
                            $import->pp = $data[$key][12];
                            $import->errorcode = $data[$key][13];
							$import->sub = $data[$key][14];
                            //$import->sub = $val;

                            if($import->save()){
                                Yii::$app->session->addFlash('success', $import->fullname.' '.$import->pid.' นำเข้าข้อมูลเรียบร้อยแล้ว');
                            }else{
                                Yii::$app->session->addFlash('error', 'เกิดข้อผิดพลาดในการนำเข้าข้อมูล');
                                //var_dump($import);
                                //die();
                            }

                        }else{//รายการนี้ส่งแล้ว
                            Yii::$app->session->addFlash('info', $data[$key][1].' รายการนี้ส่งเข้าระบบแล้ว');
                        }

                    }else{//ถ้าไม่ได้เลือกส่ง
                        Yii::$app->session->addFlash('warning', $data[$key][1].' ไม่ได้เลือกส่ง');
                    }
                }//end foreach

                $transaction->commit();
                Yii::$app->session->addFlash('success', 'ดำเนินการนำเข้าข้อมูลเรียบร้อยแล้ว กรุณาตรวจสอบความถูกต้องอีกครั้ง');
                return $this->redirect(['view', 'id' => $model->id]);

            }catch (Exception $e){
                $transaction->rollBack();
                Yii::$app->session->addFlash('error', 'เกิดข้อผิดพลาด');
            }
        }///end if post


        $dataProvider = new ArrayDataProvider([
            'allModels' => $arr,
            'pagination' => false,
        ]);

        $dataProviderImport = new ActiveDataProvider([
			//'query'=> 'select * from rep_import order by auto_id DESC'
            'query' => Repimport::find()
                ->where(['fullname' => $model->fullname])
                ->orderBy(['auto_id' => SORT_DESC])
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'dataProviderImport' => $dataProviderImport,
        ]);
    }
	/****CSV*********************************************************/
	public function actionImports(){
        $modelImport = new \yii\base\DynamicModel([
                    'fileImport'=>'File Import',
                ]);
        $modelImport->addRule(['fileImport'],'required');
        $modelImport->addRule(['fileImport'],'file',['extensions'=>'ods,xls,xlsx'],['maxSize'=>1024*1024]);

        if(Yii::$app->request->post()){
            $modelImport->fileImport = \yii\web\UploadedFile::getInstance($modelImport,'fileImport');
            if($modelImport->fileImport && $modelImport->validate()){
                $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
                $objReader = \PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
                $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
                $baseRow = 3;
                while(!empty($sheetData[$baseRow]['B'])){
                    $model = new \app\models\Repimport;
                    $model->rep = (string)$sheetData[$baseRow]['A'];
                    $model->id = (string)$sheetData[$baseRow]['B'];
					$model->train_id = (string)$sheetData[$baseRow]['C'];
					$model->hn = (string)$sheetData[$baseRow]['D'];
					$model->an = (string)$sheetData[$baseRow]['E'];
					$model->pid = (string)$sheetData[$baseRow]['F'];
					$model->fullname = (string)$sheetData[$baseRow]['G'];
					$model->main = (string)$sheetData[$baseRow]['H'];
					$model->regdate = (string)$sheetData[$baseRow]['I'];
					$model->discharge = (string)$sheetData[$baseRow]['j'];
					$model->ins = (string)$sheetData[$baseRow]['K'];
					$model->pp = (string)$sheetData[$baseRow]['L'];
					$model->errorcode = (string)$sheetData[$baseRow]['M'];
					$model->sub = (string)$sheetData[$baseRow]['N'];
                    $model->save();
                    $baseRow++;
                }
                Yii::$app->getSession()->setFlash('success','Success');
            }else{
                Yii::$app->getSession()->setFlash('error','Error');
            }
        }
		$connection = \Yii::$app->db;
		$datap = $connection->createCommand("
     SELECT auto_id, rep, id, train_id, hn, an, pid, fullname, main, regdate, discharge, ins,pp, errorcode,sub
FROM rep_import
GROUP BY train_id ORDER BY auto_id DESC
     ")->queryAll();

     $importdataProvider = new ArrayDataProvider([
         'allModels' => $datap,
     ]);


	
        return $this->render('imports',[
                'modelImport' => $modelImport,
				'dataimport' => $importdataProvider,
				
            ]);
    }
	}