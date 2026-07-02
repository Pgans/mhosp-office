<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;


$this->title = 'A15er';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
//$this->params['breadcrumbs'][] = 'การบริบาลหญิงหลังคลอดด้วยวิธีการทับหม้อเกลือ';
?>
<br>
        <b><a>รายงานผู้ป่วย A150-A182 ทีมารับบริการ ER</a></b>
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
    
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>
<div>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:blue">รายงานผู้ป่วย A150-A182 ทีมารับบริการ ER</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 .'<b style="color:blue">......จำนวนเข้าใช้งาน:::</b>' .$amount
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    [
                        'attribute' => 'regdate',
                        'header' => 'วันมารับบริการ',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    [
                        'attribute' => 'hn',
                        'header' => 'hn',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    [
                        'attribute' => 'fullname',
                        'header' => 'ชื่อ-สกุล',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
					[
                        'attribute' => 'sex',
                        'header' => 'เพศ',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
					[
                        'attribute' => 'age',
                        'header' => 'อายุ',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
					[
                        'attribute' => 'unit_name',
                        'header' => 'แผนก',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    [
                        'attribute' => 'icd10_tm',
                        'header' => 'icd10',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
					[
                        'attribute' => 'icd_name',
                        'header' => 'Diag',
						'headerOptions'=>[ 'style'=>'background-color:#a4e7df'] ,
                    ],
                    ]
                ]
                    );
                    
                    ?>
                </div>
                    

                    <div class="alert alert-info"><?=$sql?> </div>
