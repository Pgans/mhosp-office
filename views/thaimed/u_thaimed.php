<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use common\models\RContributionIpd;
use yii\data\ActiveDataProvider;



$this->title = 'u_thaimed';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รหัสโรคแพทย์แผนไทยประเภท U ยกเว้น U778';
?>
<br>
<b><a>รหัสโรคแพทย์แผนไทยประเภท U ยกเว้น U778</a></b>
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
     echo Html::a('แยกจำนวนรายโรค', ['thaimed/u_list'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']);
    // echo Html::a('เปรียบเทียบ', ['thaimed/surgeon_inout'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']);
  
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>
<div>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'<b style="color:blue">รหัสโรคแพทย์แผนไทยประเภท U ยกเว้น U778</b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                  
                    [
                        'attribute' => 'ICD10_NAME',
                        'header' => 'รหัสโรคU',
                    ],
                    [
                        'attribute' => 'VISITS',
                        'header' =>'ครั้ง',
                        'format' => 'raw',
                        'value' => function($model) {
                            #$visitid = $model['VISITS_ID'];
                            $visits = $model['VISITS'];
                            return Html::a(Html::encode($visits), ['thaimed/u_krung','visits' => $visits],['target'=>'_blank']);
                        }
                     ],
                     [
                        'attribute' => 'KON',
                        'header' => 'คน',
                        'format' => 'raw',
                        'value' => function($model) {
                            #$visitid = $model['VISITS_ID'];
                            $kon = $model['KON'];
                            return Html::a(Html::encode($kon), ['thaimed/u_kon','kon' => $kon],['target'=>'_blank']);
                        }
                     ],
                  ]
                ]
                    );
                    
                    ?>
                    
                    <div class="alert alert-info"><?=$sql?> </div>
<div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>
