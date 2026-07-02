<?php
/* @var $this yii\web\View */

use yii\helpers\Url;
//use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Breadcrumbs;
use yii\bootstrap\Html;
use kartik\grid\GridView;
use yii\db\Query;
use yii\jui\Tabs;
use kartik\tabs\TabsX;
use yii\i18n\Formatter;
use app\models\Logehr;
$formatter = new Formatter();

?>
<head>
<style>
            body {
                font-family: 'Kanit', sans-serif;
            }

            h1 {
                font-family: 'Kanit', sans-serif;
            }

            h2 {
                font-family: 'Kanit', sans-serif;
            }

            h3 {
                font-family: 'Kanit', sans-serif;
            }

            h4 {
                font-family: 'Kanit', sans-serif;
            }

            h5 {
                font-family: 'Kanit', sans-serif;
            }

            div {
                font-family: 'Kanit', sans-serif;
            }

            /* a {
                color: #009587;
            } */

            h5.thick {
                font-weight: bold;
            }
	 #grad0 {
	  background-image: linear-gradient(to right, pink, cyan);
	}
	#grad1 {
	  background-image: linear-gradient(to right, indigo, cyan);
	}
	#grad001 {
	  background-image: linear-gradient(to right, green, yellow);
	}
	#grad4 {
	  background-image: linear-gradient(to right, red,orange,yellow,green,blue,indigo,violet);
	}
	#grad2 {
	  background-image: linear-gradient(to right, cyan, yellow);
	}
	#grad5 {
	  background-image: linear-gradient(180deg, red, yellow);
	}
	#grad6 {
	  background-image: linear-gradient(180deg, violet, cyan);
	}
	#grad7 {
	  background-image: linear-gradient(180deg, blue, cyan);
	}
	#grad01 {
	  background-image: linear-gradient(to right, green , cyan);
	}
	#grad {
	  background: red; /* For browsers that do not support gradients */
	  background: -webkit-linear-gradient(left,rgba(255,0,0,0),rgba(255,0,0,1)); /*Safari 5.1-6*/
	  background: -o-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); /*Opera 11.1-12*/
	  background: -moz-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); /*Fx 3.6-15*/
	  background: linear-gradient(to right, rgba(255,0,0,0), rgba(255,0,0,1)); /*Standard*/
	}
	#grad11 {
		height: 55px;
		background: -webkit-linear-gradient(left, red, orange, yellow, green, blue, indigo, violet); /* For Safari 5.1 to 6.0 */
		background: -o-linear-gradient(left, red, orange, yellow, green, blue, indigo, violet); /* For Opera 11.1 to 12.0 */
		background: -moz-linear-gradient(left, red, orange, yellow, green, blue, indigo, violet); /* For Fx 3.6 to 15 */
		background: linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet); /* Standard syntax (must be last) */
	}
    .table-hover tbody tr:hover{
    background-color: #f7c0ba;
    }
	 .table-hover1 tbody tr:hover{
    background-color: #CCE9FB;
    }
	 .table-hover2 tbody tr:hover{
    background-color: #B3F7CE;
    }
