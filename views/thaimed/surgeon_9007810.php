<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;


$this->title = 'SURGEON_9007810';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ(9007810)';
?>
<br>
<b><a>รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ(9007810)</a></b>
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
    echo Html::a('ทั้งหมด', ['thaimed/surgeon_9007810all'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']);
    echo Html::a('แยกเดือน', ['thaimed/surgeon_9007810month'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']);
    // echo Html::a('ผู้ทำหัตถการ', ['thaimed/surgeon_inout'], ['class' => 'btn btn-primary', 'style' => 'margin-left:5px','target'=>'_blank']);
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
            'before'=>'<b style="color:blue">รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ(9007810)</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                  
                    [
                        'attribute' => 'INSCL',
                        'header' => 'รหัส',
                    ],
                    [
                        'attribute' => 'INSCL_NAME',
                        'header' => 'สิทธิ์',
                    ],
                    
                    [
                        'attribute' => 'AMOUNT ',
                        'header' => 'จำนวน',
                        'format' => 'raw',
                        'value' => function($model) {
                            $inscl = $model['INSCL'];
                            $total = $model['AMOUNT'];
                            return Html::a(Html::encode($total), ['thaimed/surgeon_9007810_list','inscl'=> $inscl],['target'=>'_blank']);
                        }
                            ],
                    ]
                ]
                    );
                    
                    ?>
                </div>
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>
