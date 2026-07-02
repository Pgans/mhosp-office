<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;
use \miloschuman\highcharts\Highcharts;

$this->title = 'ส่งการเงิน'  .$subfund;
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>

 <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
      //  'filterModel' => $searchModel,
       /* 'panel' => [
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
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       // 'label'=>'REP',611100035
                       'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['REP'] . "</font>"; 
                },       
                    ],
                    [
                        'attribute' => 'HN',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       
                    ],
					[
                        'attribute' => 'MAININSCL',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       
                    ],
                    [
                        'attribute' => 'TRAN_ID',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       
                    ],
                    
                    [
                        'attribute' => 'DATEADM',
                        'label'=>'วันที่รับบริการ',
                    'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    [
                        'attribute' => 'FULLNAME',
                        'label'=>'ชื่อ-สกุล',
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='2E86C1'>" . $model['FULLNAME'] . "</font>"; 
                    }, 
                    ],
                    
                    [
                       // 'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'SUMS_SERVICEITEM',
                        'label'=>'เรียกเก็บ',
                        'format'=>'raw',
                       // 'value' => function ($model, $key, $index, $widget) {
                          //  return "<font  color='FF9C33'>" . $model['SUMS_SERVICEITEM'] . "</font>"; 
                   // }, 
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'pageSummary' => true,
                        'format'=>['decimal',0]
                    ],//FF9C33  42E908  
                    // [
                    //     'attribute' => 'TOTL_AMT',
                    //     'label'=>'ชดเชย',
                    // 'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    // 'pageSummary'=> true,
                    // ],
                    // [
                    //     'attribute' => 'ACT_AMT',
                    //     'label'=>'ชดเชย2',
                    // 'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    // 'pageSummary'=> true,
                    // ],
                    ]
                    ]);
                    
                      ?>
                       <?php
                echo '<p align = "center">....................................</p> ';
                 echo '<p align = "center">นางสายใจ   บุญทา</p> ';
                 echo '<p align="center">ตำแหน่งเจ้าพนักงานเวชสถิติปฏิบัติงาน</p>';
                ?>
                      
                    </div>
                    
                 

                   