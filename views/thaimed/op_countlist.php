<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\data\ArrayDataProvider;
use common\models\RContributionIpd;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\export\ExportMenu;

$this->title ="opcount";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
?>
<div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"><h5><i class="glyphicon glyphicon-list-alt"></i> หัตถการแพทย์แผนไทยmBase</h5></div>
                        <div class="panel-body">
                          
        <div>
                                <?php
                                //use yii\grid\GridView;
                                echo GridView::widget([
                                    'dataProvider' => $inData,
                                    'responsive' => true,
                                    'hover' => true,
                                    'panel' => [
                                        'before' => ' ',
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'panel' => [
                                        'before'=>'<b style="color:blue ">หัตถการแพทย์แผนไทย</b>(<b style="color: red">mBase</b>)',
                                        'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
                                    ],
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'label' => 'เดือน',
                                            'attribute' => 'MONTH'
                                        ],
                                        // [
                                        //     'attribute' => 'acupencture',
                                        //     'label' => 'ฝังเข็ม',
                                        //     'format' => ['decimal', 0]
                                        // ],
                                        [
                                            'attribute' => 'nursing',
                                            'label' => 'บริบาล',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'massage',
                                            'label' => 'การนวด',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'baked',
                                            'label' => 'อบ',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'compression',
                                            'label' => 'ประคบ',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'songserm',
                                            'label' => 'ส่งเสริม',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'Total',
                                            'label' => 'รวม',
                                            'format' => ['decimal', 0]
                                        ], 
                                    ],
                                ]);
                                ?>
                            </div>
                            <div class="kv-panel-pager">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading"><h5><i class="glyphicon glyphicon-list-alt"></i>หัตถการแพทย์แผนไทยส่งออก43แฟ้ม</h5></div>
                        <div class="panel-body">
                        
                    
        <div>
                                <?php
                                 echo GridView::widget([
                                    'dataProvider' => $iproceData,
                                    'responsive' => true,
                                    'hover' => true,
                                    'panel' => [
                                        'before' => ' ',
                                    ],
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'panel' => [
                                        'before'=>'<b style="color:blue ">ส่งออกหัตถการแพทย์แผนไทย</b>(<b style="color: red">43F</b>)',
                                        'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
                                    ],
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'label' => 'เดือน',
                                            'attribute' => 'MONTH'
                                        ],
                                        // [
                                        //     'attribute' => 'ฝังเข็ม',
                                        //     'label' => 'ฝังเข็ม',
                                        //     'format' => ['decimal', 0]
                                        // ],
                                        [
                                            'attribute' => 'บริบาล',
                                            'label' => 'บริบาล',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'การนวด',
                                            'label' => 'การนวด',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'อบ',
                                            'label' => 'อบ',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'ประคบ',
                                            'label' => 'ประคบ',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'ส่งเสริม',
                                            'label' => 'ส่งเสริม',
                                            'format' => ['decimal', 0]
                                        ],
                                        [
                                            'attribute' => 'Total',
                                            'label' => 'รวม',
                                            'format' => ['decimal', 0]
                                        ], 
                                    ],
                                ]);
                                
                                // echo GridView::widget([
                                //     'dataProvider' => $oproceData,
                                //     'responsive' => true,
                                //     'hover' => true,
                                //     'panel' => [
                                //         'before' => ' ',
                                //     ],
                                //     'pjax' => true,
                                //     'pjaxSettings' => [
                                //         'neverTimeout' => true,
                                //     ],
                                //     'panel' => [
                                //         'before'=>'<b style="color:blue ">ส่งออกหัตถการแพทย์แผนไทย43F</b>(<b style="color: red">นอก</b>)',
                                //         'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
                                //     ],
                                //     'columns' => [
                                //         ['class' => 'yii\grid\SerialColumn'],
                                //         [
                                //             'label' => 'เดือน',
                                //             'attribute' => 'MONTH'
                                //         ],
                                //         [
                                //             'attribute' => 'ฝังเข็ม',
                                //             'label' => 'ฝังเข็ม',
                                //             'format' => ['decimal', 0]
                                //         ],
                                //         [
                                //             'attribute' => 'บริบาล',
                                //             'label' => 'บริบาล',
                                //             'format' => ['decimal', 0]
                                //         ],
                                //         [
                                //             'attribute' => 'การนวด',
                                //             'label' => 'การนวด',
                                //             'format' => ['decimal', 0]
                                //         ],
                                //         [
                                //             'attribute' => 'อบ',
                                //             'label' => 'อบ',
                                //             'format' => ['decimal', 0]
                                //         ],
                                //         [
                                //             'attribute' => 'ประคบ',
                                //             'label' => 'ประคบ',
                                //             'format' => ['decimal', 0]
                                //         ],
                                //         [
                                //             'attribute' => 'ส่งเสริม',
                                //             'label' => 'ส่งเสริม',
                                //             'format' => ['decimal', 0]
                                //         ],
                                //         [
                                //             'attribute' => 'Total',
                                //             'label' => 'รวม',
                                //             'format' => ['decimal', 0]
                                //         ], 
                                //     ],
                                // ]);

                                ?>
                            </div>
                            <div class="kv-panel-pager">
                            </div>
                        </div>
                    </div>
                </div>
                        


                 

               