<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;




$this->title = 'IPCS';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<?php

echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim IPCS ADJRW จากสปสช.  ประจำเดือน</a> '.date('Y-m'),
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
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
                        'attribute' => 'SUB_FUND',
                        'label'=>'กองทุนย่อย',
                       'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
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
                        'headerOptions'=>[ 'style'=>'background-color:#a4e7df'],
                        'pageSummary' => true,
                    ],//FF9C33  42E908  
                     [
                         'attribute' => 'ADJRW_NHSO',
                         'format'=>'raw',
                         'headerOptions'=>['style'=>'background-color:#a4e7df'],
                         'pageSummary'=> true,
                     ],
                     [
                         'attribute'=>'DRG_NHSO',
                         'format'=>'raw',
                         'headerOptions'=>['style'=>'background-color:#a4e7df'],
                     ],
                    ]
                    ]);
                      ?>
                       <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
                    </div>
                    
                   