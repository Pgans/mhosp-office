<?php
/* @var $this yii\web\View */

use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
//use dosamigos\datepicker\DatePicker;

$this->title = 'กลุ่มยาลดปวดกล้ามเนื้ออักเสบ';

$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->params['breadcrumbs'][] = 'กลุ่มยาลดปวดกล้ามเนื้ออักเสบ';
?>
<!--<p><?= Html::a('<i class="fa fa-reply"></i> ย้อนกลับ', ['/thaimed'], ['class' => 'btn btn-info']) ?> </p>-->
<b style="color:blue">กลุ่มยาลดปวดกล้ามเนื้ออักเสบ</b>
<div class='well'>

    <?php $form = ActiveForm::begin(); ?>
     ระหว่างวันที่:
           <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date1',
            'value' => $date1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
        ถึง:
           <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date2',
            'value' => $date2,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
        <button class='btn btn-danger'> ตกลง </button>
    <?php ActiveForm::end(); ?>
  
<?php Pjax::begin(); ?>
	</div>
</div>
<div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> 'กลุ่มยาลดปวดกล้ามเนื้ออักเสบ  ยาจากสมุนไพร</<i></div>
                <div class="panel-body">
<?php
 $gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
                        'attribute' => 'drug_id',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'รหัสยา.',
                    ],
					[
                        'attribute' => 'drug_name',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ชื่อยา',
						'pageSummary' => 'รวมทั้งหมด',
                    ],
					[
                        'attribute' => 'visits',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ครั้ง',
						'format' => ['decimal', 0],
						'pageSummary' => true,
                    ],
					[
                        'attribute' => 'amount',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'จำนวนยา',
						'format' => ['decimal', 0],
						'pageSummary' => true,
                    ],
					[
                        'attribute' => 'unit_packing',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'หน่วยนับ',
                    ],
					[
                        'attribute' => 'drugcost',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ราคาต้นทุน',
						
                    ],
					[
                        'attribute' => 'total',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ราคารวม',
						'format' => ['decimal', 0],
						'pageSummary' => true,
                    ],
					
				];
				echo GridView::widget([
					 'tableOptions' => [
					'class' => 'table table-striped table-hover',
					'width'=>'100%',
					'cellspacing'=> '0'
					],
					'dataProvider' => $dataProvider,
					
					'panel' => [
						'before'=>'<b style="color:blue ">ยาจากสมุนไพร</b>',
						'after'=>'<a>ประมวลผลจากวันที่</a> '.$date1   .'<a>ถึงวันที่</a>' .$date2 
						],
					'autoXlFormat' => true,
					'export' => [
						'fontAwesome' => true,
						'showConfirmAlert' => false,
						'target' => GridView::TARGET_BLANK
					],
					'columns' => $gridColumns,
					'resizableColumns' => true,
					 'showPageSummary' => true,
						//'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
				]);
				?>
       
			</div>
		</div>
	</div>
<div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> 'กลุ่มยาลดปวดกล้ามเนื้ออักเสบ  ยาแผนปัจจุบัน</<i></div>
                <div class="panel-body">
		<?php
		$gridColumns = [
        ['class' => 'kartik\grid\SerialColumn'],
        [
                        'attribute' => 'drug_id',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'รหัสยา.',
                    ],
					[
                        'attribute' => 'drug_name',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ชื่อยา',
						'pageSummary' => 'รวมทั้งหมด',
                    ],
					[
                        'attribute' => 'visits',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ครั้ง',
						'format' => ['decimal', 0],
						'pageSummary' => true,
                    ],
					[
                        'attribute' => 'amount',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'จำนวนยา',
						'format' => ['decimal', 0],
						'pageSummary' => true,
                    ],
					[
                        'attribute' => 'unit_packing',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'หน่วยนับ',
                    ],
					[
                        'attribute' => 'drugcost',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ราคาต้นทุน',
						
                    ],
					[
                        'attribute' => 'total',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'header' => 'ราคารวม',
						'format' => ['decimal', 0],
						'pageSummary' => true,
                    ],
					
				];
				echo GridView::widget([
					 'tableOptions' => [
					'class' => 'table table-striped table-hover',
					'width'=>'100%',
					'cellspacing'=> '0'
					],
					'dataProvider' => $nowProvider,
					
					'panel' => [
						'before'=>'<b style="color:blue ">ยาแผนปัจจุบัน</b>',
						'after'=>'<a>ประมวลผลจากวันที่</a> '.$date1   .'<a>ถึงวันที่</a>' .$date2 
						],
					'autoXlFormat' => true,
					'export' => [
						'fontAwesome' => true,
						'showConfirmAlert' => false,
						'target' => GridView::TARGET_BLANK
					],
					'columns' => $gridColumns,
					'resizableColumns' => true,
					 'showPageSummary' => true,
						//'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
				]);
				?>
         <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>
    </div>