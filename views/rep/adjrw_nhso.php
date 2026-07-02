<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;




$this->title = 'Adjrw';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<?php

echo GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim IPCS ADJRW จากสปสช.  ประจำเดือน</a> '.date('Y-m'),
            'after'=>'ประมวลผลปีงบประมาณ2563 จาก 1 ตุลาคม2562  ถึง  30 กันยายน 2563',
            ],
            'showPageSummary' => true,
            'columns' => [
                   // ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'attribute' => 'HCODE',
                        'label'=>'รหัส รพ.',
                        'headerOptions'=>[ 'style'=>'background-color:#D2B4DE'] ,
                       'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['HCODE'] . "</font>"; 
                },       
                    ],
                    [
                        'attribute' => 'INSCL_NAME',
                        'label'=>'สิทธิการพยาบาล',
                       'headerOptions'=>[ 'style'=>'background-color:#D2B4DE'] ,
                       
                    ],
                    
                    [
                        'attribute' => 'MAININSCL',
                        'label'=>'รหัสสิทธิ์',
                    'headerOptions'=>[ 'style'=>'background-color:#D2B4DE'] ,
                    ],
                    [
                        'attribute' => 'SUB_FUND',
                        'label'=>'กองทุนย่อย',
                       'headerOptions'=>[ 'style'=>'background-color:#D2B4DE'] ,
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
                        'headerOptions'=>[ 'style'=>'background-color:#D2B4DE'],
                        'pageSummary' => true,
                    ],//FF9C33  42E908  
                     [
                         'attribute' => 'ADJRW',
                         'format'=>'raw',
                         'headerOptions'=>['style'=>'background-color:#D2B4DE'],
                         'format'=>['decimal', 4],
                         'pageSummary'=> true,
                     ],
                    ]
                    ]);
                      ?>
                       <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
                       <?php echo Html::a('Adjrwไตรมาส1', ['rep/trimas'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']); ?>
                       <?php echo Html::a('ผลงานClaim-Adjrw_nhso', ['rep/fiscal'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']); ?> 
                    </div>
                    
                   