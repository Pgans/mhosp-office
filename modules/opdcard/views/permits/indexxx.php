<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;




/* @var $this yii\web\View */
/* @var $searchModel app\models\awardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'xxxx';
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="award-index">

<div class="panel panel-danger">
                    <div class="panel-heading"><h3><i class="glyphicon glyphicon-user"></i> ระบบการบันทึกรางวัลAwards</h3></div>
                    <div class="panel-body">
                    <div class="row">
    
   <p>
    <?= Html::button('เพิ่มข้อมูลรางวัล', ['value'=>Url::to(['permits/create']), 'class' =>'btn btn-success btn-lg','id'=>'modalButton']); ?> 
   </p>
   <!-- <p>
        <?= Html::a('เพิ่มแจ้งซ่อมคอมพิวเตอร์', ['create'], ['class' => 'btn btn-lg btn-success']) ?>
  </p> -->

   <?php Modal::begin([
'id' => 'modal',
'header' => '<h4><a color-blue>CREATE PERMITS</a></h4>',
'size'=>'modal-lg',
'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">ปิด</a>'
]);
echo "<div id='modalContent'></div>";

Modal::end();
?>

    <?php Pjax::begin() ?>
  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
         'id',
            'AN',
            'HN',
            'fullname',
            'treatments.treatment_name',
             'createdBy.firstname',
            // 'createdBy.lastname',
             //'created_at',
            // 'day_want',
             //'updater.firstname',
             //'updated_at',
            // 'status.status',

           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end() ?>
</div>



 <!-- <?php
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>  -->

<?php
  $this->registerJs("$(function() {
   $('#modalButton').click(function(e) {
     e.preventDefault();
     $('#modal').modal('show').find('.modal-content')
     .load($(this).attr('value'));
   });
});");
?> 
