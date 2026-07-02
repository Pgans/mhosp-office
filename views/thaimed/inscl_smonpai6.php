<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use common\models\RContributionIpd;
use yii\data\ActiveDataProvider;
$this->title = 'สมุนไพร6ชนิด';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['thaimed/index']];
$this->params['breadcrumbs'][] = 'การจ่ายสมุนไพรแยกตามสิทธิ์การรักษา';
?>
<br>
        <b><a>การจ่ายสมุนไพรแยกตามสิทธิ์</a></b>
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
    // echo Html::a('แยกรายเดือน', ['thaimed/u_9007712month'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']);
    // echo Html::a('เปรียบเทียบ', ['thaimed/surgeon_inout'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']);
  
    ActiveForm::end();?>
    <?php ActiveForm::end(); ?>
</div>
<div>


<?php echo GridView::widget([
        
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue">การจ่ายสมุนไพรแยกตามสิทธิ์การรักษา </b>',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
            ],
            'columns'=>[
                //['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'DRUG_ID',
                    'format'=> 'raw',
                    'header' => 'รหัสยา.',
                    'value' => function ($model) {
                        return '<span class="badge" style="background-color:#009966">' . $model['DRUG_ID'] . '</span>';
                    },
                ],
                [
                    'attribute'=>'DRUG_NAME',
                    'header'=>'ชื่อยา',
                ],
                ['attribute'=> 'ข้าราชการ'],
                ['attribute'=>'ประกันสังคม'],
                ['attribute'=>'อปท'],
                ['attribute'=>'มาตรา8'],
                ['attribute'=>'สิทธิ์ว่าง'],
                [
                    'attribute'=>'รวม',
                    'format'=>'raw',
                    'value'=>function ($model) {
                        $drugid = $model['DRUG_ID'];
                        $amount = $model['รวม'];
                     return html::a(Html::encode($amount),['thaimed/inscl_drugttm_list','drugid'=>$drugid],['class' => 'badge btn-info','target'=>'_blank']);
                    
                    }
            
            
            ],
            ],
        ]
  );
        ?>
     <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>

    </div>