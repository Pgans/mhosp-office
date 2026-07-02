<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;




$this->title = 'ไตรมาส3';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<?php

echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim ผู้ป่วยใน ADJRW จากสปสช.  ประจำเดือน</a> '.date('Y-m'),
            'after'=>'ประมวลผลปีงบประมาณ2563 จาก 1 มิถุนายน 2563  ถึง  30 กันยายน 2563',
            ],
            'showPageSummary' => true,
            'columns' => [
                   // ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'HCODE',
                        'label'=>'รหัส รพ.',
                        'headerOptions'=>[ 'style'=>'background-color:#FADBD8'] ,
                       'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['HCODE'] . "</font>"; 
                },       
                    ],
                    [
                        'attribute' => 'INSCL_NAME',
                        'label'=>'สิทธิการพยาบาล',
                       'headerOptions'=>[ 'style'=>'background-color:#FADBD8'] ,
                       
                    ],
                    
                    [
                        'attribute' => 'MAININSCL',
                        'label'=>'รหัสสิทธิ์',
                    'headerOptions'=>[ 'style'=>'background-color:#FADBD8'] ,
                    ],
                    [
                        'attribute' => 'SUB_FUND',
                        'label'=>'กองทุนย่อย',
                       'headerOptions'=>[ 'style'=>'background-color:#FADBD8'] ,
                       'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='FF9C33'>" . $model['SUB_FUND'] . "</font>"; 
                    }, 
                       'pageSummary'=> 'รวม',
                    ],
                    [
                       // 'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'AMOUNT',
                        'label'=>'จำนวน(ราย)',
                        'format'=>'raw',
                        'headerOptions'=>[ 'style'=>'background-color:#FADBD8'],
                        'pageSummary' => true,
                    ],//FF9C33  42E908  
                     [
                         'attribute' => 'ADJRW',
                         'format'=>'raw',
                         'headerOptions'=>['style'=>'background-color:#FADBD8'],
                         'format'=>['decimal', 4],
                         'pageSummary'=> true,
                     ],
                    ]
                    ]);
                      ?>
                       <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
                    
                    </div>
                    
                   