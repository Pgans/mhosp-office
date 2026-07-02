<?php
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;



$this->title = 'ไตรมาส1';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
 
             <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>รายงานข้อมูลE-Claim ผู้ป่วยใน ADJRW จากสปสช.ไตรมาส1</<i></div>
                        <div class="panel-body"> 
                            <div>   
                 <?php
                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'จำนวนเคลมและค่าAdjrw ไตรมาส1'],
                            'xAxis' => [
                                'categories' => $name
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'จำนวน(คน)']
                            ],
                            'series' => [
                                [
                                    'type' => 'column',
                                    'name' => 'ผลรวมadjrw',
                                    'data' =>$adjrw,
                                ],
                                [
                                    'type' => 'column',
                                    'name' => 'จำนวน(visits)',
                                    'data' =>$amount,
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
                                    'dataProvider' => $t1dataProvider,
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
                                            'label' => 'สิทธิการพยาบาล',
                                            'attribute' => 'INSCL_NAME',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> 'รวม',
                                        ],
                                        [
                                            'label' => 'กองทุนย่อย',
                                            'attribute' => 'SUB_FUND',
                                            //'format' => ['decimal', 0],
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            //'pageSummary'=> true,
                                        ],
                                        
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
                                             'format'=>['decimal', 4],
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
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>รายงานข้อมูลE-Claim ผู้ป่วยใน ADJRW จากสปสช.ไตรมาส2</<i></div>
                        <div class="panel-body"> 
                            <div>   
                 <?php
                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'จำนวนเคลมและค่าAdjrw ไตรมาส2'],
                            'xAxis' => [
                                'categories' => $name2
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'จำนวน(คน)']
                            ],
                            'series' => [
                                [
                                    'type' => 'column',
                                    'name' => 'ผลรวมadjrw',
                                    'data' =>$adjrw2,
                                ],
                                [
                                    'type' => 'column',
                                    'name' => 'จำนวน(visits)',
                                    'data' =>$amount2,
                                ]
                            ]
                        ]
                    ]);
                    ?>
                </div>
    
                             <div>
                                <?php
                               // use kartik\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $t2dataProvider,
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
                                       // ['class' => 'yii\grid\SerialColumn'],
                                       ['class' => 'kartik\grid\SerialColumn'],
                                        
                                        [
                                            'class'=>'kartik\grid\FormulaColumn',
                                            'label' => 'สิทธิการพยาบาล',
                                            'attribute' => 'INSCL_NAME',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> 'รวม',
                                        ],
                                        [
                                            'class'=>'kartik\grid\FormulaColumn',
                                            'label' => 'กองทุนย่อย',
                                            'attribute' => 'SUB_FUND',
                                            //'format' => ['decimal', 0],
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            //'pageSummary'=> true,
                                        ],
                                        
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
                                             'format'=>['decimal', 4],
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
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i>รายงานข้อมูลE-Claim ผู้ป่วยใน ADJRW จากสปสช.ไตรมาส3</<i></div>
                        <div class="panel-body"> 
                            <div>   
                 <?php
                    echo Highcharts::widget([
                        'options' => [
                            'title' => ['text' => 'จำนวนเคลมและค่าAdjrw ไตรมาส2'],
                            'xAxis' => [
                                'categories' => $name3
                            ],
                            'yAxis' => [
                                'title' => ['text' => 'จำนวน(คน)']
                            ],
                            'series' => [
                                [
                                    'type' => 'column',
                                    'name' => 'ผลรวมadjrw',
                                    'data' =>$adjrw3,
                                ],
                                [
                                    'type' => 'column',
                                    'name' => 'จำนวน(visits)',
                                    'data' =>$amount3,
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
                                    'dataProvider' => $t3dataProvider,
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
                                       // ['class' => 'yii\grid\SerialColumn'],
                                       ['class' => 'kartik\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'สิทธิการพยาบาล',
                                            'attribute' => 'INSCL_NAME',
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            'pageSummary'=> 'รวม',
                                        ],
                                        [
                                            'label' => 'กองทุนย่อย',
                                            'attribute' => 'SUB_FUND',
                                            //'format' => ['decimal', 0],
                                            'headerOptions'=>['style'=>'background-color:#FADBD8'],
                                            //'pageSummary'=> true,
                                        ],
                                        
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
                                             'format'=>['decimal', 4],
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

     <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
                    
                  
                    
                   