</style>
</head>
<h1><p align="center" ><a style="blue"> MuangSamSib (EHR)</a></p></h1>
  	
 <div class="row">
          <div class="col-md-8" id="drad01">
             <a style="color:brown">ข้อมูลประจำวันที่</a>  <?php echo date('Y-m-d H:i:s');?>    
			 <p><a >จำนวนผู้ใช้งานทั้งหมด</a> <?php echo dektrium\user\models\User::find()->count(); ?> user  <br>
             <a>จำนวนการเข้าใช้งาน</a>  <?=$model = Logehr::find()->count();?> ครั้ง<br>
			 <MARQUEE behavior=alternate direction=left scrollAmount=3 width="4%"><font face=Webdings>3</font></MARQUEE><MARQUEE scrollAmount=1 direction=left width="2%">| | |
			 </MARQUEE><a>ผู้กำลังเข้าใช้งาน</a> <?php echo \Yii::$app->user->identity->username; ?><MARQUEE scrollAmount=1 direction=right width="2%">| | |
			 </MARQUEE><MARQUEE behavior=alternate direction=right scrollAmount=3 width="4%"><font face=Webdings>4</font></MARQUEE>>
			 <!--<a>ผู้กำลังเข้าใช้งาน</a> <?php echo \Yii::$app->user->identity->username; ?> <br>-->
			 <a>IP:</a> <?php echo \Yii::$app->request->getUserIP(); ?> <br> 
			 <MARQUEE class=TextArea onmouseover=this.stop() onmouseout=this.start() scrollAmount=1 scrollDelay=70 width=500><a>***ระบบมีการเก็บLogเข้าใช้งานและจะทำการLogOutอัตโนมัติเมื่อไม่ใช้งาน 15 นาที***</a> </marquee>
			

			<!-- <a>xxxxx</a> <?= 
			  $sql = 'SELECT * FROM log_ehr lg  where lg.datetime between curdate() and now()';
              $model = Logehr::findBySql($sql)->all();?>
			  <br>
			<a><คุณกำลังใช้IP:> <? echo \Yii::$app->request->getUserIP();?>
              <a>***ระบบมีการเก็บLoggเข้าใช้งานและจะทำการLogOut อัตโนมัติเมื่อไม่ใช้งานต่อเนื่อง 20 นาที***</a>        
			
				<!--
                    <span class="info-box-text">USER</span>
                    <span class="info-box-number pull-left badge bg-green">
                      <?php echo dektrium\user\models\User::find()->count(); ?>
                    </span> -->
               
			</div>
			
 	
 <div class="row">
    <div class="col-md-3">
	 
    <?= GridView::widget([
        'dataProvider' => $dataProviderweb,
        //'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

           // 'id',
            [
                        'attribute' => 'regdate',
                        'label'=>'วันที่',
                    'headerOptions'=>[ 'style'=>'background-color:#FD6502'] ,
            ],
			[
                        'attribute' => 'users',
                        'label'=>'ผู้ใช้งาน',
                    'headerOptions'=>[ 'style'=>'background-color:#FD6502'] ,
            ],
			[
                        'attribute' => 'cid',
                        'label'=>'เข้าดู',
                    'headerOptions'=>[ 'style'=>'background-color:#FD6502'] ,
            ],
            //'cusers',
           //'cid',
           // ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
			</div>  
	

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading"id="grad1"><i class="fa fa-search"></i>MuangSamSib (EHR)  ค้นหาผู้ป่วย</div>
            <div class="panel-body">
			
                <?= Html::beginForm(); ?>

                <label for="pwd">เลขบัตรประชาชน 13 หลัก : &nbsp;&nbsp; </label>
                <input type="text"  name="cid"  placeholder="">
                &nbsp;&nbsp;<button class='btn btn-danger'> <i class="glyphicon  glyphicon-search"></i>ค้นหา</button>
                <?= Html::endForm(); ?>
				<?php if ($cid <> '') { ?>

 <div class="row">
         <!-- <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading"><i class="fa fa-id-card-o"></i>&nbsp;&nbsp;ข้อมูลบุคคล</div>
                <div class="panel-body">
           -->
                    <?php
                    if ($sex == '1') {
                        $ipath = Yii::$app->request->baseUrl . '/images/men.jpg';
                    } else {
                        $ipath = Yii::$app->request->baseUrl . '/images/women.jpg';
                    }
                    ?>
                    <div class="row" >
                        <div class="col-md-2">
                            <img src="<?= $ipath ?>" class="img-circle" alt="User Image" height="60" width="80" >
                        </div>
                        <div class="col-md-7">
                            <p><a> ชื่อ-สกุล  :</a> <?= $tname ?>  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <a>HN::</a> <?=$hn?> 
                            <p> <a>ที่อยู่ :</a> <?= $taddr ?> 
                             <a>วันเกิด :</a> <?= $formatter->asDate($birth) ?> &nbsp;&nbsp;&nbsp;&nbsp;
                             <button class="badge btn-primary"> Tel: </button><?= $telephone ?> </p>
                             <p> <span class="badge btn-warning"> แพ้ยา :</span> <font color="red"><?= $allergy ?> </font></p>
                        </div>
                    </div>
                </div>
    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading" id="grad1" ><i class="fa fa-calendar-check-o"></i>&nbsp;&nbsp;วันที่รับบริการ</div>
                <div class="panel-body">
                    <?php
                    $gridColumns = [
			
                            ['class' => 'kartik\grid\SerialColumn'],
			        
                            [
                            'attribute' => 'tdate',
                            'label' => 'วันมารับบริการ',
                            'value' => function ($model, $key, $index, $widget) {
                                if ($model['tadmit'] === 'N') {
                                    return "<font  color='000000'>" . $model['tdate'] . "</font>";
                                } else {
                                    return "<font  color='ff0066'>" . $model['tdate'] . "</font>";
                               
                                }
                            },
                            'filterType' => GridView::FILTER_COLOR,
                            'vAlign' => 'middle',
                            'format' => 'raw',
                            'width' => '150px',
                            'noWrap' => true
                        ],
			 
                            [
                            'attribute' => 'hospcode',
                            'label' => 'สถาน',
                            'value' => function($model, $key) {
                                return Html::a($model['hospcode'], ['/ehr', 'hospcode' => $model['hospcode'],
                                            'pid' => $model['pid'],
                                            'an' => $model['an'],
                                            'seq' => $model['seq']], ['title' => $model['hospname'],
                                ]);
                            },
                            'filterType' => GridView::FILTER_COLOR,
                            'hAlign' => 'center',
                            'format' => 'raw',
			    'noWrap' => true
                        ],
			[
                            'attribute' => 'lab',
                            'label' => 'lab',
			    'value' => function ($model, $key, $index, $widget) {
                               if ($model['lab'] === 'N') {
                                    return "<font  color='000000'>" . $model['lab'] . "</font>";
                               } else {
                                    return "<font  color='ff9900'>" . $model['lab'] . "</font>";
                               }
                    	    },
			   'filterType' => GridView::FILTER_COLOR,
                            'vAlign' => 'middle',
                            'format' => 'raw',
                            'width' => '150px',
                            'noWrap' => true
                        ],
                    
		[
                            'attribute' => 'dru',
                            'label' => 'dru',
			    'value' => function ($model, $key, $index, $widget) {
                               if ($model['dru'] === 'N') {
                                    return "<font  color='000000'>" . $model['dru'] . "</font>";
                               } else {
                                    return "<font  color='ff9900'>" . $model['dru'] . "</font>";
                               }
                    	    },
			   'filterType' => GridView::FILTER_COLOR,
                            'vAlign' => 'middle',
                            'format' => 'raw',
                            'width' => '150px',
                            'noWrap' => true
                        ]
                    ];
			
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        //'filterModel' => $searchModel,
                        'autoXlFormat' => true,
                        'export' => [
                            'fontAwesome' => true,
                            'showConfirmAlert' => false,
                            'target' => GridView::TARGET_BLANK
                        ],
                        'columns' => $gridColumns,
                        'resizableColumns' => true,
                        'resizeStorageKey' => Yii::$app->user->id . '-' . date("m"),
                            //'floatHeader' => true,
                            //'floatHeaderOptions' => ['scrollingTop' => '100'],
                            /* 'pjax' => true,
                              'pjaxSettings' => [
                              'neverTimeout' => true,
                              //'beforeGrid' => 'My fancy content before.',
                              //'afterGrid' => 'My fancy content after.',
                              ] */
                    ]);
                    ?>



                </div>
            </div>
        </div>
    <?php } ?>    
    <?php if ($hospcode <> '') { ?>    
        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading" id="grad01"><i class="fa fa-th-large"></i>&nbsp;&nbsp; รายละเอียด</div>
                <div class="panel-body">
                    <?php
                    echo TabsX::widget([
                        'position' => TabsX::POS_ABOVE,
                        'align' => TabsX::ALIGN_LEFT,
                        'items' => [
                                [
                                'label' => 'อาการ/วินิจฉัย',
                                'content' => $this->render('diag', [
                                    'dataProvider' => $dataProvideri,
                                    'dateserv' => $dateserv,
                                       'cc'=>$cc,
                                       'sbp'=>$sbp,
                                       'dbp'=>$dbp,
                                       'pr'=>$pr,
                                       'rr'=>$rr,
                                       'btemp'=>$btemp,
                                       'timeserv'=>$timeserv,
                                       'hospname'=>$hospname,
                                       'hospcode'=>$hospcode,
                                    
                                ]),
                                'active' => true
                            ],
                                [
                                'label' => 'ยา',
                                'content' => $this->render('drug', [
                                        'dataProvider' => $dataProviderdr,
                                ]),
                            ],
                                [
                                'label' => 'Lab',
                                'content' => $this->render('lab', [
                                    'dataProvider' => $dataProviderl,
                                ]),
                            ],
                              [
                              'label' => 'หัตถการ',
                              'content' => $this->render('procedure', [
                              //'searchModel' => $searchModel,
                              'dataProvider' => $dataProviderproce,
                              ]),
                            ], 
                             [
                              'label' => 'ข้อมูลการนัด',
                              'content' => $this->render('m30_appoints', [
                              //'searchModel' => $searchModel,
                              'dataProvider' => $dataProviderapp,
                              ]),
                            ], 
							 [
                              'label' => 'วัคซีน',
                              'content' => $this->render('vaccine', [
                              //'searchModel' => $searchModel,
                              'dataProvider' => $dataProvidervac,
                              ]),
                            ], 
                            //     [
                            //     'label' => 'ANC',
                            //     'content' => "รออัพเดท",
                            //     'headerOptions' => ['style' => 'font-weight:bold'],
                            //     'options' => ['id' => 'myveryownID'],
                            // ],
                        /* [
                          'label' => 'Dropdown',
                          'items' => [
                          [
                          'label' => 'DropdownA',
                          'content' => 'DropdownA, Anim pariatur cliche...',
                          ],
                          [
                          'label' => 'DropdownB',
                          'content' => 'DropdownB, Anim pariatur cliche...',
                          ],
                          ],
                          ], */
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>
 
<?php } ?>


<?php
$this->registerJs('');
?>


