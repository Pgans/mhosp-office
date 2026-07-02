<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;


/* @var $this yii\web\View */

$this->title = 'M30hospital(045489064)';
?>


 <div class="panel-body">
    <div class="panel panel-warning" >
        <div class="panel-heading" id="grad02" style="color: white;"><i class="glyphicon glyphicon-plus-sign"></i> ระบบรายงานส่งซ่อมหน่วยซ่อมบำรุง</<i></div>
        <div class="panel-body">          
<div class="col-md-6">
<div class="panel panel-danger">
<div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการส่งซ่อมหน่วยซ่อมบำรุงทั้งหมด แยกตามปีงบประมาณ</<i></div>
<div class="panel-body">
<div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_topopd">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal , COUNT(k.fiscal ) as 'amount'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
									,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
									FROM jobservice a
							WHERE a.dateline BETWEEN '2018-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal

							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['amount'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_topopd').highcharts({
                            colors: ['#CC99FF', '#C0C0C0', '#B9D300', '#FFFF99', '#99CC00', '#FF9900', '#33CCCC', '#FF99CC', '#808000', '#FF0000', '#FFCC00', '#993366', '#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการส่งซ่อมหน่วยซ่อมบำรุงทั้งหมด'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                         </div>
                   </div>
              </div>
		<div class="col-md-6">
        <div class="panel panel-danger">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการซ่อมครุภัณฑ์ทางการแพทย์</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_hardware">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 2 ) THEN 1 END )  AS   'ครุภัณฑ์ทางการแพทย์'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal

							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['ครุภัณฑ์ทางการแพทย์'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_hardware').highcharts({
                            colors: ['#FF0000', '#FFCC00', '#993366', '#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูล:ซ่อมครุภัณฑ์ทางการแพทย์'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                  </div>
             </div>
         </div>
    <div>
	
	 <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลซ่อมเครื่องปรับอากาศ</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_mbase">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 4 ) THEN 1 END )  AS   'เครื่องปรับอากาศ'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal

							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['เครื่องปรับอากาศ'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_mbase').highcharts({
                            colors: ['#FFCC00', '#993366', '#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลซ่อมเครื่องปรับอากาศ'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                    </div>
                    </div>
                    </div>
                    </div>
                   
    <div class="col-md-6">
        <div class="panel panel-info">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการซ่อมโครงสร้าง อาคาร สถานที่</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_internet">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 5 ) THEN 1 END )  AS   'โครงสร้าง อาคาร สถานที่'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal
							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['โครงสร้าง อาคาร สถานที่'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_internet').highcharts({
                            colors: [ '#CC99FF','#993366','#99CC00', '#FF9900', '#33CCCC' ,'#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'ซ่อมโครงสร้าง อาคาร สถานที่'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                    </div>
                    </div>
                    </div>

 <div>
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการระบบประปา ระบบบำบัด</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_server">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 6 ) THEN 1 END )  AS   'ระบบประปา ระบบบำบัด'
						FROM (
						SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
						,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
						FROM jobservice a
						LEFT  JOIN jobtype b ON a.type_id = b.id
						WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
						GROUP BY k.fiscal

						";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['ระบบประปา ระบบบำบัด'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_server').highcharts({
                            colors: ['#99CC00', '#FF9900', '#33CCCC','#99CC00', '#993366', '#000080'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการซ่อมระบบประปา ระบบบำบัด'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                    </div>
                    </div>
                    </div>
                    </div>
                    
    <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลซ่อมวัสดุอุปกรณ์ทั่วไป</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_camera">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 7 ) THEN 1 END )  AS   'วัสดุอุปกรณ์ทั่วไป'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal
							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['วัสดุอุปกรณ์ทั่วไป'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_camera').highcharts({
                            colors: [ '#CC99FF','#993366','#99CC00', '#FF9900', '#33CCCC', '#000080'],
                            chart: {
                            type: 'pie',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลวัสดุอุปกรณ์ทั่วไป'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                   </div>
                 </div>
               </div>
			   
			 <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลยานพาหนะ</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="chart_ink">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 8 ) THEN 1 END )  AS   'ยานพาหนะ'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal
							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    'y' => $data['ยานพาหนะ'] * 1,
                                ];
                                $main = json_encode($main_data);
                                ?>

                                <?php
                                $this->registerJs("$(function () {
                        $('#chart_ink').highcharts({
                            colors: [ '#CC99FF', '#FF9900', '#33CCCC', '#FF99CC', '#808000', '#FF0000'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลประเภทยานพาหนะ'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                   </div>
                 </div>
               </div>

 <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลระบบไฟฟ้า</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="comsod">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 9 ) THEN 1 END )  AS   'ระบบไฟฟ้า'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal
							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    //'y' => $data['Software'] * 1,
                                    'y' => intval($data['ระบบไฟฟ้า']),
                                  // 'y' => [intval($data['Total']),intval($data['mBase'])],

                                ];
                                $main = json_encode($main_data);
                                ?>
                                <?php
                                $this->registerJs("$(function () {
                        $('#comsod').highcharts({
                            colors: [ '#CC99FF','#993366', '#000080','#FF99CC', '#808000', '#FF0000'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลระบบไฟฟ้า'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                    </div>
				 </div>
		  </div>
					
			 <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> รายการข้อมูลทั้งหมด</<i></div>
                <div class="panel-body">
                     <?=
            GridView::widget([
                'dataProvider' => $dataProvider,
                'columns' =>[
				       [
                        'attribute' => 'fiscal',
                        'label' => 'ปีงบประมาณ'
                    ],
                    [
                        'attribute' => '2',
                        'label' => 'ครุภัณฑ์ทางการแพทย์'
                    ],
                    [
                        'attribute' => '4',
                        'label' => 'เครื่องปรับอากาศ'
                    ],
                    [
                        'attribute' => '5',
                        'label' => 'โครงสร้าง อาคาร สถานที่'
                    ],
                    [
                        'attribute' => '6',
                        'label' => 'ระบบประปา ระบบบำบัด'
                    ],
                    [
                        'attribute' => '7',
                        'label' => 'วัสดุอุปกรณ์ทั่วไป'
                    ],
					[
                        'attribute' => '8',
                        'label' => 'ยานพาหนะ'
                    ],
					[
                        'attribute' => '9',
                        'label' => 'ระบบไฟฟ้า'
                    ],
					[
                        'attribute' => '10',
                        'label' => 'ครุภัณฑ์สำนักงาน'
                    ],
					[
                        'attribute' => '11',
                        'label' => 'ครุภัณฑ์ไฟฟ้า'
                    ],
					[
                        'attribute' => '12',
                        'label' => 'ระบบสื่อสาร'
                    ],
					[
                        'attribute' => 'xx',
                        'label' => 'อื่นๆ'
                    ],
                ]
            ])
            ?>
                            <div>
                      </div>
                   </div>
                 </div>
               </div>

 <div class="col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading"><i class="glyphicon glyphicon-list-alt"></i> xxxxx</<i></div>
                <div class="panel-body">
                    <div style="display: none">
                                <?php
                                echo Highcharts::widget([
                                    'scripts' => [
                                        'highcharts-more', // enables supplementary chart types (gauge, arearange, columnrange, etc.)
                                    //'modules/exporting', // adds Exporting button/menu to chart
                                    //'themes/grid', // applies global 'grid' theme to all charts
                                    'highcharts-3d',
                                    'modules/drilldown'
                                    ]
                                ]);
                                ?>
                            </div>
                            <div id="comsod">
                            </div>
                             <?php
                            $sql = "SELECT k.fiscal, COUNT(CASE WHEN(k.type_id= 9 ) THEN 1 END )  AS   'ระบบไฟฟ้า'
							FROM (
							SELECT a.id, a.detail, a.dateline, a.send_by, a.repair_by , a.repair_service,  a.jstatus_id, a.type_id, a.dep_id
							,IF(MONTH(a.dateline)>9, YEAR(a.dateline)+544, YEAR(a.dateline)+543) AS fiscal
							FROM jobservice a
							LEFT  JOIN jobtype b ON a.type_id = b.id
							WHERE a.dateline BETWEEN '2019-10-01' AND NOW() )  as k 
							GROUP BY k.fiscal
							";
                            $rawData = Yii::$app->db->createCommand($sql)->queryAll();
                            $main_data = [];
                            foreach ($rawData as $data) {
                                $main_data[] = [
                                    'name' => $data['fiscal'],
                                    //'y' => $data['Software'] * 1,
                                    'y' => intval($data['ระบบไฟฟ้า']),
                                  // 'y' => [intval($data['Total']),intval($data['mBase'])],

                                ];
                                $main = json_encode($main_data);
                                ?>
                                <?php
                                $this->registerJs("$(function () {
                        $('#comsod').highcharts({
                            colors: [ '#CC99FF','#993366', '#000080','#FF99CC', '#808000', '#FF0000'],
                            chart: {
                            type: 'column',
                            margin: 75,
                            options3d: {
                                enabled: true,
                                alpha: 21,
                                beta: 15,
                                depth: 70
                            }
                            },
                            title: {
                                text: 'รายการข้อมูลระบบไฟฟ้า'
                            },
                            xAxis: {
                                type: 'category'
                            },
                            yAxis: {
                                title: {
                                    text: '<b>จำนวน</b>'
                                },
                            },
                            legend: {
                                enabled: true
                            },
                            plotOptions: {
                                series: {
                                    borderWidth: 0,
                                    dataLabels: {
                                        enabled: true
                                    }
                                }
                            },
                            series: [
                            {
                                name: 'ปีงบประมาณ',
                                colorByPoint: true,
                                data:$main

                            }
                            ],

                        });
                    });");
                             ?>
                             <?php } ?>
                            <div>
                      </div>
                    </div>
				
					