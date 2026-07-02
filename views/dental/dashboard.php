<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\tabs\TabsX;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;
use yii\bootstrap4\LinkPager;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;

/* @var $this yii\web\View */

$this->title = 'Dashboard';
?>
    <div class="panel-body">
    <div class="panel panel-primary">
        <div class="panel-heading" id="grad1"><i class="glyphicon glyphicon-plus"></i> Dashboard</<i></div>
        <div class="panel-body">
<!-- ####################################Start Replication######### -->
     <div class="row">
                <div class="col-md-4">
                    <div class="panel panel-info">
                        <div class="panel-heading" id="grad2"><i class="glyphicon glyphicon-user"></i> SLAVE192.168.200.14</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data14Provider,
                                    'responsive' => true,
                                  // 'hover' => true,
                                    
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'tables',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'จำนวน',
                                            'attribute' => 'amount',
                                            'format' => ['decimal', 0]
                                        ],
                                    ],
									'layout' => '{items}{pager}',
                                ]);
                                ?>
                            </div>
                          
                            </div></div></div>
                <div class="col-md-4">
                    <div class="panel panel-warning">
                        <div class="panel-heading" id=grad2><i class="glyphicon glyphicon-user"></i> SLAVE192.168.200.70</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data70Provider,
                                    'responsive' => true,
                                  // 'hover' => true,
                                    
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'tables',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'จำนวน',
                                            'attribute' => 'amount',
                                            'format' => ['decimal', 0]
                                        ],
                                        

                                    ],
									'layout' => '{items}{pager}',
                                ]);
                                ?>
                            </div>
                            </div></div></div>
                            <div class="col-md-4">
                    <div class="panel panel-success">
                        <div class="panel-heading" id=grad2><i class="glyphicon glyphicon-user"></i>MASTER 192.168.200.7</<i></div>
                        <div class="panel-body">
                            <div>
                       
                                <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $data7Provider,
                                    'responsive' => true,
                                  // 'hover' => true,
                                    
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'แฟ้ม',
                                            'attribute' => 'tables',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'จำนวน',
                                            'attribute' => 'amount',
                                            'format' => ['decimal', 0]
                                        ],
                                    ],
									'layout' => '{items}{pager}',
                                ]);
                                ?>
                            </div>
                            </div></div></div></div></div>

