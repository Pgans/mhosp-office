<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use common\models\RContributionIpd;
use yii\data\ActiveDataProvider;



$this->title = 'SURGEON_OPERATON';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ';
?>
<br>
<b><a>รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ</a></b>
<div class='well'>
    <?php $form = ActiveForm::begin(); ?>
     ระหว่างวันที่:
           <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date1',
            'value' => $date1,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
        ถึง:
           <?php
        echo yii\jui\DatePicker::widget([
            'name' => 'date2',
            'value' => $date2,
            'language' => 'th',
            'dateFormat' => 'yyyy-MM-dd',
            'clientOptions' => [
                'changeMonth' => true,
                'changeYear' => true,
            ]
        ]);
        ?>
        <button class='btn btn-danger'> ตกลง </button>
        <?php $form = ActiveForm::begin([ ]);
    echo Html::a('แยกเดือน', ['thaimed/op_count'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']);
    echo Html::a('เปรียบเทียบ(ใน-นอกสถานบริการ)', ['thaimed/surgeon_inout'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']);
    // echo Html::a('ตรวจรหัสหัตถการmBase', ['thaimed/check_operation'], ['class' => 'btn btn-primary', 'id'=>'modalButton','target'=>'_blank']);
  //echo Html::a('ตรวจรหัสหัตถการ43แฟ้ม', ['thaimed/check_procudure'], ['class' => 'btn btn-warning', 'id'=>'modalButton','target'=>'_blank']);
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>
<div>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:blue">รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ(ในสถานบริการ)</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                  
                    [
                        'attribute' => 'Provider',
                        'header' => 'ชื่อผู้ทำ',
                    ],
                    [
                        'attribute' => 'SURGEON_ID',
                        'header' => 'รหัส',
                    ],
                    [
                        'attribute' => 'ฝังเข็ม',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['ฝังเข็ม'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_acupencture','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    [
                        'attribute' => 'บริบาล',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['บริบาล'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_nursing','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    [
                        'attribute' => 'การนวด',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['การนวด'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_massage','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    [
                        'attribute' => 'อบ',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['อบ'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_baked','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    [
                        'attribute' => 'ประคบ',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['ประคบ'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_compression','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    [
                        'attribute' => 'ส่งเสริม',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['ส่งเสริม'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_songserm','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    [
                        'attribute' => 'Total ',
                        'format' => 'raw',
                        'value' => function($model) {
                            $surgeonid = $model['SURGEON_ID'];
                            $total = $model['Total'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_operation_list','surgeonid'=> $surgeonid],['target'=>'_blank']);
                        }
                            ],
                    ]
                ]
                    );
                    
                    ?>
                </div>
                <div class="alert alert-info"><?=$sql?> </div>
        