<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;



$this->title = 'ปีงบ';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
 
             <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>รายงานข้อมูลE-Claim ผู้ป่วยใน ADJRW จากสปสช.แยกตามปีงบประมาณ</<i></div>
                        <div class="panel-body"> 
                            <div>   
                 <?php
                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'จำนวนเคลมและค่าAdjrw ปีงบประมาณ'],
                            'xAxis' => [
                                'categories' => $fiscal4
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'จำนวน']
                            ],
                            'series' => [
                                [
                                    'type' => 'line',
                                    'name' => 'adjrw',
                                    'data' =>$adjrw4,
                                ],
                                [
                                    'type' => 'line',
                                    'name' => 'จำนวน(visits)',
                                    'data' =>$amount4,
                                ]
                            ]
                        ]
                    ]);
                    ?>
                </div>
    
                             <div>
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $f1dataProvider,
                                    'responsive' => true,
                                    'hover' => true,
                                    'panel' => [
                                        'before' => ' ',
                                        'after'=>'<a style="color:#ff6c00">
                                       
                                        </a>' 
                                        ],
                                        'showPageSummary' => true,
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                        ['class' => 'kartik\grid\SerialColumn'],
                                        [
                                            'class'=>'kartik\grid\FormulaColumn',
                                            'label' => 'ปีงบ',
                                            'attribute' => 'FISCAL',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                           
                                        ],
                                        [
                                            'class'=>'kartik\grid\FormulaColumn',
                                            'label' => 'สิทธิการพยาบาล',
                                            'attribute' => 'INSCL_NAME',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> 'รวม',
                                        ],
                                        // [
                                        //     'label' => 'กองทุนย่อย',
                                        //     'attribute' => 'SUB_FUND',
                                        //     //'format' => ['decimal', 0],
                                        //     'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                        //     //'pageSummary'=> true,
                                        // ],
                                        
                                        [
                                            'class'=>'kartik\grid\FormulaColumn',
                                            'label' => 'จำนวนเคลม',
                                            'attribute' => 'AMOUNT',
                                            'format' => ['decimal', 0],
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> true,
                                        ],
                                        [
                                            'class'=>'kartik\grid\FormulaColumn',
                                            'label' => 'ADJRW',
                                            'attribute' => 'ADJRW',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                             'format'=>['decimal', 2],
                                             'pageSummary'=> true,
                                        ],

                                    ],
                                ]);
                                ?>
                             </div>
                            <div>
                         </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>รายงานข้อมูลE-Claim ผู้ป่วยใน ADJRW จากสปสช.แยกตามปีงบประมาณ</<i></div>
                        <div class="panel-body"> 
                            <div>   
                 <?php
                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'จำนวนเคลมและค่าAdjrw ปีงบประมาณ'],
                            'xAxis' => [
                                'categories' =>  $fiscal5
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'จำนวน']
                            ],
                            'series' => [
                                [
                                    'type' => 'column',
                                    'name' => 'adjrw',
                                    'data' =>$adjrw5,
                                ],
                                [
                                    'type' => 'column',
                                    'name' => 'จำนวน(visits)',
                                    'data' =>$amount5,
                                ]
                            ]
                        ]
                    ]);
                    ?>
                </div>
    
                             <div>
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $f2dataProvider,
                                    'responsive' => true,
                                    'hover' => true,
                                    'panel' => [
                                        'before' => ' ',
                                        'after'=>'<a style="color:#ff6c00">
                                       
                                        </a>' 
                                        ],
                                        'showPageSummary' => true,
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                        ['class' => 'kartik\grid\SerialColumn'],
                                        [
                                            'label' => 'ปีงบ',
                                            'attribute' => 'FISCAL',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                           
                                        ],
                                        [
                                            'label' => 'สิทธิการพยาบาล',
                                            'attribute' => 'INSCL_NAME',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> 'รวม',
                                        ],
                                        // [
                                        //     'label' => 'กองทุนย่อย',
                                        //     'attribute' => 'SUB_FUND',
                                        //     //'format' => ['decimal', 0],
                                        //     'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                        //     //'pageSummary'=> true,
                                        // ],
                                        
                                        [
                                            'label' => 'จำนวนเคลม',
                                            'attribute' => 'AMOUNT',
                                            'format' => ['decimal', 0],
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> true,
                                        ],
                                        [
                                            'label' => 'ADJRW',
                                            'attribute' => 'ADJRW',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                             'format'=>['decimal', 2],
                                             'pageSummary'=> true,
                                        ],

                                    ],
                                ]);
                                ?>
                            </div>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>
