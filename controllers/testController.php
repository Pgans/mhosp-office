<?php



namespace app\modules\consumers\controllers;


use Yii;

use arogachev\excel\import\basic\Importer;

use yii\web\UploadedFile;

use PHPExcel;

use PHPExcel_IOFactory;

use yii\helpers\Html;

use app\modules\consumers\models\Building;

use app\modules\consumers\models\BuildingManager;

use app\modules\consumers\models\BuildingCategory;

use app\models\AuthAssignment;

use app\models\City;

use app\models\State;

use app\models\Zone;

use app\models\User;


class BuildingImportController extends \yii\web\Controller

{

    public function actionIndex()

    {

		$model = new Building();		

		$model->scenario = 'import-building';

		$importResults = [];


		if ($model->load(Yii::$app->request->post())) {			

			$model->importFile = UploadedFile::getInstance($model, 'importFile');

			if($model->saveImportFile()) {

				$importResults = $this->importBuildingData($model);

				//print_r($importResults); exit;

			}			

		}


        return $this->render('index', [

			'model' => $model,

			'importResults' => $importResults,

		]);

    }




	public function importBuildingData($model)

	{

		$dispResults = []; 

		$totalSuccess = 0;

		

		$objPHPExcel = PHPExcel_IOFactory::load($model->importFilePath.$model->importFile);

		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, true);

		

		//print_r($sheetData); exit;

		unset($sheetData[1]);


		//start import building row by row

		foreach($sheetData as $k => $line){

			//print_r($line); exit;

			if(!array_filter($line))

				continue;

				 

			$line = array_map('trim', $line);

			$line = array_map(function($value) { return empty($value) ? NULL : $value; }, $line);

			

			$buildingMaster = new Building();

			$buildingMaster->scenario = 'import-building';


			//set building info attributes

			$buildingMaster->last_inspection = Yii::$app->dateformatter->getDateFormat($line['A']); //Last Inspection Date

			$buildingMaster->building_name = $line['B']; //Building Name 

//			$buildingMaster->corporation_name = $line['C']; //Corporation Name

			$buildingMaster->address = $line['C']; //Address

			$buildingMaster->status = $this->valueReplace($line['D'], $buildingMaster->getBuildingStatus()); // Building Status

			$buildingMaster->corporation_type = $this->valueReplace($line['E'], $buildingMaster->getCorporationTypes()); // Corporation Types

			$buildingMaster->supply_rating = $line['F']; //Supply Rating




			

			//set building master attribute

			$buildingMaster->city = $this->valueReplace($line['L'], City::getAllCity()); //City

			$buildingMaster->cat_id = $this->valueReplace($line['M'], BuildingCategory::getBuildingCategoryId()); //Building Category

			$buildingMaster->manager_id = $this->valueReplace($line['N'], BuildingManager::getBuildingManager()); //Building Manager


			if($buildingMaster->save())


			//set building info attribute

			$buildingManager->corporation_name = $line['O']; //Corporation Name

		return ['dispResults' => $dispResults, 'totalSuccess' => $totalSuccess];

	}


	protected function valueReplace($value, $arrayData)

    {

    	if(empty($value) || empty($arrayData)) {

    		return null;

    	} else {

    		$key = array_search(strtolower($value), array_map('strtolower', $arrayData));

    		return ($key) ? $key : NULL;

    	}    

    }

	

	public function actionDownloadFile($id)

    {

    	$path = null;

		

    	if($id=='SSF') {

    			$path = Yii::getAlias('@webroot').'/data/import_files/building_import_en.xlsx';

    	}

    

		if(file_exists($path)) {

			return \Yii::$app->response->sendFile($path);

		}

			throw new NotFoundHttpException('The requested file does not exist.');	

    }

}



