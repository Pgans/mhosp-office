<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Lsubfund;
use app\models\Msumsubfund;
use kartik\select2\Select2;


$this->title = 'rep E-Claim';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading"><i class="fa fa-search"></i> ค้นหาRep เลือกตามปี เดือน </div>
            <div class="panel-body">

             
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim แยกตามREP  ประจำเดือน</a> '.date('Y-m'),
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
            'showPageSummary' => true,
            'columns' => [
                   // ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn'],
                 /*   [
                        //'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'SUB_FUND',
                        'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       // 'label'=>'REP',611100035
                       'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['rep'] . "</font>"; 
                },       
                    ],*/
                    
                    [
                        'attribute' => 'SUB_FUND',
                        'label'=>'กองทุนย่อย',
                       'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       //'format'=>'raw',
                       // 'value' => function ($model, $key, $index, $widget) {
                        //    return "<font  color='FF9C33'>" . $model['sub_fund'] . "</font>"; 
                   // }, 
                    //   'pageSummary'=> 'รวม',
                    ],
                    [
                        'attribute' => 'AMOUNT',
                        'label'=>'จำนวน',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    //'format'=> 'raw',
                    //'value' => function($model) {
                     //   $subfund = $model['sub_fund'];
                     //   $amount = $model['amount'];
                     //   return Html::a(Html::encode($amount), ['rep/rep_list','subfund' => $subfund],['target'=>'_blank']);
                    //},
                    //'pageSummary'=> true,
                    ],
                    ]
                    ]);
                    
                      ?>
                       
             <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
              <!-- <?php echo Html::a('ส่งการเงิน', ['rep/rep2'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']); ?>
              <?php echo Html::a('ผลงานเคลม', ['rep/rep3'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']); ?>   -->
            
              </div>
            </div>

            <div class="alert alert-danger"><?=$sql?> </div>
                    
                   