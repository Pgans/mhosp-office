<?php 
	use yii\widgets\ActiveForm;
	use yii\helpers\Html;
	use yii\grid\GridView;
	//use kartik\grid\GridView;

$this->title = 'นำเข้าไฟล์REP';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-info box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<h4>รองรับไฟล์ .xls, xlsx</h4>
<?php $form = ActiveForm::begin(['options'=>['enctype'=>'multipart/form-data']]);?>

<?= $form->field($modelImport,'fileImport')->fileInput() ?>

<?= Html::submitButton('บันทึกการนำเข้าไฟล์',['class'=>'btn btn-success']);?>

<?php ActiveForm::end();?>

</div>

<div class="repimport-index">
<div class="box box-primary box-solid">
<div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i>รายการข้อมูลนำเข้าแล้ว</div>
          <div class="box-body">

    

    <?php
		echo GridView::widget([
        'dataProvider' =>$dataimport,
       // 'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

           // 'auto_id',
            'rep',
            //'id',
            'train_id',
            'hn',
            'an',
            'pid',
            'fullname',
            'main',
            'regdate',
           // 'discharge',
            'ins',
            //'pp',
            //'errorcode',
            'sub',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