<!-- ####################################END Replication######### -->
<!-- ################################# Start Authen Code############# -->

                <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading"id= grad0><i class="glyphicon glyphicon-user"></i> AuthenCode</<i></div>
                        <div class="panel-body">
                            <div>
                            <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'responsive' => true,
                                  // 'hover' => true,
                                    
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'วันที่',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'regdate',
                                            'format' => 'raw',
                                           'value'=> function ($model){
                                            return '<font class="text-primary">' . $model['regdate'] . '</font>';
                                        },
                                        ],
                                        [
                                            'label' => 'กายภาพ',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'กายภาพ',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'ไตเทียม',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'ไตเทียม',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => ' SI',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'ARI OP',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => ' HI',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'ARI HI',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        

                                    ],
									'layout' => '{items}{pager}',
                                ]);
                                ?>
                            </div>
                            </div>
                            </div>
                            </div>
    
                   <div class="row">
                     <div class="col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading" id= grad0><i class="glyphicon glyphicon-user"></i> Admitt IPD</<i></div>
                        <div class="panel-body">
                            <div>
                            <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $admitProvider,
                                    'responsive' => true,
                                  // 'hover' => true,
                                    
                                  
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'วันที่',
                                            'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'admit_date',
                                           'format' => 'raw',
                                           'value'=> function ($model){
                                            return '<font class="text-primary">' . $model['admit_date'] . '</font>';
                                        },
                                        ],
                                        [
                                            'label' => 'LR',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'LR',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'Ward1',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'Ward1',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'Ward2',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'Ward2',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'Ward3',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'Ward3',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'Ward4',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'Ward4',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'HomeI',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'HI',
                                           // 'format' => ['decimal', 0]
                                        ],
                                        [
                                            'label' => 'รวม',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'TOTAL',
                                            'format' => 'raw',
                                            'value'=> function ($model){
                                                return '<span class="badge" style="background-color:#FF9900">' . $model['TOTAL'] . '</span>';
                                            }
                                        ],
                                        
                                    ],
									'layout' => '{items}{pager}',
                                ]);
                                ?>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            
         <!-- ########################### -->
         <!-- ################################# Start Authen Code############# -->
         <!-- <div class="row">
    <div class="panel panel-success">
        <div class="panel-heading"><i class="glyphicon glyphicon-plus"></i> รายงานการฉีดวัคซีน ศูนย์ตรวจสุขภาพชุมชน โรงพยาบาลม่วงสามสิบ</<i></div>
        <div class="panel-body">
            <div class="row"> -->
                <!-- <div class="col-md-6"> -->
                    <div class="panel panel-primary">
                        <div class="panel-heading"id= grad1><i class="glyphicon glyphicon-user"></i> รายงานการฉีดวัคซีน ย้อนหลัง 7 วัน<i></div>
                        <div class="panel-body">
                            <div>
                            <table class="table table-striped" width="450" border="0" align="center" cellspacing="0" >
        <thead>
        <tr>
            <td rowspan="2" class="icon bg-green" width="110"> <div align="center">วันฉีด</div></td>
            <td colspan="3"class="icon bg-teal" width="91"> <div align="center">โมเดอร์นา</div></td>
            <td colspan="3" class="icon bg-blue" width="91"> <div align="center">แอสตรา</div></td>
            <td colspan="3" class="icon bg-purple" width="91"> <div align="center">ซิโนฟาร์ม</div></td>
            <td colspan="3" class="icon bg-teal" width="91"> <div align="center">ไฟเซอร์5-11ปี</div></td>
            <td colspan="3" class="icon bg-primary" width="91"> <div align="center">ไฟเซอร์2</div></td>
            <td rowspan="2" class="icon bg-green" width="91"> <div align="center">รวม</div></td>
        </tr>
        <tr>
            <td align="center" class="icon bg-teal">1</td>
            <td align="center" class="icon bg-teal">2</td>
            <td align="center" class="icon bg-teal">3</td>
            <td align="center" class="icon bg-blue">1</td>
            <td align="center" class="icon bg-blue">2</td>
            <td align="center" class="icon bg-blue">3</td>
            <td align="center" class="icon bg-purple">1</td>
            <td align="center" class="icon bg-purple">2</td>
            <td align="center" class="icon bg-purple">3</td>
            <td align="center" class="icon bg-teal">1</td>
            <td align="center" class="icon bg-teal">2</td>
            <td align="center" class="icon bg-teal">3</td>
            <td align="center" class="icon bg-primary">1</td>
            <td align="center" class="icon bg-primary">2</td>
            <td align="center" class="icon bg-primary">3</td>
        </tr>
            </thead> 
        <?php
        //while($objResult = mysql_fetch_array($vaccineProvider))
        foreach($vaccineProvider->getModels() as $key => $value):
        ?>
        <tr>
            <td align="center"><?=$value["regdate"];?></td>
            <td align="center"><?=$value["moderna1"];?></td>
            <td align="center"><?=$value["moderna2"];?></td>
            <td align="center"><?=$value["moderna3"];?></td>
            <td align="center"><?=$value["astra1"];?></td>
            <td align="center"><?=$value["astra2"];?></td>
            <td align="center"><?=$value["astra3"];?></td>
            <td align="center"><?=$value["sinopharm1"];?></td>
            <td align="center"><?=$value["sinopharm2"];?></td>
            <td align="center"><?=$value["sinopharm3"];?></td>
            <td align="center"><?=$value["pfizer1"];?></td>
            <td align="center"><?=$value["pfizer2"];?></td>
            <td align="center"><?=$value["pfizer3"];?></td>
            <td align="center"><?=$value["pfizer21"];?></td>
            <td align="center"><?=$value["pfizer22"];?></td>
            <td align="center"><?=$value["pfizer23"];?></td>
            <td align="center"><span class="btn btn-xs btn-primary"><?= $value["total"];?></span></td>       
        </tr>
         <?php endforeach; ?>
        </table>
                            </div>
                         </div>
                    </div>
             
        <div class="row">
                     <div class="col-md-4">
                    <div class="panel panel-primary">
                        <div class="panel-heading" id= grad1><i class="glyphicon glyphicon-user"></i>รายงานการส่งข้อมูล Epidem Covid กองระบาดวิทยา</<i></div>
                        <div class="panel-body">
                            <div>
                            <?php
                                //use yii\grid\GridView;

                                echo GridView::widget([
                                    'dataProvider' => $epidemProvider,
                                    'responsive' => true,
                                  // 'hover' => true,                                 
                                    'pjax' => true,
                                    'pjaxSettings' => [
                                        'neverTimeout' => true,
                                    ],
                                    'columns' => [
                                       // ['class' => 'yii\grid\SerialColumn'],
                                        
                                        [
                                            'label' => 'วันที่',
                                            'contentOptions' => ['style' => 'text-overflow: ellipsis; white-space: nowrap; max-width: 25vw; overflow: hidden;'],
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'send_date',
                                           'format' => 'raw',
                                           'value'=> function ($model){
                                            return '<font class="text-primary">' . $model['send_date'] . '</font>';
                                        },
                                        ],
                                          
                                        [
                                            'label' => 'จำนวนส่งผ่าน',
                                            'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                                            'attribute' => 'amount',
                                            'format' => 'raw',
                                            'value'=> function ($model){
                                                return '<span class="btn btn-xs btn-success" style="background-color:#FF00FF">' . $model['amount'] . '</span>';
                                            }
                                        ],
                                
                                    ],
									'layout' => '{items}{pager}',
                                ]);
                                ?>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                            </div>
                                                 
                            
                            
                            
                   
                            
         