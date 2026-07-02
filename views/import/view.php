<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Import */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Imports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
 <!--<div class="box box-primary box-solid">
 <div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">-->
				<?=GridView::widget([
					'dataProvider' => $dataProvider,
					'columns' => [
						[
							//'label' => 'rep',
							'value' => function($model){
								return $model[0][0];
							}
						],
						[
							//'label' => 'auto_id',
							'value' => function($model){
								return $model[0][1];
							}
						],
						[
							//'label' => 'train',
							'value' => function($model){
								return $model[0][2];
							}
						],
						[
							//'label' => 'hn',
							'value' => function($model){
								return $model[0][3];
							}
						],
						[
							//'label' => 'an',
							'value' => function($model){
								return $model[0][4];
							}
						],
						[
							//'label' => 'pid',
							'value' => function($model){
								return $model[0][5];
							}
						],
						[
							//'label' => 'fullname',
							'value' => function($model){
								return $model[0][6];
							}
						],
						[
							//'label' => 'main',
							'value' => function($model){
								return $model[0][7];
							}
						],
						[
							//'label' => 'regdate',
							'value' => function($model){
								return $model[0][8];
							}
						],
						[
							//'label' => 'discharge',
							'value' => function($model){
								return $model[0][10];
							}
						],
						[
							//'label' => 'discharge',
							'value' => function($model){
								return $model[0][11];
							}
						],
						[
							//'label' => 'discharge',
							'value' => function($model){
								return $model[0][13];
							}
						],
					]
				])?>