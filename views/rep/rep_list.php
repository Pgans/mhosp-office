<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'Rep-List';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/index']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ป่วยในDischarge';

// Assuming today is March 10th, 2001, 5:16:18 pm, and that we are in the
// Mountain Standard Time (MST) Time Zone

?>
                 <p align="center">รายงานข้อมูลการเคลมตามกองทุน  </p>';
 <p>
	<input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();"> 
           <?php echo Html::a('ส่งการเงิน', ['rep/rep2'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']); ?>
           <?php echo Html::a('ผลงานเคลม', ['rep/rep3'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']); ?>           
            
 </p>
    
<?=  GridView::widget([
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    /*'panel' => [
        'before'=>'<a>รายงานข้อมูลE-Claim แยกตามREP  ประจำเดือน</a> '.date('Y-m'),
        'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
        ],*/
        'showPageSummary' => true,
        'columns' => [
               // ['class' => 'yii\grid\SerialColumn'],
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    //'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'REP',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                   // 'label'=>'REP',611100035
                   'format'=>'raw',
                   'value' => function ($model, $key, $index, $widget) {
                    return "<font  color='2E86C1'>" . $model['REP'] . "</font>"; 
            },       
                ],
                [
                    'attribute' => 'HN',
                   'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                   
                ],
                
                [
                    'attribute' => 'DATEADM',
                    'label'=>'วันที่รับบริการ',
                'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                ],
                [
                    'attribute' => 'FULLNAME',
                    'label'=>'ชื่อ-สกุล',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    'format'=>'raw',
                    'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['FULLNAME'] . "</font>"; 
                }, 
                ],
                [
                    'attribute' => 'SUB_FUND',
                    'label'=>'กองทุนย่อย',
                   'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                   'format'=>'raw',
                    'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='FF9C33'>" . $model['SUB_FUND'] . "</font>"; 
                }, 
                   'pageSummary'=> 'รวม',
                ],
                [
                   // 'class' => 'kartik\grid\EditableColumn',
                    'attribute' => 'SUMS_SERVICEITEM',
                    'label'=>'เรียกเก็บ',
                    'format'=>'raw',
                   // 'value' => function ($model, $key, $index, $widget) {
                      //  return "<font  color='FF9C33'>" . $model['SUMS_SERVICEITEM'] . "</font>"; 
               // }, 
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    'pageSummary' => true,
                    'format'=>['decimal',0]
                ],//FF9C33  42E908  
                [
                    'attribute' => 'TOTL_AMT',
                    'label'=>'ชดเชย',
                'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                'pageSummary'=> true,
                'format'=>['decimal',0]
                ],
                [
                    'attribute' => 'ACT_AMT',
                    'label'=>'ชดเชย2',
                'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                'pageSummary'=> true,
                'format' => ['decimal',0] 
                ],
                ]
                ]);
             
                echo '<p align = "center">....................................</p> ';
                 echo '<p align = "center">นางสายใจ   บุญทา</p> ';
                 echo '<p align="center">ตำแหน่งเจ้าพนักงานเวชสถิติปฏิบัติงาน</p>';
  
                
                  ?>   
          
        
      </div>
        

        <!-- <div class="alert alert-danger"><?=$sql?> </div> -->
