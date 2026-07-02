<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use kartik\grid\GridView;
use kartik\editable\Editable;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel frontend\modules\opdcard\models\PermitsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'คืนเวชระเบียน';
$this->params['breadcrumbs'][] = $this->title;


?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />

<style>
    .modal-dialog {
        width: 60%;
        max-width: 60%;
        margin: auto;
    }
</style>
<script>
function printCurrentPage() {
    // สร้างตัวแปร content ไว้เก็บเนื้อหาที่ต้องการพิมพ์
    var content = document.getElementById("printable-content").innerHTML;
    // สร้างชุดรายละเอียดการพิมพ์
    var printWindow = window.open('', '', 'height=600,width=800');
    // สร้างเอกสาร HTML ที่จะพิมพ์โดยยกเลิกการแสดง URL ในการพิมพ์
    printWindow.document.write('<html><head><title>Print</title></head><body>' + content + '</body></html>');
    // ปิดการเขียนเอกสาร
    printWindow.document.close();
    // เรียกใช้การพิมพ์
    printWindow.print();
}

</script>
<br>

  
		  <div class="well">
    <p>
         <?= Html::button('<i class="glyphicon glyphicon-plus"></i>เพิ่มข้อมูล', ['value' => Url::to(['permits/create']), 'class' => 'btn btn-success','id'=>'modalButton']); ?>
        <!--<?= Html::a('เพิ่มการยืม', ['create'], ['class' => 'btn btn-success']) ?>-->
        <?= Html::button('เงื่อนไขการยืมคืนเวชระเบียน:', ['class' => 'btn btn-danger']) ?>
        <?= Html::button('กำหนดการคืนเวชระเบียนภายใน 7 วัน', ['class' => 'btn btn-info']) ?>
        
        <!-- <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();"> -->
        
    </p>
</div>


    <?php Modal::begin([
        'id' => 'modal',
        'header' => '<h4>เพิ่มการยืมแฟ้มเวชระเบียน</h4>',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-danger" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
    ?>

    <?php Pjax::begin(); ?>
    <div id="printable-content">
    <?= GridView::widget([
	'tableOptions' => [
    'class' => 'table table-striped',
		],
		'options' => [
			'class' => 'table-responsive',
		],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<b style="color:green">คืนเวชระเบียน</b>',
           // 'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
			[
                'class' => 'kartik\grid\EditableColumn',
				'attribute'=>'AN',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
				'format' =>'raw',
				'label'=>'AN',
				'contentOptions' => ['style' => 'max-width:20px;'],
				'value'=> function ($model){
                    return '<font class="text-defualt">' . $model['AN'] . '</font>';
					// return '<span class="badge" style="background-color:primary">' . $model['AN'] . '</span>';
				}
			],
			[
				'attribute'=>'HN',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
				'format'=>'raw',
				'contentOptions' => ['style' => 'max-width:30px;'],
				'value'=> function ($model){
					return '<font class="text-success">' . $model['HN'] . '</font>';
				}
			],
            [    
                        'attribute' => 'fullname',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
            
            [    
                        'attribute' => 'treatments.treatment_name',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
			[    
                        'attribute' => 'createdBy.firstname',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw', 
			],
			[    
                        'attribute' => 'created_at',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw',
               
			],
			[    
                        'attribute' => 'updater.firstname',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw',
               
			],
			/*
            [    
                        'attribute' => 'updater.lastname',
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw',
               
			],*/
            [    
                        'attribute' => 'updated_at',
						'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       'format'=>'raw',
               
			],
            /*
			[
			    // 'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'status.status',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                'format' => 'raw',
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return '<span class="badge" style="background-color:' . $model->status->color . '">' . $model->status->status . '</span>';
                    
                },
            ],
			*/
			[
			     'class' => 'kartik\grid\EditableColumn',
                'attribute' => 'status_id',
				'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                'format' => 'raw',
                'label' => 'สถานะ',
                'value' => function ($model) {
                    return '<span class="badge" style="background-color:' . $model->status->color . '">' . $model->status->status . '</span>';
                    
                },
            ],
			// เพิ่มคอลัมน์ 'days_diff' ใน 'columns' ของ GridView
[
    'attribute' => 'days_diff',
    'label' => 'จำนวนวันระหว่างยืม-คืน',
    'format' => 'raw',
    'value' => function ($model) {
        // แปลงวันที่ยืมและวันที่คืนเป็นวัตถุ DateTime
        $borrowDate = new DateTime($model->created_at);
        $returnDate = new DateTime($model->updated_at);
        
        // คำนวณจำนวนวันระหว่างวันที่
        $interval = $borrowDate->diff($returnDate);
        
        // นำค่านี้มาแสดงผล
        return $interval->days;
    },
    'contentOptions' => ['style' => 'max-width: 50px;'],
    'headerOptions' => ['style' => 'background-color:#a4e7df'],
],

          ['class' => 'yii\grid\ActionColumn',
           'urlCreator' => function ($action, $model, $key, $index) {
            return Url::to(['scan-batch/view',  'id' => $key, 'id' => $model->id]);
           },
                'header'=>'คลิก',
                
			'headerOptions' => ['style' => 'width:15%'],
                'template'=>'<div class="btn-group btn-group-sm text-center" role="group"> {detail} {edit} {del} </div>',
                'buttons'=>[
				/*
                    'detail' => function($url,$model,$key){
                        return Html::a('ดู',
                            ['mrasum', 'id' => $model->id],
                           // ['class' => 'btn btn-inverse'],
                            ['class' => 'btn btn-info'],
							//['class' => 'btn btn-info', 'id'=>'modalButton'],
                            $url);	     
                    },
                */
                'edit' => function ($url, $model, $key) {
                    return Html::a('รับคืน', ['update', 'id' => $model->id], [
                        'class' => 'btn btn-warning',
                        'data-toggle' => 'modal',
                        'data-target' => '#returnModal',
                        'id' => 'submitReturn'
                    ]);
                },
            ],
        ],
    ],
]);
?>
<div class="modal fade" id="returnModal" tabindex="-1" role="dialog" aria-labelledby="returnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content rounded">
      <div class="modal-header">
        <h5 class="modal-title" id="returnModalLabel">รับคืนข้อมูล</h5>
        <!-- ปุ่มปิด -->
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- เนื้อหาของฟอร์มรับคืนข้อมูล -->
        <form id="returnForm" action="your-return-action" method="post">
          <!-- สามารถเพิ่มฟิลด์ข้อมูลต่างๆ ที่ต้องการรับค่าได้ที่นี่ -->
          <div class="form-group">
            <label for="returnReason">เหตุผลในการรับคืน</label>
            <input type="text" class="form-control" id="returnReason" name="returnReason">
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">ยกเลิก</button>
        <button type="button" class="btn btn-warning" id="submitReturn">รับคืน</button>
      </div>
    </div>
  </div>
</div>

<!-- <?php //Pjax::end() ?> -->
</div>

 <?php
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
<script>
$(document).ready(function(){
    $('#submitReturn').click(function(){
        // ดึงข้อมูลจากฟอร์มรับคืน
        var returnReason = $('#returnReason').val();
        
        // ทำอะไรก็ตามที่คุณต้องการกับข้อมูลที่ดึงมา
        
        // ปิด Modal
        $('#returnModal').modal('hide');
    });
    
    // เพิ่มคลาส modal-lg เมื่อ Modal เปิด
    $('#returnModal').on('show.bs.modal', function (e) {
        $(this).find('.modal-dialog').addClass('modal-lg');
    });
});
</script>






