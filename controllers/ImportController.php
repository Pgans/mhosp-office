<?php

namespace app\controllers;

use Yii;
use app\models\Import;
use app\models\ImportSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
//use vendor\yiisoft\yii2\data\ArrayDataProvider;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;

/**
 * ImportController implements the CRUD actions for Import model.
 */
class ImportController extends Controller
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
     * Lists all Import models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ImportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
	###########################################
	public function actionView($id)
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


        $dataProvider = new ArrayDataProvider([
            'allModels' => $arr,
            'pagination' => false,
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
  
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
     */
    public function actionCreate()
    {
        $model = new Import();
		
		 if ($model->load(Yii::$app->request->post())) {

            $model->file = $model->uploadFile($model, 'file');
            $model->save();

            Yii::$app->session->setFlash('warning', 'เพิ่มข้อมูลเรียบร้อยแล้ว');
            return $this->redirect(['index', 'id' => $model->id]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

       /* if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }*/

    
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
		
		 if ($model->load(Yii::$app->request->post())) {

            $model->file = $model->uploadFile($model, 'file');
            $model->save();

            Yii::$app->session->setFlash('success', 'แก้ไขข้อมูลเรียบร้อยแล้ว');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }


        /*if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }*/

   
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Import::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
