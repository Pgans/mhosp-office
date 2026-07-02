<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;
use \miloschuman\highcharts\Highcharts;

$this->title = 'LOG EPIDEM' ;
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>

 <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
<?php
echo GridView::widget([
        'dataProvider' => $logepidemProvider,
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
                        'attribute' => 'id',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       
                    ],
					[
                        'attribute' => 'visit_id',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       
                    ],
                    [
                        'attribute' => 'pid',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                       
                    ],
                    
                    [
                        'attribute' => 'status',
                        'label'=>'สถานะ',
                    'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    [
                        'attribute' => 'messagecode',
                        'label'=>'Error',
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                        'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='2E86C1'>" . $model['messagecode'] . "</font>"; 
                    }, 
                    ],
                    [
                        'attribute' => 'response',
                        'label'=>'response',
                    'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
					[
                        'attribute' => 'users',
                        'label'=>'ผู้ส่ง',
                    'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
					[
                        'attribute' => 'd_update',
                        'label'=>'วันที่รับบริการ',
                    'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    ]
                    ]);
                    
                      ?>
                      
                      
                    </div>
                    
                 

                   