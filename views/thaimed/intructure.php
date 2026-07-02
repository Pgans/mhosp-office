<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'INTRUCTURE';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->params['breadcrumbs'][] = 'รายงานนับผู้สั่งทำหัตถการในโรงพยาบาล';
?>
<br>
<b><a>รายงานนับผู้สั่งทำหัตถการในโรงพยาบาล</a></b>
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
        
    <?php ActiveForm::end(); ?>
</div>
<?php //echo $sql;?>
<?php
//return $this->redirect(array('report/dsc_list', ['date1' => $date1, 'date1' => $date2]));
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'รายงานผู้สั่งทำหัตการในโรงพยาบาล',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                  
                    [
                        'attribute' => 'STAFF_ID',
                        'header' => 'รหัสจนท.',
                    ],
                    [
                        'attribute' => 'FNAME',
                        'header' => 'ชื่อ',
                    ],
                    [
                        'attribute' => 'LNAME',
                        'header' => 'สกุล',
                    ],
                    [
                        'attribute' => 'จำนวนทำหัตถการ(ครั้ง)',
                        'format' => 'raw',
                        'value' => function($model) {
                            $staffid = $model['STAFF_ID'];
                            $amount = $model['AMOUNT'];
                            return Html::a(Html::encode($amount), ['thaimed/intructure_list','staffid' => $staffid],['target'=>'_blank']);
                        }
                            ],
                  ]
                ]
                    );
                    
                    ?>
                    
                    <div class="alert alert-danger"><?=$sql?> </div>
